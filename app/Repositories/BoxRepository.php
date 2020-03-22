<?php
/**
 * User: aho
 * Date: 2019-2-28
 * Time: 17:40
 */

namespace NeubusSrm\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Indexing\Box;
use DB;

/**
 * Class PartIndexRepository
 * @package NeubusSrm\Repositories
 */
class BoxRepository implements NeuSrmRepository
{
    /**
     * @var array
     */
    const SEARCH_ARR = [ 'box_name' => ['type' => 'nojoin', 'col' => 'box_name', 'search' => true],
        'box_type' => ['type' => 'nojoin', 'col' => 'box_type', 'search' => true],
        'box_location_code' => ['type' => 'nojoin', 'col' => 'box_location_code', 'search' => true],
        'rfid' => ['type' => 'nojoin', 'col' => 'rfid', 'search' => true],
        'created_at' => ['type' => 'nojoin', 'col' => 'created_at', 'search' => false],
        'created_by_name' => ['type' => 'join', 'table' => 'users', 'col' => 'name', 'relation' => 'createdBy',
        'foreignKey' => 'users.id', 'localKey' => 'boxes.created_by', 'search' => true],
        'updated_by_name' => ['type' => 'join', 'table' => 'users', 'col' => 'name', 'relation' => 'updatedBy',
            'foreignKey' => 'users.id', 'localKey' => 'boxes.updated_by', 'search' => true],
        'part_count' => ['type' => 'nojoin', 'col' => 'parts_count', 'search' => false],
        'is_deleted' => ['type' => 'nojoin', 'col' => 'is_deleted', 'search' => false]];

    /**
     * @return string
     */
    public function getModelClass(): string {
        return Box::class;
    }

    /**
     * @param string $boxName
     * @return bool
     * @throws \Throwable
     */
    public function doesBoxNameNotExist(string $boxName) : bool {

        return Box::where('box_name', 'ilike', [$boxName] ) ->doesntExist();

    }

    /**
     * @param string $projectId
     * @param string $keyword
     * @return Builder
     */
    public function searchQuery(string $projectId, string $keyword) : Builder {
        $query = Box::whereHas('project', function($query) use ($projectId){
            $query->where('project_id', $projectId);
        })->withCount('parts');
        if ($keyword != null && $keyword != '') {
            $query = $query->where(function ($subQuery) use ($keyword) {
                foreach (BoxRepository::SEARCH_ARR as $key => $arr) {
                    if ($arr['search']) {
                        if ($arr['type'] == 'nojoin') {
                            $subQuery = $subQuery->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
                        } else {
                            $subQuery = $subQuery->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword) {
                                $queryHas->where($arr['col'], 'ilike', '%' . $keyword . '%');
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
    public function orderQuery(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy != null && $sortBy != ''){
            $arr = BoxRepository::SEARCH_ARR[$sortBy];
            if ($arr['type'] == 'nojoin'){
                $query = $query->orderBy($arr['col'], $order);
            }
            else{
                $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
        }
        else{
            $query = $query->orderBy('created_at', 'DESC');
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return LengthAwarePaginator
     * @throws \Throwable
     */
    public function boxSearch(Builder $query) : LengthAwarePaginator {
        $results = $query->paginate(25);
        throw_if($results == null || $results->isEmpty(),
            NeuEntityNotFoundException::class, 'There are no boxes for this query');
        return $results;
    }

    /**
     * @param string $projectID
     * @return LengthAwarePaginator
     */
    public function getBoxByBoxId(string $ID) : LengthAwarePaginator {
        $boxes = Box::whereId($ID)
            ->with('project')
            ->withCount('parts')
            ->orderBy('created_at', 'DESC')->paginate(25);

        return $boxes;

    }

    /**
     * @param string $boxName
     * @return int|mixed|string
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function getBoxIdByBoxName(string $boxName): string {
        return $this->getBoxByName($boxName)->id;
    }

    /**
     * @param string $boxName
     * @return Box
     * @throws \Throwable
     */
    public function getBoxByName(?string $boxName): Box {
        $box = Box::whereBoxName($boxName)->first();
        throw_if($box === null, NeuEntityNotFoundException::class, 'No box found by that name');
        return $box;
    }

    /**
     * @param int $boxId
     * @param string $newLocation
     * @return bool
     */
    public function setLocation(int $boxId, string $newLocation): ?bool {
        return Box::whereId($boxId)
            ->update(['box_location_code' => $newLocation]);
    }

    /**
     * @param int $boxId
     * @return bool|null
     */
    public function clearLocation(int $boxId): ?bool {
        return Box::whereId($boxId)
            ->update(['box_location_code' => '']);
    }

}
