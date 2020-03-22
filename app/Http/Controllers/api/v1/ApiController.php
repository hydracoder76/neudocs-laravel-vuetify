<?php
/**
 * User: mlawson
 * Date: 11/1/18
 * Time: 12:09 PM
 */

namespace NeubusSrm\Http\Controllers\api\v1;


use NeubusSrm\Http\Controllers\Controller;
use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Exceptions\NeuSrmException;

/**
 * Class ApiController
 * @package NeubusSrm\Http\Controllers\api\v1
 */
abstract class ApiController extends Controller {

    // TODO: switch out response for constant when available

    /**
     * @param string $message
     * @param array $payload
     * @param int $httpResponseCode
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function apiSuccess(string $message, array $payload = [], int $httpResponseCode = 200) {
        return $this->apiResponse($message, $payload, $httpResponseCode);
    }

	/**
	 * @param NeuSrmException $exception
	 * @return \Illuminate\Http\JsonResponse
	 */
    final protected function apiErrorWithException(NeuSrmException $exception) {
        return $this->apiError($exception->getMessage()
            , $exception->getHttpCode(), $exception->getInternalCode());
    }

    /**
     * TODO: Accept generic throwable objects
     * @param string $message
     * @param int $httpResponseCode
     * @param int $internalCode
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function apiError(string $message, int $httpResponseCode = 500,
        int $internalCode = ExceptionConstants::NEUBUS_SRM_GENERAL_EXCEPTION) {
        return $this->apiResponse($message, ['error_code' => $internalCode], $httpResponseCode);
    }

    /**
     * @param string $message
     * @param array $payload
     * @param int $httpResponseCode
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function apiResponse(string $message, array $payload, int $httpResponseCode) {
        return response()->json(['message' => $message, 'data' => $payload], $httpResponseCode);
    }

}