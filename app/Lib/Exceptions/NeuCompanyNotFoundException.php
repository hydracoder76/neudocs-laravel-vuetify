<?php
/**
 * User: mlawson
 * Date: 2018-12-31
 * Time: 08:41
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuCompanyNotFoundException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuCompanyNotFoundException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::COMPANY_NOT_FOUND_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_NOT_FOUND;
	}


}