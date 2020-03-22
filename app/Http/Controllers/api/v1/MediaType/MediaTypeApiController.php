<?php

namespace NeubusSrm\Http\Controllers\api\v1\MediaType;


use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\MediaTypeService;
use Illuminate\Http\JsonResponse;
/**
 * Class MediaTypeApiController
 * @package NeubusSrm\Http\Controllers\api\v1\MediaType
 */
class MediaTypeApiController extends ApiController
{

	/**
	 * @var MediaTypeService
	 */
    private $mediaTypeService;

	/**
	 * MediaTypeApiController constructor.
	 * @param MediaTypeService $mediaTypeService
	 */
    public function __construct(MediaTypeService $mediaTypeService)
    {
        $this->mediaTypeService = $mediaTypeService;
    }

    /**
     * @param null $format
     * @return \Illuminate\Http\JsonResponse
     */
    public function all($format = null) : JsonResponse
    {
        try {            
            $data = $this->mediaTypeService->allMediaType();            
            return $this->apiSuccess('media type retrieved', [$data]);
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
