<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:48 PM
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuUserInvalidCredentialsException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuUserInvalidCredentialsException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::USER_INVALID_ACCESS_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_FORBIDDEN;
	}


}