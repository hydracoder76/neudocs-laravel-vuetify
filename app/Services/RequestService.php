<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:30 PM
 */

namespace NeubusSrm\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\NeuUserInvalidCredentialsException;
use NeubusSrm\Lib\Exceptions\ProjectNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\BoxesCollection;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestPartsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestsCollection;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Org\MediaType;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Repositories\ProjectRepository;
use NeubusSrm\Repositories\RequestRepository;
use NeubusSrm\Repositories\PartRepository;
use NeubusSrm\Repositories\MediaTypeRepository;
use NeubusSrm\Repositories\FileUploadRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Lib\DataMappers\FormatterImpl\RequestFormatter;
use Carbon\Carbon;
use Crypt, Auth;


/**
 * Class RequestService
 * @package NeubusSrm\Services
 */
class RequestService extends NeuSrmService
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

    /**
     * @var FileUploadRepository
     */
    protected $fileUploadRepo;

    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * @var MediaTypeRepository
     */
    protected $mediaTypeRepository;

    /**
     * RequestService constructor.
     * @param ProjectRepository $projectRepo
     * @param RequestRepository $requestRepo
     * @param PartRepository $partRepo
     * @param Formatter $formatter
     * @param MediaTypeRepository $mediaTypeRepository
     */
    public function __construct(
        ProjectRepository $projectRepo,
        RequestRepository $requestRepo,
        PartRepository $partRepo,
        FileUploadRepository $fileUploadRepo,
        Formatter $formatter,
        MediaTypeRepository $mediaTypeRepository
    ) {
        $this->projectRepo = $projectRepo;
        $this->requestRepo = $requestRepo;
        $this->partRepo = $partRepo;
        $this->fileUploadRepo = $fileUploadRepo;
        $this->formatter = $formatter;
        $this->mediaTypeRepository = $mediaTypeRepository;
    }

    /**
     * @param string $name
     * @return \NeubusSrm\Models\Org\Project
     * @throws \Throwable
     */
    public function getProjectByName(string $name) : Project{
        $project = $this->projectRepo->getProjectByName($name);
        throw_if($project == null,
            ProjectNotFoundException::class, 'No project found for this name');
        return $project;
    }

    /**
     * @param string $id
     * @return Project
     * @throws ProjectNotFoundException
     */
    public function getProjectById(string $id) : Project{
        $project = $this->projectRepo->getProjectById($id);
        throw_if($project == null,
            ProjectNotFoundException::class, 'No project found for this id');
        return $project;
    }

    /**
     * Grabs all projects that a user has access to IF that user is an admin
     * @param bool $byCompany
     * @param bool $canUseAnyUser
     * @return ProjectsCollection
     * @throws \Throwable
     */
    public function getAllProjects(bool $byCompany = true, bool $canUseAnyUser = true) : ProjectsCollection {
        $projects = $this->projectRepo->getAllProjects($canUseAnyUser);
        return new ProjectsCollection($projects);
    }

    /**
     * @param PartsCollection $partList
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @param bool $review
     * @param bool $byProject
     * @return array
     */
    public function getRequestsByIndexes(array $indexes, string $projectID, string $sortBy, string $order, bool $review, bool $byProject): array {
        return $this->requestRepo->getRequestsByIndexes($indexes, $projectID, $sortBy, $order, $review, $byProject);
    }

    /**
     * @param string $projectID
     * @return RequestsCollection
     */
    public function getRequestsByProject(string $projectID): RequestsCollection {
        $sortBy = '';
        $sortDesc = false;
        $page = 1;
        $perPage = 25;

        return $this->requestRepo->getRequestsByProject($projectID, $sortBy, $sortDesc, $page, $perPage);
    }

    /**
     * @param $requestLists
     * @return array
     */
    public function requestsToList(RequestsCollection $requestLists, IndexTypesCollection $indexTypes, bool $review) : array{
        $part = [];
        foreach ($indexTypes as $indexType) {
            $part[$indexType->id] = 'n/a';
        }

        $requests = $requestLists->map(function (Request $requestList) use ($part, $review){
            $partMediaTypeId = [];
            $mediaTypes = $this->partRepo->getPartsByRequest($requestList->id);            
            $allMediaType = $this->mediaTypeRepository->getAllMediaTypes();
            $all = [];
            $check = [];
            foreach ($allMediaType as $item){
                $all[] = $item['type'];
            }
            foreach ($mediaTypes as $item){
                $subMediaType = $item->mediaTypes;
                foreach ($subMediaType as $subItem){
                    $check[] = $subItem['type'];
                }
            }

            $diff = array_diff($all, $check);
            $checkedMediatype = 0;
            foreach ($allMediaType as $key => $value) {
                if(in_array($value['type'], $diff)){
                    $partMediaTypeId[] = ['type' => $value['type'], 'check' =>  false ];
                }else{
                    $partMediaTypeId[] = ['type' => $value['type'], 'check' =>  true ];
                    $checkedMediatype++;
                }

            }
            $partMediaTypeId = $checkedMediatype ? $partMediaTypeId : [];
            $status = $this->getStatusFromRequest($requestList);
            $fulfilled = ($requestList->fulfilled_on) ? $requestList->fulfilled_on->format('Y-m-d g:i A') : null;
            return [
                'request_number' => $requestList->request_name,
                'request_indexes' => $this->getIndexesFromParts($part, $requestList),
                'status' => $status,
                'requested_on' => $requestList->created_at->format('Y-m-d g:i A'),
                'fulfilled_on' => $fulfilled,
                'request_id' => $requestList->id,
                'download' => $requestList->is_reviewed || $review ? 'request' : '',
                'external_comment' => $requestList->external_comment,
                'media_type_id' => $partMediaTypeId,
            ];
        })->toArray();
        return $requests;
    }

    /**
     * @param IndexTypesCollection $indexTypes
     * @param Request $requestList
     * @return string
     */
    public function getIndexesFromParts(array $partTypes, Request $requestList) : string{
        $part = $partTypes;
        //TODO: find way to represent part/indexes without loop inside loop
        $parts = $requestList->parts->map(function(Part $partList) use ($part){
            $indexes = $partList->indexes;
            foreach ($indexes as $index) {
                $part[$index->index_type_id] = $index->part_index_value;
            }
            return implode(', ', $part);
        })->toArray();
        $partString = implode('; ', $parts);
        return $partString;
    }

    /**
     * @param Request $requestList
     * @return string
     */
    public function getStatusFromRequest(Request $requestList) : string{
        if (!$requestList->is_reviewed) {
            if ($requestList->is_fulfilled) {
                $status = 'In Review';
            } else {
                if ($requestList->is_in_process) {
                    $status = 'In Progress';
                } else {
                    $status = 'New';
                }
            }
        }
        else{
            $status = 'Fulfilled';
        }
        return $status;
    }

    /**
     * @param array $indexes
     * @param string $projectID
     * @param string $sortBy
     * @param string $order
     * @param int $page
     * @return array
     */
    public function getPartsByIndexes(array $indexes, string $projectID, string $sortBy = '', string $order = 'asc', int $page = 1): array {
        return $this->partRepo->getPartsByIndexes($indexes, $projectID, $sortBy, $order, $page);
    }

    // TODO: iterable is too generic for this use case

    /**
     * @param PartsCollection $partLists
     * @return array
     */
    public function requestPartsToList(PartsCollection $partLists, IndexTypesCollection $indexTypes) : array {
        $parts = [];
        if (count($partLists) <= 0) {
            return $parts;
        }
        $parts = $this->formatter->format($partLists, Formatter::MODE_PARTS, $indexTypes);
        return $parts;
    }

    /**
     * @param  iterable  $partIDs
     * @param  string  $projectID
     * @param  string  $externalComment
     * @return string
     * @throws ProjectNotFoundException
     */
    public function createNewRequest(iterable $partIDs, string $projectID, ?string $externalComment) : string {
        $dateStr = Carbon::now()->format('ymd');
        $projectName = $this->getProjectById($projectID)->project_name;
        $num = str_pad($this->requestRepo->getNumRequestForDay($projectID), 4, '0', STR_PAD_LEFT);
        $name = $projectName . $dateStr . $num;
        $this->requestRepo->createNewRequest($partIDs, $projectID, $name, $externalComment ?? '');
        return $name;
    }

    /**
     * @param string $projectID
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function listRequests(string $projectID): array {
        $query = $this->requestRepo->todoSearchQueryBegin($projectID);
        $query = $this->requestRepo->todoSearchQuery($query, '', 'todo');
        $query = $this->requestRepo->todoOrderQuery($query, 'requested_at', 'ASC', 'todo');
        $requestParts = $this->requestRepo->todoSearch($query);
        return $this->convertRequestPage($requestParts, Formatter::MODE_TODO);
    }

    /**
     * @param string $project
     * @param string $orderBy
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function listCompletedRequests(string $project, string $orderBy) : array {
        $requestParts = $this->requestRepo->listCompletedRequests($project, $orderBy);
        return $this->convertRequestPage($requestParts, Formatter::MODE_COMPLETE);
    }

    /**
     * @param string $project
     * @param string $filterBy
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function filterCompletedRequests(string $project, string $filterBy, string $sortBy, string $order): array {
        $requestParts = $this->requestRepo->filterCompletedRequests($project, $filterBy, $sortBy, $order);
        return $this->convertCompleted($requestParts['results'], Formatter::MODE_COMPLETE, $requestParts['length']);
    }

    /**
     * @param RequestPartsCollection $requestParts
     * @param int $todo
     * @param int $total
     * @return array
     */
    public function convertCompleted(RequestPartsCollection $requestParts, int $todo, int $total): array {
        $requestPartArray = $this->formatter->format($requestParts, $todo);
        return ['results' => $requestPartArray, 'length' => $total];
    }

    /**
     * @param LengthAwarePaginator $requestParts
     * @param int $todo
     * @return array
     */
    public function convertRequestPage(LengthAwarePaginator $requestParts, int $todo): array {
        $requestPartArray = $this->formatter->format($requestParts->getCollection(), $todo);
        return ['results' => $requestPartArray, 'length' => $requestParts->total()];
    }

    /**
     * @param array $parts
     * @param string $action
     * @return array
     */
    public function lockRequests(array $parts, string $action): array {
        $partDatas = $this->requestRepo->lockRequests($parts);
        if ($action == 'upload') {
            return $partDatas->map(function (RequestPart $requestPart) {
                return collect(['part_id' => $requestPart->part_id_ref, 'request_id' => $requestPart->request_id_ref]);
            })->toArray();
        } elseif ($action == 'scan') {
            return $partDatas->map(function (RequestPart $requestPart) {
                $part = $requestPart->part;
                $request = $requestPart->request;
                return collect([
                    'value' => $requestPart->part_id_ref,
                    'text' => $part->box->box_name . '-' .
                        $part->part_name,
                    'disabled' => false,
                    'box' => $part->box->box_name,
                    'project_id' => $part->project_id,
                    'request' => $request->request_name,
                    'part_name' => $part->part_name,
                    'request_id' => $requestPart->request_id_ref,
                    'part_id' => $requestPart->part_id_ref
                ]);
            })->toArray();
        }
    }

    /**
     * @param array $parts\
     */
    public function unlockRequests(array $parts, array $dataAddChecked) : void {
        $this->requestRepo->unlockRequests($parts,$dataAddChecked);
    }

    /**
     * @param int $boxID
     * @param string $keyword
     * @param string $sortBy
     * @param string $order
     * @return array
     */
    public function getPartsByBox(int $boxID, string $keyword, string $sortBy, string $order): array {
        return $this->partRepo->getPartsByBox($boxID, $keyword, $sortBy, $order);
    }

    /**
     * @param iterable $boxLists
     * @return SupportCollection
     */
    public function requestBoxesToDataEntry(LengthAwarePaginator $boxLists) : SupportCollection {
        $boxes = [];
        if (count($boxLists) <= 0) {
            return $boxes;
        }

        $boxes = $boxLists->map(function ($boxList) {
            return [
                'project_id' => $boxList->project_id,
                'box_name' => $boxList->box_name,
                'box_type' => $boxList->box_type,
                'box_location_code' => $boxList->box_location_code,
                'rfid' => $boxList->rfid,
                'created_at' => Carbon::parse($boxList->created_at)->format('Y-m-d g:i A'),
                'created_by_name' => $boxList->createdBy !== null ? $boxList->createdBy->name : null,
                'updated_by_name' => ( $boxList->updated_by !== null ) ?  $boxList->updatedBy->name : null,
                'deleted_at' => ( $boxList->deleted_at !== null ) ?  Carbon::parse($boxList->deleted_at)->format('Y-m-d H:i') : null,
                'part_count' => $boxList->parts_count,
                'company_id' => $boxList->company_id,
                'has_pending_requests' => $boxList->has_pending_requests,
                'box_status' => $boxList->box_status,
                'id' => $boxList->id,
                'is_deleted' => $boxList->is_deleted
            ];
        });
        return $boxes;
    }

    /**
     * TODO: iterable is too generic, use a more specific param type
     * TODO: use proper return type
     * @param iterable $partLists
     * @return array
     */

    public function requestPartsToDataEntry(iterable $partLists) {
        $parts = [];
        if (count($partLists) <= 0) {
            return $parts;
        }
        $indexTypes = $partLists[0]->project->indexes;
        $parts = $partLists->map(function ($partList) use ($indexTypes) {
            $indexes = $partList->indexes;
            $part = [
                'part_name' => $partList->part_name,
                'created_at' => $partList->created_at->format('Y-m-d g:i A'),
                'created_by' => $partList->createdBy->name
            ];
            foreach ($indexTypes as $indexType) {
                $part[$indexType->index_internal_name] = '';
            }
            foreach ($indexes as $index) {
                $part[$index->indexType->index_internal_name] = $index->part_index_value;
            }
            return $part;
        });
        return $parts;
    }

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    public function getAutoPartValues(string $key, string $value) : array{
        $indexTypeId = $this->partRepo->getIndexTypeByName($key)->id;
        $partIndexes = $this->partRepo->getPartValuesAuto($indexTypeId, $value);
        if ($partIndexes == null) {
            return [];
        }
        $partIndexArr = $partIndexes->pluck('part_index_value')->toArray();
        return $partIndexArr;
    }

    /**
     * @param string $projectId
     * @param string $keyword
     * @param string $sortBy
     * @param string $order
     * @return array
     * @throws \Throwable
     */
    public function todoSearch(string $projectId, string $keyword, string $sortBy, string $order) : array {
        $query = $this->requestRepo->todoSearchQueryBegin($projectId);
        $query = $this->requestRepo->todoSearchQuery($query, $keyword, 'todo');
        $query = $this->requestRepo->todoOrderQuery($query, $sortBy, $order, 'todo');
        $requestParts = $this->requestRepo->todoSearch($query);
        return $this->convertRequestPage($requestParts, Formatter::MODE_TODO);
    }

    /**
     * @param string $requestId
     * @param string $comment
     * @param bool $accept
     */
    public function review(string $requestId, string $comment, bool $accept) : void {
        $this->requestRepo->review($requestId, $comment, $accept);
    }

    /**
     * @param string $fileId
     */
    public function updateRequestAfterFileDeletion(string $fileId) : void {
        $fileEntity = $this->fileUploadRepo->getById($fileId);
        $updateData = [
            'is_fulfilled' => false,
            'is_reviewed' => false,
            'fulfilled_by' => null,
            'fulfilled_on' => null
        ];
        $this->requestRepo->requestReopened($fileEntity, $updateData);
    }

}
