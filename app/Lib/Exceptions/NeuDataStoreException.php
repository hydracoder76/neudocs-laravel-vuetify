<?php
/**
 * User: mlawson
 * Date: 2018-12-11
 * Time: 12:12
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuDataStoreException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuDataStoreException extends NeuSrmException
{
	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::NEU_DATA_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_SERVER_ERROR;
	}


}