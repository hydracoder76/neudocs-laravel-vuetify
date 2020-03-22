<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 13:05
 */

namespace NeubusSrm\Lib\Exceptions;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use NeubusSrm\Lib\Constants\HttpConstants;

/**
 * Class NeuMenuBuilderException
 * @package NeubusSrm\Lib\Exceptions
 */
class NeuMenuBuilderException extends NeuSrmException
{

	/**
	 * @return int
	 */
	public function getInternalCode(): int {
		return ExceptionConstants::MENU_BUILDER_EXCEPTION;
	}

	/**
	 * @return int
	 */
	public function getHttpCode(): int {
		return HttpConstants::HTTP_SERVER_ERROR;
	}


}