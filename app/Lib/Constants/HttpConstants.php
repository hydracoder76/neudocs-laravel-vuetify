<?php
/**
 * User: mlawson
 * Date: 11/8/18
 * Time: 1:33 PM
 */

namespace NeubusSrm\Lib\Constants;

/**
 * Interface HttpConstants
 * @package NeubusSrm\Lib\Constants
 */
interface HttpConstants
{

    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INVALID_REQUEST = 422;
    const HTTP_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501; //technically this is for HTTP methods, but it'll work here too
}