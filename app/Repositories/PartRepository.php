<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\PartIndexesCollection;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestsCollection;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\FileUpload;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Models\Util\SrmLog;
use Nexmo\Call\Collection;


/**
 * Class PartRepository
 * @package NeubusSrm\Repositories
 */
class PartRepository implements NeuSrmRepository
{
    /**
     * @var array
     */
    const SEARCH_ARR = ['part_name' => ['type' => 'nojoin', 'col' => 'p.part_name'],
        'box_name' => ['type' => 'join', 'table' => 'boxes sort', 'col' => 'sort.box_name',
            'foreignKey' => 'sort.id', 'localKey' => 'p.box_id']];

    /**
     * @var array
     */
    const SEARCH_ARR_ALL = ['part_name' => ['type' => 'nojoin', 'col' => 'part_name'],
        'box_name' => ['type' => 'join', 'table' => 'boxes', 'col' => 'box_name', 'relation' => 'box',
            'foreignKey' => 'boxes.id', 'localKey' => 'parts.box_id']];

    /**
     * @var array
     */
    const DATA_SEARCH_ARR = ['part_name' => ['type' => 'nojoin', 'col' => 'part_name'],
        'created_at' => ['type' => 'nojoin', 'col' => 'parts.created_at'],
        'created_by' => ['type' => 'join', 'table' => 'users', 'col' => 'name', 'relation' => 'createdBy',
            'foreignKey' => 'users.id', 'localKey' => 'parts.created_by'],
         'part_index' => ['type' => 'join', 'table' => 'part_indexes', 'col' => 'part_index_value', 'relation' => 'indexes']];

    /**
     * @var int
     */
    const PARTS_PER_PAGE = 10;

    /**
     * @return string
     */
    public function getModelClass(): string {
        return Part::class;
    }

    /**
     * @param iterable $indexes
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @param int $page
     * @return array
     */
    public function getPartsByIndexes(iterable $indexes, string $projectID, string $sortBy, string $order, int $page) : array {
        if (count($indexes) > 0) {
            $parts = $this->getPartsByIndexesQuery($indexes, $projectID, $sortBy, $order, $page);
            $partOrder = $parts['parts'];
            $partList = Part::find($partOrder);
            $partList = $partList->sortBy(function($model) use ($partOrder){
                return array_search($model->getKey(), $partOrder);
            })->values();
            $numResults = $parts['total'];
        }
        else{
            $parts = Part::where('parts.project_id', $projectID);
            $parts = $this->partsOrder($parts, $sortBy, $order, self::SEARCH_ARR_ALL);
            $partListPage = $parts->paginate(self::PARTS_PER_PAGE);
            $partList = $partListPage->getCollection();
            $numResults = $partListPage->total();
        }
        $partList->load('box', 'indexes');
        return ['parts' => $partList, 'total' => $numResults];
    }

    /**
     * @param array $indexes
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @param int $page
     * @return array
     */
    public function getPartsByIndexesQuery(array $indexes, string $projectID, string $sortBy, string $order, int $page) : array {
        $index = 0;
        $queryArr = ['project_id' => $projectID];
        $queryString = '';
        foreach ($indexes as $key => $field) {
            $indexID = IndexType::where('index_internal_name', '=', $key)->first()->id;
            $tableName = 'p' . $index;
            $queryArr['indexID' . $index] = $indexID;
            $queryArr['field' . $index] = strtolower($field);
            $queryString .= <<<SQL
              SELECT p.id, p.part_name, p.box_id from part_indexes $tableName, parts p WHERE p.project_id = :project_id AND p.id =
              {$tableName}.part_id AND {$tableName}.index_type_id = :indexID{$index} AND LOWER({$tableName}.part_index_value) =
              :field{$index} AND {$tableName}.is_deleted = false
SQL;
            if ($index + 1 < count($indexes)){
                $queryString .= ' INTERSECT ';
            }
            $index += 1;
        }
        $queryStringCount = 'SELECT COUNT(*) FROM (' . $queryString . ') I';
        $queryString = $this->getPartsByIndexesQuerySort($queryString, $sortBy, $order, $page);
        $partsCount = DB::select(DB::raw($queryStringCount), $queryArr)[0]->count;
        $parts = array_column(DB::select(DB::raw($queryString), $queryArr), 'id');
        return ['parts' => $parts, 'total' => $partsCount];
    }

