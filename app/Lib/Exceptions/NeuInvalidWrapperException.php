<?php
/**
 * User: mlawson
 * Date: 2018-11-30
 * Time: 12:07
 */

namespace NeubusSrm\Lib\Exceptions;


use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuInvalidWrapperException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuInvalidWrapperException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::NEU_WRAPPER_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_SERVER_ERROR;
	}

}