<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:42 PM
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class ProjectNameException
 * @package NeubusSrm\Lib\Exceptions
 */
class ProjectNameException extends NeuSrmException
{
    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::PROJECT_NAME_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_NOT_FOUND;
    }


}