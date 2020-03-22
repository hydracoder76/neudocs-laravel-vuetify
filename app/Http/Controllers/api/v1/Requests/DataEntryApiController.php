<?php
/**
 * User: aho
 * Date: 2/4/19
 * Time: 12:27 PM
 */

namespace NeubusSrm\Http\Controllers\api\v1\Requests;

use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\GetPartsByBoxRequest;
use NeubusSrm\Http\Requests\ProjectBoxRequest;
use NeubusSrm\Http\Resources\DataEntryCollection;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use Illuminate\Http\Request;
use NeubusSrm\Services\DataEntryService;
use NeubusSrm\Http\Requests\RequestApiFormRequest;
use NeubusSrm\Http\Requests\CreateBoxesRequest;
use NeubusSrm\Http\Requests\CreatePartsRequest;
use NeubusSrm\Http\Requests\CreatePartIndexesRequest;
use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Requests\DataEntrySearchRequest;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Services\IndexService;
use NeubusSrm\Services\ProjectService;

class DataEntryApiController extends ApiController
{
    /**
     * @var DataEntryService
     */
    protected $dataEntryService;
    /**
     * @var RequestService
     */
    protected $requestService;
    /**
     * @var IndexService
     */
    protected $indexService;
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * RequestApiController constructor.
     * @param RequestService $requestService
     */
    public function __construct(DataEntryService $dataEntryService,RequestService $requestService, IndexService $indexService, ProjectService $projectService){
        $this->dataEntryService = $dataEntryService;
        $this->requestService = $requestService;
        $this->indexService = $indexService;
        $this->projectService = $projectService;
    }



    // TODO: generalize this to return the correct data contextually, so that the todo
    // table gets the same
    /**
     * @param string $projectID
     * @return JsonResponse
     */
    public function project(string $projectID){
        try {
            $requestLists = $this->requestService->getRequestsByProject($projectID);
            $requests = $this->requestService->requestsToList($requestLists);
            $resultsObject = ['results' => $requests, 'total' => $requestLists->total(),
                'currentPage' => $requestLists->currentPage()];
            return $this->apiSuccess('Request By Project Successful', ['results' => $resultsObject]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiError($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param CreateBoxesRequest $createBoxesRequest
     * @return JsonResponse
     */
    public function addNewBox(CreateBoxesRequest $createBoxesRequest) {
        try {
            $result = $this->dataEntryService->saveNewBoxForProject($createBoxesRequest->all());
            $box  = $this->dataEntryService->getBoxByBoxId($result);
            $data = $this->requestService->requestBoxesToDataEntry($box); //Purpose: to request Box model to DataEntry UI

            return $this->apiSuccess('box added',
                ['results' => $data, 'total' => $box['total']],
                 HttpConstants::HTTP_CREATED);
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
     * @param CreatePartsRequest $createPartsRequest
     * @return JsonResponse
     */
    public function addNewPart(CreatePartsRequest $createPartsRequest) : JsonResponse {
        $response = null;
        try {
            $result = $this->dataEntryService->saveNewPartForProject($createPartsRequest->all());
            $response = $this->apiSuccess('part added',
                ['id' => $result['id'], 'created_by' => $result['created_by'], 'created_at' => $result['created_at']],
                HttpConstants::HTTP_CREATED);
        }
        catch (NeuSrmException $exception) {
            $response = $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            $response = $this->apiError('An internal error occurred, please try again later');
        }
        finally {
            return $response;
        }
    }

    /**
     * @param Request $createPartIndexesRequest
     * @return JsonResponse
     */
    public function addNewPartIndex(Request $createPartIndexesRequest) {
        try {
            $arrRequest = $createPartIndexesRequest->all();
            $project = $this->dataEntryService->getProjectByBoxId($arrRequest['data']['box_id']);
            $result = $this->dataEntryService->saveNewPartIndexForProject($project, $arrRequest['data']);

            return $this->apiSuccess('part index added',
                ['id' => $result], HttpConstants::HTTP_CREATED);
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
     * @return JsonResponse
     */
    public function all():JsonResponse
    {
        try {
            $data = $this->dataEntryService->listDataEntries('created_at');
            return $this->apiSuccess('data entries retrieved', ['result' => $data]);
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
     * @param string $projectID
     * @return JsonResponse
     */
    public function projectBox(ProjectBoxRequest $request) : JsonResponse {
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $keyword = $request->input('keyword') ? $request->input('keyword') : '';
            $boxLists  = $this->dataEntryService->projectBoxes($request->input('projectId'), $keyword, $sortBy, $order);
            $data = $this->requestService->requestBoxesToDataEntry($boxLists);
            return $this->apiSuccess('boxes retrieved',  ['result' => $data, 'total' => $boxLists->total()]);
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
     * @param DataEntrySearchRequest $dataEntrySearchRequest
     * @return JsonResponse
     */
    public function search(DataEntrySearchRequest $dataEntrySearchRequest):JsonResponse{
        try {
            $request = $dataEntrySearchRequest->all();
            $results = $this->dataEntryService->searchDataEntries($request['keyword']);
            return $this->apiSuccess('Request Search Successful', ['results' => $results]);
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
     * @param GetPartsByBoxRequest $request
     * @return JsonResponse
     */
    public function boxPartIndex(GetPartsByBoxRequest $request) : JsonResponse {
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $keyword = $request->input('keyword') ? $request->input('keyword') : '';
            $partLists = $this->requestService->getPartsByBox($request->input('boxId'), $keyword, $sortBy, $request->input('order'));
            $data = $this->requestService->requestPartsToDataEntry($partLists['results']);
            return $this->apiSuccess('part indexes retrieved', ['result' => $data, 'total' => $partLists['total'], 'maxPartNum' => $partLists['maxPartNum']]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError($exception->getMessage());
        }
    }

    /**
     * @param string $projectID
     * @return JsonResponse
     */
    public function boxPartIndexSchemaOnly(string $projectID)
    {
        try {
            $project = $this->projectService->getProjectByID($projectID);
            $data = $this->indexService->getIndexesByProjectForDataEntryPartSchemaOnly($project);
            return $this->apiSuccess('part page schema retrieved', ['result' => $data]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }
}
