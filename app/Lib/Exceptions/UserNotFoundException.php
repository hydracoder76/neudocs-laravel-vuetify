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
 * Class UserNotFoundException
 * @package NeubusSrm\Lib\Exceptions
 */
class UserNotFoundException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::USER_NOT_FOUND_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_NOT_FOUND;
	}


}