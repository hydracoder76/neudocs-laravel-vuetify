<?php
/**
 * User: mlawson
 * Date: 2018-12-11
 * Time: 09:23
 */

namespace NeubusSrm\Services;

use Illuminate\Support\Collection;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Models\Org\MediaType;
use NeubusSrm\Repositories\IndexTypeRepository;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;

/**
 * Class IndexService
 * @package NeubusSrm\Services
 */
class IndexService extends NeuSrmService
{


	/**
	 * @var IndexTypeRepository
	 */
	private $indexTypeRepo;


	/**
	 * IndexService constructor.
	 * @param IndexTypeRepository $indexTypeRepository
	 */
	public function __construct(IndexTypeRepository $indexTypeRepository) {

		$this->indexTypeRepo = $indexTypeRepository;
	}

	/**
	 * @return Collection
	 */
	public function getIndexTypeNameList() : Collection {
		return $this->indexTypeRepo->getIndexDataTypes();
	}

	/**
	 * @param Collection $indexTypeList
	 * @return array
	 */
	public function convertIndexTypeList(Collection $indexTypeList) : array{
	    $indexTypes = $indexTypeList->map(function($indexType){
	         return ['name' => $indexType,
		         'value' => strtolower(str_replace(' ', '_', $indexType))];
        });
	    return $indexTypes->toArray();
    }

	/**
	 * @param array $indexInfo
	 * @return int
	 * @throws NeuSrmException
	 * @throws \Throwable
	 */
	public function saveNewIndexTypesForProject(array $indexInfo) : int {
		$dataToStore = [
			'index_type_name' => 'text',
			'index_label' => $indexInfo['index_name'],
			'index_internal_name' => str_replace(' ', '_', strtolower($indexInfo['index_int_name'])),
			'index_description' => $indexInfo['index_description'],
			'is_required' => false,
			'is_required_double' => false,
			'has_validation' => false,
			'validation_regex' => $indexInfo['validation_regex'] ?? '',
			'validation_rule_class_name' => $indexInfo['validation_class_name'] ?? null,
			'project_id' => $indexInfo['project_id'],
			'created_by' => \Auth::id()
		];

		try {
			$indexID = $this->indexTypeRepo->addNewIndexType($dataToStore);

			return $indexID;

		}
		catch (NeuSrmException | \Throwable $exception) {
			throw $exception;
		}

	}

    /**
     * @param Project $project
     * @return iterable
     */
	public function getIndexesByProject(Project $project) : array 
	{
	    $indexList = $project->indexes;
	    $indexes = $indexList->map(function(IndexType $index){
            return ['index_name' => $index->index_label, 'internal_name' => $index->index_internal_name,
                'description' => $index->index_description, 'id' => $index->id,
                'created_at' => $index->created_at->format('Y-m-d g:i A')];
        });
	    return $indexes->toArray();
    }


    /**
     * @param Project $project
     * @return array|Collection|IndexTypesCollection|IndexType[]
     * @throws NeuSrmException
     */
    public function getIndexesByProjectForDataEntryPartSchemaOnly(Project $project)  {
        $partSchemas = [];
        $partSchema = [];
        $indexList = $project->indexes;
        throw_if(count($indexList) <= 0, NeuEntityNotFoundException::class, 'There are no indexes for this project');

        $partSchemas = $indexList->map(function($index) use ($indexList, $partSchema){
            array_push($partSchema, ['key' => 'part_id', 'label' => 'Part', 'visible'=> false, 'editable'=>false]);
            array_push($partSchema, ['key' => 'part_name', 'label' => 'Part', 'visible'=> true, 'editable'=>false, 'sortable' => true]);
            foreach($indexList as $indexType){
                array_push($partSchema, ['key' => $indexType->index_internal_name, 'label' => $indexType->index_label,
                    'visible' => true, 'editable'=>true, 'type'=> 'TEXT', 'sortable' => true]);
            }

            array_push($partSchema, ['key' => 'created_at', 'label' => 'Created On','editable'=>false, 'visible'=> true, 'sortable' => true]);
            array_push($partSchema, ['key' => 'created_by', 'label' => 'Created By','editable'=>false, 'visible'=> true, 'sortable' => true]);
            return $partSchema;


        });
        return $partSchemas;
    }

    /**
     * @param string $indexID
     */
    public function deleteIndexType(string $indexID){
        $this->indexTypeRepo->deleteIndexType($indexID);
    }

    /**
     * @param array $indexInfo
     */
    public function editIndexType(array $indexInfo){
        try {
            $this->indexTypeRepo->editIndexType($indexInfo);
        }
        catch (NeuSrmException | \Throwable $exception) {
            throw $exception;
        }
    }
}
