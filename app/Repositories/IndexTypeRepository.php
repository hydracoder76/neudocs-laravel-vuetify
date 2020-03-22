<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 10:27
 */

namespace NeubusSrm\Repositories;

use Illuminate\Support\Collection;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;

/**
 * Class IndexTypeRepository
 * @package NeubusSrm\Repositories
 */
class IndexTypeRepository implements NeuSrmRepository
{
	/**
	 * @return string
	 */
	public function getModelClass(): string {
		return IndexType::class;
	}

	/**
	 * @param array $indexData
	 * @return int
	 * @throws \Throwable|NeuDataStoreException
	 */
	public function addNewIndexType(array $indexData) : int {
		$entity = IndexType::create($indexData);
		throw_if($entity == null, NeuDataStoreException::class, 'Error saving new index type');
		$params = collect([
		    'message' => 'Index type added',
            'index_type_name' => $entity->index_type_name,
            'index_type_description' => $entity->index_description,
            'index_label' => $entity->index_label,
            'index_internal_name' => $entity->index_internal_name,
            'company_id' => \Auth::user()->company_id
        ]);
        event(new NeulogModelEvent($entity, $params));
		return $entity->id;
	}

	/**
	 * @param int $id
	 * @return IndexType
	 * @throws \Throwable|NeuEntityNotFoundException
	 */
	public function getIndexTypeById(int $id) : IndexType {
		$indexType = IndexType::find($id);
		$shouldThrow = $indexType == null || count($indexType) > 1;
		throw_if($shouldThrow, NeuEntityNotFoundException::class, 'No valid indexes found');
		return $indexType;
	}

	/**
	 * @param int $id
	 * @return IndexType
	 * @throws NeuEntityNotFoundException
	 * @throws \Throwable
	 */
	public function getIndexTypeWithIndexesLazy(int $id) : IndexType {
		$indexTypeWithIndexes = $this->getIndexTypeById($id)->load('indexes');
		return $indexTypeWithIndexes;
	}

	/**
	 * @return IndexTypesCollection
	 */
	public function getIndexTypes() : IndexTypesCollection{
        return IndexType::all();
    }

	/**
	 * @return Collection
	 */
    public function getIndexDataTypes() : Collection {
		return collect(config('srm.index_type_names'));
    }

    /**
     * @param $indexID
     */
    public function deleteIndexType($indexID){
        IndexType::where('id', $indexID)->delete();
    }

    public function editIndexType(array $indexInfo){
        $editIndexType = IndexType::where('id', $indexInfo['id'])->first();
        $editIndexType->index_label = $indexInfo['index_name'];
        $editIndexType->index_internal_name = str_replace(' ', '_', strtolower($indexInfo['internal_name']));
        $editIndexType->index_description = $indexInfo['description'];
        $editIndexType->save();
    }

}