<?php

namespace NeubusSrm\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use NeubusSrm\Lib\Exceptions\NeuGenericException;
use NeubusSrm\Lib\Exceptions\NeuSrmException;

/**
 * Class Handler
 * @package NeubusSrm\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception) {
        if ($exception instanceof NeuSrmException) {
            \Log::error("NeuSRM exception: {$exception->getInternalCode()} : {$exception->getMessage()}");
            parent::report($exception);
        }
        else {
            \Log::error("Internal exception: {$exception->getMessage()}");
            parent::report(new NeuGenericException('An internal error occurred please try again later'));
        }

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {
        return parent::render($request, $exception);
    }
}
