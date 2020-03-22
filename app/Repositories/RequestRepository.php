<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Traits\WorksWithModels;
use NeubusSrm\Lib\Wrappers\Collections\RequestPartsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestsCollection;
use NeubusSrm\Models\Indexing\FileUpload;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Org\Project;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Eloquent\Builder;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Repositories\PartRepository;
use Auth;


class RequestRepository implements NeuSrmRepository
{
    use WorksWithModels;
    /**
     * @var array
     */
    const SEARCH_ARR = ['request_number' => ['col' => 'request_name'],
        'status' => ['col' => ''],
        'requested_on' => ['col' => 'created_at'],
        'fulfilled_on' => ['col' => 'fulfilled_on']];

    /**
     * @var array
     */
    const TODO_SEARCH_ARR = ['request' => ['type' => 'join', 'table' => 'requests', 'col' => 'request_name', 'relation' => 'request',
            'foreignKey' => 'requests.id', 'localKey' => 'request_parts.request_id_ref', 'search' => true],
        'box' => ['type' => 'linkJoin', 'table' => 'boxes', 'col' => 'box_name', 'relation' => 'part.box',
            'foreignKey' => 'boxes.id', 'localKey' => 'parts.box_id', 'search' => true, 'linkTable' => 'parts',
            'linkForeignKey' => 'parts.id', 'linkLocalKey' => 'request_parts.part_id_ref'],
        'part' => ['type' => 'join', 'table' => 'parts', 'col' => 'part_name', 'relation' => 'part',
            'foreignKey' => 'parts.id', 'localKey' => 'request_parts.part_id_ref', 'search' => true],
        'location' => ['type' => 'linkJoin', 'table' => 'boxes', 'col' => 'box_location_code', 'relation' => 'part.box',
            'foreignKey' => 'boxes.id', 'localKey' => 'parts.box_id', 'search' => true, 'linkTable' => 'parts',
            'linkForeignKey' => 'parts.id', 'linkLocalKey' => 'request_parts.part_id_ref'],
        'requested_at' => ['type' => 'nojoin', 'col' => 'created_at', 'search' => false],
        'request_status' => ['type' => 'nojoin', 'col' => 'created_at', 'search' => false],
        'completion' => ['type' => 'join', 'table' => 'requests', 'col' => 'is_in_process', 'relation' => 'request',
            'foreignKey' => 'requests.id', 'localKey' => 'request_parts.request_id_ref', 'search' => false],
        'request_review' => ['type' => 'join', 'table' => 'requests', 'col' => 'comment', 'relation' => 'request',
            'foreignKey' => 'requests.id', 'localKey' => 'request_parts.request_id_ref', 'search' => true]];

    /**
     * @var array
     */
    const COMPLETED_SEARCH_ARR = ['request' => ['type' => 'join', 'table' => 'requests', 'col' => 'request_name', 'relation' => 'request',
            'foreignKey' => 'requests.id', 'localKey' => 'request_parts.request_id_ref', 'search' => true,
        'projectRef' => 'requests.project_id'],
        'box' => ['type' => 'linkJoin', 'table' => 'boxes', 'col' => 'box_name', 'relation' => 'part.box',
            'foreignKey' => 'boxes.id', 'localKey' => 'parts.box_id', 'search' => true, 'linkTable' => 'parts',
            'linkForeignKey' => 'parts.id', 'linkLocalKey' => 'request_parts.part_id_ref', 'projectRef' => 'boxes.project_id'],
        'indexValue' => ['type' => 'linkJoin', 'table' => 'part_indexes', 'col' => 'part_index_value', 'relation' => 'part.indexes',
            'foreignKey' => 'part_indexes.part_id', 'localKey' => 'parts.id', 'search' => true, 'linkTable' => 'parts',
            'linkForeignKey' => 'part_indexes.part_id', 'linkLocalKey' => 'request_parts.part_id_ref', 'projectRef' => 'parts.project_id'],
        'part' => ['type' => 'join', 'table' => 'parts', 'col' => 'part_name', 'relation' => 'part',
            'foreignKey' => 'parts.id', 'localKey' => 'request_parts.part_id_ref', 'search' => false],
        'location' => ['type' => 'linkJoin', 'table' => 'boxes', 'col' => 'box_location_code', 'relation' => 'part.box',
            'foreignKey' => 'boxes.id', 'localKey' => 'parts.box_id', 'search' => true, 'linkTable' => 'parts',
            'linkForeignKey' => 'parts.id', 'linkLocalKey' => 'request_parts.part_id_ref', 'projectRef' => 'boxes.project_id'],
        'requested_at' => ['type' => 'nojoin', 'col' => 'created_at', 'search' => false],
        'completed_at' => ['type' => 'nojoin', 'col' => 'updated_at', 'search' => false],
        'completed_by' => ['type' => 'linkJoin', 'table' => 'users', 'col' => 'name', 'relation' => 'request.fulfilledBy',
            'foreignKey' => 'users.id', 'localKey' => 'requests.fulfilled_by', 'search' => true, 'linkTable' => 'requests',
            'linkForeignKey' => 'requests.id', 'linkLocalKey' => 'request_parts.request_id_ref', 'projectRef' => 'requests.project_id']];
    /**
     * @return string
     */
    public function getModelClass(): string {
        return Request::class;
    }

