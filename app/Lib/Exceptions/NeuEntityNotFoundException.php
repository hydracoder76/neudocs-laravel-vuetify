<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 13:49
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuEntityNotFoundException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuEntityNotFoundException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::ENTITY_NOT_FOUND_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_NOT_FOUND;
	}


}