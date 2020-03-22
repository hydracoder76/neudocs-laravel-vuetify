<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 14:52
 */

namespace NeubusSrm\Http\Controllers\api\v1\Indexing;

use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\CreateIndexesRequest;
use NeubusSrm\Http\Requests\DeleteIndexesRequest;
use NeubusSrm\Http\Requests\EditIndexesRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\IndexService;
use NeubusSrm\Services\PartService;
use NeubusSrm\Services\ProjectService;

/**
 * Class IndexesApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Indexing
 */
class IndexesApiController extends ApiController
{

	/**
	 * @var PartService
	 */
	private $partService;

	/**
	 * @var PartService
	 */
	private $indexService;

    /**
     * @var ProjectService
     */
	private $projectService;

    /**
     * IndexesApiController constructor.
     * @param PartService $partService
     * @param IndexService $indexService
     * @param ProjectService $projectService
     */
	public function __construct(PartService $partService, IndexService $indexService, ProjectService $projectService) {
		$this->partService = $partService;
		$this->indexService = $indexService;
		$this->projectService = $projectService;
	}

	//TODO: return a master list of projects, likely for company admins who need to see all projects and not
	// projects assign to like a regular user

	public function all() {
		$this->apiError('Not yet implemented', HttpConstants::HTTP_NOT_IMPLEMENTED);
	}

	/**
	 * @param string $projectId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function byProjectId(string $projectId) {
		try {
			$project = $this->projectService->getProjectByID($projectId);
			$indexes = $this->indexService->getIndexesByProject($project);
			return $this->apiSuccess('indexes retrieved', $indexes);
		}
		catch (NeuSrmException $exception) {
			return $this->apiErrorWithException($exception);
		}
		catch (\Throwable $exception) {
		    \Log::error($exception->getMessage());
			return $this->apiError('An internal error occurred, please try again later');
		}
	}

	/**
	 * @param CreateIndexesRequest $createIndexesRequest
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function addNewIndex(CreateIndexesRequest $createIndexesRequest) {
		try {
			$result = $this->indexService->saveNewIndexTypesForProject($createIndexesRequest->all());
			return $this->apiSuccess('indexing fields added',
				['index_id' => $result], HttpConstants::HTTP_CREATED);
		}
		catch (NeuSrmException $exception) {
			return $this->apiErrorWithException($exception);
		}
		catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
		}
	}

    /**
     * @param DeleteIndexesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function deleteIndex(DeleteIndexesRequest $request){
	    $this->indexService->deleteIndexType($request->input('id'));
        return $this->apiSuccess('indexing deleted', [], HttpConstants::HTTP_OK);
    }

    /**
     * @param EditIndexesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editIndex(EditIndexesRequest $request){
        $this->indexService->editIndexType($request->all());
        return $this->apiSuccess('indexes edited', [], HttpConstants::HTTP_OK);
    }
}
