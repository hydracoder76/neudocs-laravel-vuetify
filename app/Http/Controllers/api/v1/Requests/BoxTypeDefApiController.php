<?php

/**
 * User: aho
 * Date: 2/4/19
 * Time: 12:27 PM
 */

namespace NeubusSrm\Http\Controllers\api\v1\Requests;

use Illuminate\Http\JsonResponse;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\BoxTypeDefService;

/**
 * Class BoxTypeDefApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Requests
 */

class BoxTypeDefApiController extends ApiController
{
    /**
     * @var BoxTypeDefService
     */
    private $boxtypedefService;

    /**
     * @var Formatter
     */
    private $formatter;

    public function __construct(BoxTypeDefService $boxtypedefService) {
        $this->boxtypedefService = $boxtypedefService;
    }

    /**
     * @param $format
     * @return JsonResponse
     */
    public function all():JsonResponse
    {
        try {
            $data = $this->boxtypedefService->getAllBoxtypedefs();
            return $this->apiSuccess('Box type defs retrieved', $data);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError($exception->getMessage());
        }
    }


}
