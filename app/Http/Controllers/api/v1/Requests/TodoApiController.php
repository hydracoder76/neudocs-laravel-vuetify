<?php
/**
 * User: mlawson
 * Date: 2019-01-25
 * Time: 12:37
 */

namespace NeubusSrm\Http\Controllers\api\v1\Requests;


use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Requests\SearchOrderRequest;
use NeubusSrm\Lib\Exceptions\NeuGenericException;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Org\Setting;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\CompletedRequest;
use NeubusSrm\Http\Requests\NavRequest;
use NeubusSrm\Http\Requests\UnlockRequest;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\PartService;
use NeubusSrm\Services\ProjectService;
use NeubusSrm\Services\RequestService;
use Auth;
use Cache;
use Log;
use NeubusSrm\Services\SettingService;
use NeubusSrm\Lib\Facades\Neulog;
use NeubusSrm\Lib\Logging\Neulogger;

/**
 * Class TodoApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Requests
 */
class TodoApiController extends ApiController
{

    /**
     * @var RequestService
     */
    protected $requestService;

    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * @var PartService
     */
    protected $partService;

    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * TodoApiController constructor.
     * @param RequestService $requestService
     * @param SettingService $settingService
     */
    public function __construct(RequestService $requestService, SettingService $settingService, PartService $partService, ProjectService $projectService){
        $this->requestService = $requestService;
        $this->settingService = $settingService;
        $this->partService = $partService;
        $this->projectService = $projectService;
    }

    /**
     * @return string
     */
    public function selectForFulfillment() {
        return 'todo';
    }

    /**
     * @param NavRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function navigate(NavRequest $request){
        try {

            $parts = $request->input('parts');
            $partData = $this->requestService->lockRequests($parts, $request->input('action'));
            $user = Auth::user();
            $partKey = $user->id . '_' . time() . '_todo';
            $partKeyStore = 'neu-' . $partKey;
            Cache::put($partKeyStore, json_encode($partData), 60);
            return $this->apiSuccess('Parts Locked', ['key' => $partKey, 'parts' => $partData]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            Neulog::write($exception->getMessage(), collect(), NeuLogger::LEVEL_ERROR);
            return $this->apiError('Internal error, please notify your administrator');
        }
    }

    /**
     * @param string $projectID
     * @return \Illuminate\Http\JsonResponse
     * @throws NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function requestList(string $projectID){
        try {
            $results = $this->requestService->listRequests($projectID);
        }
        catch(\Exception $e){
            return $this->apiError($e->getMessage());
        }
        $yellow = Setting::YELLOW_DEFAULT;
        $red = Setting::RED_DEFAULT;
        $message = 'Completed List Successful';
        try {
            $yellow = $this->settingService->getSettingByKey('priority_yellow', $projectID);
            $red = $this->settingService->getSettingByKey('priority_red', $projectID);
        }catch (NeuEntityNotFoundException $e){
            $message = 'Priority Settings Not Set';
        } catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            return $this->apiError('Internal error, please notify your administrator');
        }
        $results['yellow'] = $yellow;
        $results['red'] = $red;
        return $this->apiSuccess($message, $results);
    }

    /**
     * @param CompletedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completedList(CompletedRequest $request) : JsonResponse {
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $results = $this->requestService->listCompletedRequests($request->input('project'), $sortBy);
            return $this->apiSuccess('Completed List Successful', $results);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            Neulog::write($exception->getMessage(), collect(), NeuLogger::LEVEL_ERROR);            
            return $this->apiError('Internal error, please notify your administrator');
        }
    }

    /**
     * @param CompletedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completedFilter(CompletedRequest $request){
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $filterBy = $request->input('filter_by') ? $request->input('filter_by') : '';
            $results = $this->requestService->filterCompletedRequests($request->input('project'), $filterBy, $sortBy, $request->input('order'));
            return $this->apiSuccess('Completed Filter Successful', $results);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->apiError('Internal error, please notify your administrator');
        }
    }

    /**
     * @param UnlockRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlock(UnlockRequest $request){
        try {	
            $this->requestService->unlockRequests($request->input('parts'), $request['dataAddChecked']);
            return $this->apiSuccess('Unlock Successful');
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->apiError('Internal error, please notify your administrator');
        }
    }

    /**
     * @param SearchOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function todoSearch(SearchOrderRequest $request) : JsonResponse {
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $keyWord = $request->input('keyword') ? $request->input('keyword') : '';
            $projectId = $request->input('projectId');
            $requests = $this->requestService->todoSearch($projectId, $keyWord, $sortBy, $order);
            return $this->apiSuccess('Requsts retrieved', $requests);
        }
        catch (\Exception $e){
            return $this->apiError($e->getMessage());
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
     * @param NavRequest $request
     * @return JsonResponse
     */
    public function fulfillParts(NavRequest $request) : JsonResponse {
        $parts = $request->input('parts');
        foreach($parts as $part){
            $this->partService->fulfillPart($part['part_id'], $request['dataAddChecked']);
            $this->partService->requestStatus($part['part_id']);
        }
        return $this->apiSuccess('Parts Fulfilled');
    }


    /**
     * @param NavRequest $request
     * @return JsonResponse
     */
    public function getRequestPartMediaType(\Illuminate\Http\Request $request) : JsonResponse {
        $mediaTypes = [];
        $mediaTypesEmpty = [];
        foreach ($request['parts'] as $key => $value){
            if(isset($value['part_id'])){
                $part = $this->partService->partMediaType($value['part_id']);
                $projectMediaTypes = $this->projectService->projectMediaTypes($part->project_id);                
                $mediaTypes[$value['part_id']] = $projectMediaTypes;
                $mediaTypesEmpty[$value['part_id']] = [];
            }
        }
        
        return $this->apiSuccess('Media Types', ['part_media_types' => $mediaTypes, 'media_type_empty' => $mediaTypesEmpty]);
    }
}
