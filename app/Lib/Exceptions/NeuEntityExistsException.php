<?php

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuEntityExistsException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuEntityExistsException extends NeuSrmException
{
    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::ENTITY_EXISTS_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_BAD_REQUEST;
    }


}