    /**
     * @param Builder $partQuery
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @param bool $review
     * @param bool $byProject
     * @return array
     */
    public function getRequestsByIndexes(array $indexes, string $projectID, string $sortBy, string $order, bool $review = false, bool $byProject = false) : array {
        $requests = Request::where('project_id', $projectID);
        if ($review){
            $requests = $requests->where('is_fulfilled', true)->where('is_reviewed', false);
        }
        if (!$byProject) {
            $requests = $this->queryRequestsByParts($requests, $indexes, $projectID);
        }
        $requests = $this->orderQuery($requests, $sortBy, $order);
        $requestResults = $requests->paginate(25);
        $requestCollectResults = $requestResults->getCollection();
        $requestCollectResults->load('parts.indexes');
        return ['requests' => $requestCollectResults, 'total' => $requestResults->total()];
    }

    public function getRequestsByProject(string $projectID) : RequestsCollection{
        $requests = Request::where('project_id', $projectID);
        $requestResults = $requests->paginate(25);
        $requestCollectResults = $requestResults->getCollection();
        return $requestCollectResults;
    }

    /**
     * @param Builder $partQuery
     * @param Builder $requests
     * @return Builder
     */
    public function queryRequestsByParts(Builder $requests, array $indexes, string $projectId) : Builder{
        $requests = $requests->whereHas('parts', function($query) use ($indexes, $projectId){
            $query = $query->where('parts.project_id', $projectId);
            foreach($indexes as $key => $index){
                $indexID = IndexType::where('index_internal_name', '=', $key)->first()->id;
                $query = $query->whereHas('indexes', function($query) use ($key, $index, $indexID){
                    $query->where('index_type_id', '=', $indexID)->whereRaw('LOWER(part_index_value) = ?',[strtolower($index)]);
                });
            }
        });
        return $requests;
    }

    /**
     * @param  iterable  $partIDs
     * @param  string  $projectID
     * @param  string  $name
     * @param  string  $externalComment
     */
    public function createNewRequest(iterable $partIDs, string $projectID, string $name, string $externalComment) : void {
        $user = Auth::user();
        $project = Project::where('id', '=', $projectID)->first();
        $request = factory(Request::class)->create(['company_id'=>$project->company_id,
            'created_by'=>$user->id, 'updated_by'=>$user->id, 'is_fulfilled'=>false, 'is_in_process'=>false,
            'fulfilled_by'=>null, 'fulfilled_on'=>null, 'project_id'=>$projectID, 'request_name' => $name, 'external_comment' => $externalComment]);
        $request->parts()->attach($partIDs);

        if ($request instanceof Request) {
            $params = collect([
                'message' => 'Request Created',
                'request_name' => $request->request_name
            ]);
            event(new NeulogModelEvent($request, $params));
        }
    }