    /**
     * @param string $queryString
     * @param string $sortBy
     * @param string $order
     * @param int $page
     * @return string
     */
    public function getPartsByIndexesQuerySort(string $queryString, string $sortBy, string $order, int $page = 1) : string {
        $queryString = ' SELECT p.id, p.part_name FROM (' . $queryString . ') as p ';
        if ($sortBy !== null && $sortBy !== '') {
            if (array_key_exists($sortBy, self::SEARCH_ARR)){
                $searchInfo = self::SEARCH_ARR[$sortBy];
                $extraQuery = '';
            }
            else{
                $indexTypeId = $this->getIndexTypeByName($sortBy)->id;
                $searchInfo = ['type' => 'join', 'table' => 'part_indexes sort', 'col' => 'sort.part_index_value',
                    'foreignKey' => 'p.id', 'localKey' => 'sort.part_id'];
                $extraQuery = ' AND sort.index_type_id = ' . $indexTypeId . ' ';
            }
            if ($searchInfo['type'] === 'join') {
                $queryString .= ' LEFT JOIN ' . $searchInfo['table'] . ' ON ' .
                    $searchInfo['localKey'] . ' = ' . $searchInfo['foreignKey'] . $extraQuery;
            }
            $queryString = $queryString . ' ORDER BY ' . $searchInfo['col'] . ' ' . $order;
        }
        if ($page > 1){
            $queryString .= ' OFFSET ' . ($page - 1) * self::PARTS_PER_PAGE;
        }
        $queryString .= ' LIMIT ' . self::PARTS_PER_PAGE;
        return $queryString;
    }

    /**
     * @param int $boxID
     * @return array
     */
    public function getPartsByBox(int $boxID, string $keyword, string $sortBy, string $order) : array {
        $parts = Part::where('parts.box_id', $boxID);
        $maxPartNum = $parts->max('part_name');
        $parts = $this->searchQuery($parts, $keyword);
        $parts = $this->partsOrder($parts, $sortBy, $order, PartRepository::DATA_SEARCH_ARR);
        $partList = $parts->paginate(25);
        if ($maxPartNum == null){
            $maxPartNum = 0;
        }
        return ['results' => $partList->getCollection(), 'total' => $partList->total(), 'maxPartNum' => $maxPartNum];
    }

    /**
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function searchQuery(Builder $query, string $keyword) : Builder {
        if ($keyword != null && $keyword != '') {
            $query = $query->where(function ($subQuery) use ($keyword) {
                foreach (PartRepository::DATA_SEARCH_ARR as $key => $arr) {
                    if ($arr['type'] == 'nojoin') {
                        $subQuery = $subQuery->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
                    } else {
                        $subQuery = $subQuery->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword){
                            $queryHas->where($arr['col'], 'ilike', '%' . $keyword . '%');
                        });
                    }
                }
                return $subQuery;
            });
        }
        return $query;
    }

	/**
	 * @param string $projectId
	 * @return PartsCollection
	 * @throws \Throwable|NeuEntityNotFoundException
	 */
    public function getPartsByProjectId(string $projectId) : PartsCollection {
	    $parts = Part::whereProjectId($projectId)->get();
	    throw_if($parts->count() == 0,
		    NeuEntityNotFoundException::class, 'No parts found for that project');
        //TODO: use newCollection and verify that it works with no errors
	    return new PartsCollection($parts);
    }

    /**
     * @param array $partData
     * @return PartsCollection
     */
    public function getPartFiles(array $partData) : PartsCollection{
        $parts = Part::find($partData);
        $parts->load('requests', 'files', 'box');
        return $parts;
    }

    /**
     * @param string $partID
     */
    public function markUploaded(string $partID){
        $part = Part::where('id', $partID);
        $part->update(['uploaded_to'=>true]);
    }

    /**
     * @param string $partID
     * @return Builder
     */
    public function partRequestProgress(string $partID) : Builder{
        $requestQuery = Request::where('is_fulfilled', false)->whereHas('parts', function($query) use ($partID){
            $query->where('id', $partID);
        });
        $requestQuery->update(['is_in_process'=>true]);
        return $requestQuery;
    }

