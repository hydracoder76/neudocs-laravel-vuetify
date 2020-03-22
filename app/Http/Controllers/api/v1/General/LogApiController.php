<?php

namespace NeubusSrm\Http\Controllers\api\v1\General;


use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\LogRequest;
use NeubusSrm\Http\Requests\RequestAutoRequest;
use NeubusSrm\Http\Requests\RequestSearchRequest;
use NeubusSrm\Http\Requests\ReviewRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use Illuminate\Http\Request;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Services\LogService;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Http\Requests\RequestApiFormRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class LogApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Auth
 */
class LogApiController extends ApiController
{
    /**
     * @var LogService
     */
    protected $logService;

    /**
     * LogApiController constructor.
     * @param LogService $logService
     */
    public function __construct(LogService $logService){
        $this->logService = $logService;
    }

    /**
     * @param LogRequest $request
     * @return JsonResponse
     */
    public function search(LogRequest $request) : JsonResponse {
        $response = null;
        try {
            $dateFrom = $request->input('dateFrom') ? $request->input('dateFrom') : '';
            $dateTo = $request->input('dateTo') ? $request->input('dateTo') : '';
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $logs = $this->logService->logSearch($dateFrom, $dateTo, $sortBy, $order);
            $response = $this->apiSuccess('Request Filter Successful', ['results' => $logs]);
        }
        catch (NeuSrmException $exception){
            $response = $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            \Log::error($exception->getMessage());
            $response = $this->apiError('An internal error occurred, please try again later');
        }
        finally{
            return $response;
        }
    }
}
