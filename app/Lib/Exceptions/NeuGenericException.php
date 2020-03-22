<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 10:39
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuGenericException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuGenericException extends NeuSrmException
{
    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::NEUBUS_SRM_GENERAL_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_SERVER_ERROR;
    }


}