    /**
     * @param string $projectID
     * @return LengthAwarePaginator
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function listRequests(string $projectID): LengthAwarePaginator {


        try {
        // this is still broken, mainly due to a front end issue
            $requestParts = RequestPart::whereHas('request', function ($query) use ($projectID) {
                $query->where('is_fulfilled', 'false')->where('project_id', $projectID);
            })->orderBy('request_id_ref')->orderBy('created_at', 'DESC')->paginate(25);
            throw_if($requestParts === null || $requestParts->isEmpty(),
                NeuEntityNotFoundException::class, 'There are no requests for this project');
            $requestParts->load('request', 'part', 'lockedBy');
        }
        catch (QueryException $exception) {
            \Log::error("Internal exception: {$exception->getMessage()}");
            throw new NeuEntityNotFoundException('There are no requests for this project');
        }

        return $requestParts;
    }

    /**
     * @param string $project
     * @param string $orderBy
     * @return LengthAwarePaginator
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function listCompletedRequests(string $project, string $orderBy) : LengthAwarePaginator{
        $requestParts = RequestPart::whereHas('request', function($query) use ($project){
          $query->where('project_id', $project)->where('is_reviewed', 'true');
        })->orderBy('request_id_ref')->orderBy('created_at', 'DESC')->paginate(25);
        throw_if($requestParts == null || $requestParts->count() == 0,
            NeuEntityNotFoundException::class, 'No completed requests found');
        $requestParts->load('request', 'part');
        return $requestParts;
    }

    /**
     * @param string $project
     * @param string $filterBy
     * @return LengthAwarePaginator
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function filterCompletedRequests(string $project, string $filterBy, string $sortBy, string $order) : array {

        if ($filterBy != null && $filterBy != ''){
            $requestParts = $this->completedSearchQuery($project, $filterBy);
        }
        else{
            $requestParts = RequestPart::whereHas('request', function($query) use ($project){
                $query->where('project_id', $project)->where('is_reviewed', 'true');
            });
        }
        if ($sortBy != null && $sortBy != ''){
            $requestParts = $this->todoOrderQuery($requestParts, $sortBy, $order, 'completed');
        }
        else{
            $requestParts = $requestParts->orderBy('request_id_ref')->orderBy('created_at', 'DESC');
        }
        $results = $requestParts->paginate(25);
        neu_throw_if($results == null || $results->total() == 0,
            NeuEntityNotFoundException::class, 'No completed requests found');
        $total = $results->total();
        $coll = $results->getCollection();
        if ($filterBy != null && $filterBy != ''){
            $coll = RequestPart::hydrate($coll->toArray());
        }
        $coll->load('request', 'part');
        return ['results' => $coll, 'length' => $total];
    }

    /**
     * @param array $parts
     * @return RequestPartsCollection
     */
    public function lockRequests(array $parts) : RequestPartsCollection{
        
        $user = Auth::user();
        $partQuery= RequestPart::query();
        foreach($parts as $part){
            $partQuery = $partQuery->orWhere(function($query) use ($part){
                $query->where('request_id_ref', $part['request_id'])->where('part_id_ref', $part['part_id']);
            });
        }        
        $partQuery->update(['locked_by'=> $user->id]);
        $requestParts = $partQuery->get();
        $requestParts->load('request', 'part');
        return $requestParts;
    }

    /**
     * @param array $parts
     */
    public function unlockRequests(array $parts, array $dataAddChecked) : void
    {        
        $partQuery= RequestPart::query();
        foreach($parts as $part){
            $partQuery = $partQuery->orWhere(function($query) use ($part){
                $query->where('request_id_ref', $part['request_id'])->where('part_id_ref', $part['part_id']);
            });

            $partModel = PartRepository::getPartById(['part_id']);
            foreach ($dataAddChecked as $key => $value){
                if($key === $partModel->id){
                    $partModel->mediaTypes()->detach();
                    if(!count($value)) {
                        $value = [1];
                    }
                    $partModel->mediaTypes()->attach($value);
                }

            }
        }
        $partQuery->update(['locked_by'=> null]);
    }

    /**
     * @param string $projectId
     * @return int
     */
    public function getNumRequestForDay(string $projectId) : int{
        $count = Request::whereDate('created_at', Carbon::today())->where('project_id', $projectId)->count();
        return $count;
    }

