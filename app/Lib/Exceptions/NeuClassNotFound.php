<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 10:09
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;
use Throwable;

/**
 * Class NeuClassNotFound
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuClassNotFound extends NeuSrmException
{
    /**
     * NeuClassNotFound constructor.
     * @param string $message
     */
    public function __construct(string $message = "") {

        parent::__construct('Requested class: ' . $message . ' does not exist.');
    }

    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::NEU_CLASS_NOT_FOUND_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_SERVER_ERROR;
    }


}