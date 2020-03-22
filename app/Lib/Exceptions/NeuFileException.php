<?php

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuFileException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuFileException extends NeuSrmException
{
    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::ENTITY_FILE_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_SERVER_ERROR;
    }


}