    /**
     * @param PartsCollection $partList
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function orderQuery(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy != null && $sortBy != ''){
            $arr = self::SEARCH_ARR[$sortBy];
            if ($sortBy == 'status'){
                $query = $query->orderBy('is_reviewed', $order)->orderBy('is_fulfilled', $order)->orderBy('is_in_process', $order);
            }
            else{
                $query = $query->orderBy($arr['col'], $order);
            }
        }
        return $query;
    }

    /**
     * @param string $projectId
     * @return Builder
     */
    public function todoSearchQueryBegin(string $projectId) : Builder {
        $query = RequestPart::whereHas('request', function ($subQuery) use ($projectId) {
            $subQuery->where('is_fulfilled', 'false')->where('project_id', $projectId);
        });
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $keyword
     * @param string $arrType
     * @return Builder
     */
    public function todoSearchQuery(Builder $query, string $keyword, string $arrType = 'todo') : Builder {
        if ($keyword != null && $keyword != '') {
            $arrSearch = self::TODO_SEARCH_ARR;
            if ($arrType == 'completed'){
                $arrSearch = self::COMPLETED_SEARCH_ARR;
            }
            $query = $query->where(function ($subQuery) use ($keyword, $arrSearch) {
                foreach ($arrSearch as $key => $arr) {
                    if ($arr['search']) {
                        if ($arr['type'] == 'nojoin') {
                            $subQuery = $subQuery->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
                        } else if ($key !== 'index' && $key !== 'indexValue') {
                            $subQuery = $subQuery->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword) {
                                $queryHas->where($arr['col'], 'ilike', '%' . $keyword . '%');
                            });
                        } else{
                            $subQuery = $subQuery->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword) {
                                $queryHas->whereRaw("LOWER({$arr['col']}) LIKE ?", [strtolower($keyword) . '%']);
                            });
                        }
                    }
                }
                return $subQuery;
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function todoOrderQuery($query, string $sortBy, string $order, string $arrType) {
        if ($sortBy != null && $sortBy != ''){
            if ($arrType == 'todo') {
                $arr = self::TODO_SEARCH_ARR[$sortBy];
            }
            else{
                $arr = self::COMPLETED_SEARCH_ARR[$sortBy];
            }
            if ($arr['type'] == 'nojoin'){
                $query = $query->orderBy($arr['col'], $order);
            }
            else if ($arr['type'] == 'join'){
                $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
            else{
                $query = $query->leftJoin($arr['linkTable'], $arr['linkForeignKey'], '=', $arr['linkLocalKey'])
                    ->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->select('request_parts.*')->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return LengthAwarePaginator
     * @throws \Throwable
     */
    public function todoSearch(Builder $query) : LengthAwarePaginator {
        $results = $query->paginate(25);
        throw_if($results == null || $results->isEmpty(),
            NeuEntityNotFoundException::class, 'There are no requests for this query');
        return $results;
    }

    /**
     * @param string $projectId
     * @param string $searchText
     * @return \Illuminate\Database\Query\Builder
     */
    public function completedSearchQuery(string $projectId, string $searchText) : \Illuminate\Database\Query\Builder {
        $searchText = '%' . strtolower($searchText) . '%';
        $subQuery = DB::query();
        $first = true;
        foreach(self::COMPLETED_SEARCH_ARR as $key => $index){
            if ($index['search']) {
                $selectFrom = $index['table'] . ' ,request_parts ';
                if ($index['type'] === 'linkJoin') {
                    $selectFrom .= ' , ' . $index['linkTable'];
                }
                $arrQuery = DB::table(DB::raw($selectFrom))->select(DB::raw('request_parts.*'))
                    ->where($index['projectRef'], $projectId)->whereRaw("LOWER({$index['col']}) LIKE ?", [$searchText])
                    ->whereRaw("{$index['foreignKey']} = {$index['localKey']}");
                if ($index['type'] === 'linkJoin') {
                    $arrQuery = $arrQuery->whereRaw("{$index['linkForeignKey']} = {$index['linkLocalKey']}");
                }
                if ($first) {
                    $subQuery = $arrQuery;
                } else {
                    $subQuery = $subQuery->union($arrQuery);
                }
                $first = false;
            }
        }
        $query = DB::table(DB::raw("requests r, ({$subQuery->toSql()}) as request_parts"))->mergeBindings($subQuery)
            ->select(DB::raw('request_parts.*'))->whereRaw('r.id = request_parts.request_id_ref')->whereRaw('r.is_reviewed = true');
        return $query;
    }

    /**
     * @param string $requestId
     * @param string $comment
     * @param bool $accept
     */
    public function review(string $requestId, string $comment, bool $accept) : void {
        $request = Request::where('id', $requestId)->first();
        neu_throw_if($request == null, NeuEntityNotFoundException::class, 'There are no requests with this id');
        if ($accept){
            $request->is_reviewed = true;
        }
        else{
            $request->is_fulfilled = false;
        }
        $request->comment = $comment;
        $request->save();
        $text = $accept ? 'Request Reviewed' : 'Request Rejected';
        if ($request->comment !== '') {
            $params = collect([['name' => 'Request Number', 'value' => $request->request_name . ' '], ['name' => 'Comment', 'value' => $request->comment ]]);
        }else{
            $params = collect([['name' => 'Request Number', 'value' => $request->request_name . ' ']]);
        }

        event(new NeulogActionEvent($text, $params));
    }

    /**
     * @param FileUpload $fileEntity
     * @param array $updateData
     */
    public function requestReopened(FileUpload $fileEntity, array $updateData) : void {
        $query = $fileEntity->part->requests()->done();
        $requests = $query->get();
        $query->update($updateData);
        foreach ($requests as $request) {
            if ($request instanceof Request) {
                $params = collect([
                    'message' => 'Request Reopened',
                    'request_name' => $request->request_name
                ]);
                event(new NeulogModelEvent($request, $params, 'update'));
            }
        }

    }       
}
