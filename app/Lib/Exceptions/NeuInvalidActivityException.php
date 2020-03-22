<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-13
 * Time: 15:56
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuInvalidActivityException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuInvalidActivityException extends NeuSrmException
{
    /**
     * @return int
     */
    public function getInternalCode(): int {
        return ExceptionConstants::INVALID_ACTIVITY_EXCEPTION;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return HttpConstants::HTTP_BAD_REQUEST;
    }
}
