<?php
/**
 * User: aho
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use DB;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Http\Resources\RequestPartCollection;
use NeubusSrm\Lib\Wrappers\Collections\DataEntryCollection;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\DataEntry;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Request;

/**
 * Class DataEntryRepository
 * @package NeubusSrm\Repositories
 */
class DataEntryRepository implements NeuSrmRepository
{
    /**
     * @return string
     */
    public function getModelClass(): string {
        return Request::class;
    }

    /**
     * @param array $boxData
     * @return int
     */
    public function createNewBox(array $boxData) : int {
        $box = Box::create($boxData);
        $boxColl = collect($boxData);
        $boxColl->put('message', 'Box Created');
        $boxColl->put('company_id', \Auth::user()->company_id);
        event(new NeulogModelEvent($box, $boxColl, 'create', 'info'));
        return $box->id;
    }

    /**
     * @param array $boxData
     * @return array
     */
    public function createNewPart(array $boxData) : array {
        $part = Part::create($boxData);
        $partColl = collect($boxData);
        $partColl->put('message', 'Part Created');
        $partColl->put('company_id', \Auth::user()->company_id);

       // event(new NeulogModelEvent($part, $partColl));
        return ['id' => $part->id, 'created_by' => $part->createdBy->name,
            'created_at' => $part->created_at->format('Y-m-d g:i A')];
    }

    /**
     * @param array $indexData
     * @return int
     */
    public function createNewPartIndex(array $indexData) : int {

        $params = [
            'part_index_value' => $indexData['part_index_value'],
            'index_type_id' => $indexData['index_type_id'],
            'box_id' => $indexData['box_id'],
            'part_id' => $indexData['part_id'],
        ];

        DB::statement("insert into part_indexes (part_index_value,  index_type_id, box_id,  part_id) 
        values (:part_index_value,  :index_type_id, :box_id, :part_id)",$params);

        return 1;
    }

    /**
     * @param string $partId
     * @param array $partIndexValues
     */
    public function logPartIndexCreation(string $partId, array $partIndexValues) : void {

        $part = Part::whereId($partId)->first();
        $partLogColl = collect([
            'part_name' => $part->part_name,
            'part_index_value' => $partIndexValues,
            'message' => 'Part Created',
            'company_id' => \Auth::user()->company_id
        ]);
        event(new NeulogModelEvent($part, $partLogColl));

    }

    /**
     * @param string $orderBy
     * @return array
     */
    public function listDataEntries(string $orderBy) {

        $dataEntries = DB::table('part_indexes')
            ->selectRaw(" part_indexes.box_id, string_agg(part_index_value,' | ') as col_part_index_value , max(box_name) as box_name, max(box_location_code) as box_location_code ,  max(part_name ) as part_name   ")
            ->leftJoin('parts','part_indexes.part_id','=', 'parts.id')
            ->leftJoin('boxes','part_indexes.box_id','=', 'boxes.id')
            ->groupBy('part_indexes.box_id')
            ->paginate(25);
        return $dataEntries;

    }



    /**
     * @param string $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Throwable
     */
    public  function searchDataEntries(string $keyword):LengthAwarePaginator{

        $results = DB::table('part_indexes')
            ->selectRaw(" part_indexes.box_id, string_agg(part_index_value,' | ') as col_part_index_value , max(box_name) as box_name, max(box_location_code) as box_location_code ,  max(part_name ) as part_name   ")
            ->leftJoin('parts','part_indexes.part_id','=', 'parts.id')
            ->leftJoin('boxes','part_indexes.box_id','=', 'boxes.id')
            ->where('box_name', 'like', '%'. $keyword . '%')
            ->orWhere('box_location_code', 'like', '%'. $keyword . '%')
            ->orWhere('part_name', 'like', '%'. $keyword . '%')
            ->orWhere('part_index_value', 'like', '%'. $keyword . '%')
            ->groupBy('part_indexes.box_id');

        throw_if($results == null,
            UserNotFoundException::class, 'No result exist for this search');

        $dataentriesResults = $results->paginate(25);


        return $dataentriesResults;
    }

    /**
     * @param string $projectId
     * @return IndexTypesCollection
     */
    public function getIndexesByProjectId(string $projectId) : IndexTypesCollection {
        $results = IndexType::whereHas('project', function($query) use ($projectId){
            $query->where('id', $projectId);
        })->get();
        return $results;
    }

    /**
     * @param string $boxId
     * @return Project
     * @throws \Throwable
     */
    public function getProjectByBoxId(string $boxId) : Project {
        $box = Box::where('id', $boxId)->first();
        throw_if($box == null,
            EntityNotFoundException::class, 'No box for this id');
        $project = $box->project;
        return $project;
    }
}
