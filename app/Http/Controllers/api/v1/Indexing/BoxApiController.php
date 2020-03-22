<?php

namespace NeubusSrm\Http\Controllers\api\v1\Indexing;

use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\BoxLocationRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\BoxService;

/**
 * Class BoxApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Indexing
 */
class BoxApiController extends ApiController
{
    /**
     * @var BoxService
     */
    private $boxService;

    /**
     * BoxApiController constructor.
     * @param BoxService $boxService
     */
    public function __construct(BoxService $boxService) {
        $this->boxService = $boxService;
    }

    /**
     * @param BoxLocationRequest $boxLocationRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewLocation(BoxLocationRequest $boxLocationRequest): JsonResponse {
        $response = null;
        try {
            $arrRequest = $boxLocationRequest->all();
            $result = $this->boxService->createBoxLocation($arrRequest);

            $response = $this->apiSuccess('Box location updated', ['result' => $result],
                HttpConstants::HTTP_CREATED);
        } catch (\Throwable $exception) {
            if ($exception instanceof NeuSrmException) {
                $response = $this->apiErrorWithException($exception);
            } else {
                $response = $this->apiError($exception);
            }

        } finally {
            return $response;
        }
    }

}
