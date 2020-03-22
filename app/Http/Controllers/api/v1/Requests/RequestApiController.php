<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:27 PM
 */

namespace NeubusSrm\Http\Controllers\api\v1\Requests;


use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\RequestAutoRequest;
use NeubusSrm\Http\Requests\RequestSearchRequest;
use NeubusSrm\Http\Requests\ReviewRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use Illuminate\Http\Request;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Http\Requests\RequestApiFormRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Auth
 */
class RequestApiController extends ApiController
{
    /**
     * @var RequestService
     */
    protected $requestService;

    /**
     * RequestApiController constructor.
     * @param RequestService $requestService
     */
    public function __construct(RequestService $requestService){
        $this->requestService = $requestService;
    }

    /**
     * @param RequestSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(RequestSearchRequest $request) : JsonResponse {
        try {
            $indexInput = $request->input('indexes');
            $indexes = ($indexInput == null) ? [] : json_decode($indexInput, true);
            $projectID = $request->input('projectID');
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $review = $request->input('review') == 'true' ? true : false;
            $byProject = false;
            if (count($indexes) == 0) {
                $byProject = true;
            }
            $indexResult = [];
            $requestLists = $this->requestService->getRequestsByIndexes($indexes, $projectID, $sortBy, $order, $review, $byProject);
            $indexLists = $this->requestService->getProjectById($projectID)->indexes;
            $requests = $this->requestService->requestsToList($requestLists['requests'], $indexLists, $review);
            $total = $requestLists['total'];
            if ($request->input('getIndexes')) {
                $indexResult = $indexLists->reduce(function($indexResult, IndexType $index){
                    $indexResult[$index->index_internal_name] = ['label' => $index->index_label];
                    return $indexResult;
                });
            }
            return $this->apiSuccess('Request Filter Successful', ['results' => ['requestResults' => $requests,
                'total' => $total, 'indexes' => $indexResult]]);
        }
        catch (NeuSrmException $exception){
            return $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

    /**
     * @param RequestSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(RequestSearchRequest $request){
        try {
            $indexInput = $request->input('indexes');
            $indexes = ($indexInput == null) ? [] : json_decode($indexInput, true);
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $projectID = $request->input('projectID');
            $page = $request->input('page');
            $partLists = $this->requestService->getPartsByIndexes($indexes, $projectID, $sortBy, $order, $page);
            $indexes = $this->requestService->getProjectById($projectID)->indexes;
            $parts = $this->requestService->requestPartsToList($partLists['parts'], $indexes);
            return $this->apiSuccess('Request Search Successful', ['results' => $parts, 'total' => $partLists['total']]);
        }
        catch (NeuSrmException | \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

    /**
     * @param RequestApiFormRequest $requestApiFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function newRequest(RequestApiFormRequest $requestApiFormRequest){
        try {
            $partIDs = $requestApiFormRequest->input('partIDs');
            $projectID = $requestApiFormRequest->input('projectID');
            // externalComment should be defaulted to '' as it comes in anyway, but this is to ensure it
            // given future changes that may come in
            $externalComment = $requestApiFormRequest->input('externalComment', '');
            // added a third param as the comment doesn't fit with the rest of the data
            $requestName = $this->requestService->createNewRequest($partIDs, $projectID, $externalComment);
            return $this->apiSuccess('New Request Successful', ['message' => 'New Request ' .
                $requestName . ' Successful'], HttpConstants::HTTP_CREATED);
        }
        catch (NeuSrmException | \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

    /**
     * @param RequestAutoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoPartSearch(RequestAutoRequest $request){
        try {
            $partValues = $this->requestService->getAutoPartValues($request->input('key'), $request->input('value'));
            return $this->apiSuccess('Auto Part Search Completed', ['results' => $partValues]);
        }
        catch (NeuSrmException $exception){
            return $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

    /**
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function review(ReviewRequest $request) : JsonResponse {
        try {
            $comment = $request->input('comment') ? $request->input('comment') : '';
            $this->requestService->review($request->input('requestId'), $comment, $request->input('accept'));
            return $this->apiSuccess('Request review finished');
        }
        catch (NeuSrmException $exception){
            return $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

}
