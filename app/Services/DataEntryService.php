<?php
/**
 * User: aho
 * Date: 11/12/18
 * Time: 12:30 PM
 */

namespace NeubusSrm\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Http\Resources\RequestCollection;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\NeuUserInvalidCredentialsException;
use NeubusSrm\Lib\Exceptions\ProjectNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Repositories\ProjectRepository;
use NeubusSrm\Repositories\RequestRepository;
use NeubusSrm\Repositories\DataEntryRepository;
use NeubusSrm\Repositories\PartRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Http\Resources\DataEntryCollection;
use Crypt, Auth;
use NeubusSrm\Repositories\BoxRepository;

/**
 * Class RequestService
 * @package NeubusSrm\Services
 */
class DataEntryService extends NeuSrmService
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepo;

	/**
	 * @var RequestRepository
	 */
    protected $requestRepo;

	/**
	 * @var PartRepository
	 */
    protected $partRepo;

    protected $dataentryRepo;

    /**
     * @var BoxRepository
     */
    protected $boxRepo;

	/**
	 * RequestService constructor.
	 * @param ProjectRepository $projectRepo
	 * @param RequestRepository $requestRepo
	 * @param PartRepository $partRepo
     * @param BoxRepository $boxRepo
	 */
    public function __construct(ProjectRepository $projectRepo, RequestRepository $requestRepo, PartRepository $partRepo, DataEntryRepository $dataentryRepo, BoxRepository $boxRepository) {
        $this->projectRepo = $projectRepo;
        $this->requestRepo = $requestRepo;
        $this->partRepo = $partRepo;
        $this->dataentryRepo = $dataentryRepo;
        $this->boxRepo = $boxRepository;
    }


    /**
     * @param array $boxInfo
     * @return int
     * @throws NeuSrmException
     * @throws \Throwable
     */
    public function saveNewBoxForProject(array $boxInfo) : int {
        $dataToStore = [
            'box_name' => $boxInfo['box_name'],
            'box_location_code' => $boxInfo['box_location_code'],
            'box_type' => $boxInfo['box_type'],
            'box_status' => $boxInfo['box_status'],
            'rfid' => $boxInfo['rfid'],
            'project_id' => $boxInfo['project_id'],
            'company_id' => \Auth::user()->company_id,
            'created_by' => \Auth::id()
        ];

        try {
            $boxID = $this->dataentryRepo->createNewBox($dataToStore);

            return $boxID;

        }
        catch (NeuSrmException | \Throwable $exception) {
            throw $exception;
        }

    }

    /**
     * @param array $boxInfo
     * @return array
     * @throws NeuSrmException
     * @throws \Throwable
     */
    public function saveNewPartForProject(array $boxInfo) : array {
        $dataToStore = [
            'part_name' => $boxInfo['part_name'],
            'box_id' => $boxInfo['box_id'] ,
            'created_by' => \Auth::id(),
            'project_id' => $boxInfo['project_id']
        ];

        try {
            $partInfo = $this->dataentryRepo->createNewPart($dataToStore);

            return $partInfo;

        }
        catch (NeuSrmException | \Throwable $exception) {
            throw $exception;
        }

    }

    /**
     * @param Project $project
     * @return int
     * @throws NeuSrmException
     * @throws \Throwable
     */
    public function saveNewPartIndexForProject(Project $project, array $boxInfo) : int {
        $indexTypes = $project->indexes;
        $indexArr = [];
        foreach($indexTypes as $indexType){
            $dataToStore = [
                'box_id' => $boxInfo['box_id'],
                'part_index_value' => $boxInfo[$indexType->index_internal_name] ? $boxInfo[$indexType->index_internal_name] : '',
                'part_id' => $boxInfo['part_id'],
                'index_type_id' => $indexType->id
            ];
            try {
                $partindexID = $this->dataentryRepo->createNewPartIndex($dataToStore);
                if ($boxInfo[$indexType->index_internal_name]) {
                    $indexArr[$indexType->index_internal_name] = $boxInfo[$indexType->index_internal_name];
                }
            }
            catch (NeuSrmException | \Throwable $exception) {
                throw $exception;
            }
        }
        $this->dataentryRepo->logPartIndexCreation($boxInfo['part_id'], $indexArr);
        return $partindexID;
    }


    /**
     * @param string $orderBy
     * @return LengthAwarePaginator
     */
    public function listDataEntries(string $orderBy) :LengthAwarePaginator {
        $dataEntries = $this->dataentryRepo->listDataEntries($orderBy);
        return $dataEntries;
    }

    /**
     * @param string $orderBy
     * @return LengthAwarePaginator
     */
    public function projectBoxes(string $projectID, string $keyword, string $sortBy, string $order) : LengthAwarePaginator {
        $query = $this->boxRepo->searchQuery($projectID, $keyword);
        $query = $this->boxRepo->orderQuery($query, $sortBy, $order);
        $dataEntries = $this->boxRepo->boxSearch($query);
        return $dataEntries;
    }

    /**
     * @param string $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchDataEntries(string $keyword): LengthAwarePaginator {
        return $this->dataentryRepo->searchDataEntries($keyword);
    }

    public function getIndexesByProjectId(string $projectID) : array{
        $indexes = $this->dataentryRepo->getIndexesByProjectId($projectID);
        return $indexes->map(function(IndexType $index){
            return ['id' => $index->id, 'label' => $index->index_label];
        })->toArray();
    }

    /**
     * @param string $boxId
     * @return Project
     */
    public function getProjectByBoxId(string $boxId) : Project{
        return $this->dataentryRepo->getProjectByBoxId($boxId);
    }

    /**
     * @param string $boxId
     * @return Box
     */
    public function getBoxByBoxId(string $boxId) : LengthAwarePaginator {
        return $this->boxRepo->getBoxByBoxId($boxId);
    }
}