    /**
     * @param Builder $requestQuery
     */
    public function partRequestFulfill(Builder $requestQuery) : RequestsCollection{
        $userID = \Auth::user()->id;
        $requestQuery = $requestQuery->whereDoesntHave('parts', function($query){
            $query->where('uploaded_to', false);
        });
        $requests = $requestQuery->get();
        $requestQuery->update(['is_fulfilled'=>true, 'fulfilled_on'=>Carbon::now(), 'fulfilled_by'=>$userID]);
        $requests->load('parts');

        if(!$requests->isEmpty()){
            $logs = SrmLog::where('message', '=', 'Request Ready for Review')
                          ->whereIn('details',[$requests[0]->request_name])->get();
            if(!$logs->count() ) {
                $requestNumbers = $requests->map(static function (Request $request) {
                    return ['name' => 'Request Name', 'value' => $request->request_name . ' '];
                });
                event(new NeulogActionEvent('Request Ready for Review', $requestNumbers));
            }
        }
        return $requests;
    }

    /**
     * @param string $name
     * @return IndexType
     */
    public function getIndexTypeByName(string $name) : IndexType{
        $indexType = IndexType::where('index_internal_name', $name)->first();
        throw_if($indexType == null, NeuEntityNotFoundException::class, 'No index type by this name');
        return $indexType;
    }

    /**
     * @param int $indexTypeId
     * @param string $value
     * @return PartIndexesCollection
     */
    public function getPartValuesAuto(int $indexTypeId, string $value) : PartIndexesCollection{
        $value = strtolower($value) . '%';
        $partIndexes = DB::select('SELECT get_part_index_value_by_keyword(?,?,?) as part_index_value', [ $indexTypeId,$value,8]);
        return new PartIndexesCollection($partIndexes);
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function partsOrder(Builder $query, string $sortBy, string $order, array $dataArray) : Builder {
        if ($sortBy != null && $sortBy != ''){
            if (array_key_exists($sortBy, $dataArray)) {
                $arr = $dataArray[$sortBy];
                if ($arr['type'] == 'nojoin'){
                    $query = $query->orderBy($arr['col'], $order);
                }
                else{
                    $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                        ->orderBy($arr['table'] . '.' . $arr['col'], $order)->select('parts.*');
                }
            }
            else{
                $indexTypeId = $this->getIndexTypeByName($sortBy)->id;
                $query = $query->leftJoin('part_indexes', function($join) use ($indexTypeId){
                    $join->on('part_indexes.part_id', '=', 'parts.id');
                    $join->where('part_indexes.index_type_id', '=', $indexTypeId);
                })->orderBy('part_indexes.part_index_value', $order)->select('parts.*');
            }
        }
        return $query;
    }

    public function fulfillPart(string $partID, array $dataAddChecked){
        $part = $this->getPartById($partID);
        foreach ($dataAddChecked as $key => $item){
           if($key === $partID){
                $part->mediaTypes()->detach();
                if(count($item)===0) {
                    $item = [1];
                }
                $part->mediaTypes()->attach($item);
           }
        }
        if (!$part->uploaded_to){
            $box = Box::where('id', $part->box_id)->first();
            FileUpload::create([
                'box_number' => $box->box_name,
                'part_name' => $part->part_name,
                'part_id' => $partID,
                'uploaded_by' => \Auth::user()->id,
                'true_file_name' => 'Manually fulfilled',
                'hashed_file_name' => '',
                'file_mime' => 'txt',
                'current_fs_location' => 'images/Fulfillment_Placeholder_File.txt',
                'is_scanned' => false
            ]);
        }
    }

    /**
     * @param string $partID
     * @return mixed
     */
    public function getPartById(string $partID) : Part 
    {
        return Part::where('id', $partID)->first();
    }
    
    /**
     * @param string $requestId
     * @return mixed
     */
    public function getPartsByRequest(string $requestId) : Part 
    {
        return Part::with('requests', 'mediaTypes')->where('request_id_ref','=', $requestId)->get();
    }
}
