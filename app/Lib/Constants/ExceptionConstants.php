<?php
/**
 * User: mlawson
 * Date: 11/8/18
 * Time: 1:34 PM
 */

namespace NeubusSrm\Lib\Constants;

/**
 * Interface ExceptionConstants
 * This should be updated any time a new exception type is created!!
 * @package NeubusSrm\Lib\Constants
 */
interface ExceptionConstants
{
    // model/entity exceptions

    const ENTITY_NOT_FOUND_EXCEPTION = 1000;
    const ENTITY_INVALID_EXCEPTION = 1001;
    // can be thrown in cases where the entity must absolutely not be empty
    const ENTITY_EMPTY_EXCEPTION = 1002;
    const ENTITY_BAD_PARAMS_EXCEPTION = 1003;
    const ENTITY_FILE_EXCEPTION = 1004;
    const ENTITY_EXISTS_EXCEPTION = 1005;

    // project exceptions

    const PROJECT_EXISTS_EXCEPTION = 2000;
    const PROJECT_PENDING_EXCEPTION = 2001;
    const PROJECT_GENERAL_EXCEPTION = 2002;
    const PROJECT_NOT_FOUND_EXCEPTION = 2003;
    const PROJECT_NAME_EXCEPTION = 2004;

    // user exceptions

    const USER_NOT_FOUND_EXCEPTION = 3000;
    const USER_INVALID_ACCESS_EXCEPTION = 3001;
    const USER_INFO_EXISTS_EXCEPTION = 3002;
    const USER_INVALID_ACTION_EXCEPTION = 3003;

    // company exceptions

    const COMPANY_NOT_FOUND_EXCEPTION = 4000;
    const COMPANY_INVALID_ACCESS_EXCEPTION = 4001;

    // request exceptions

    const REQUEST_EXISTS_EXCEPTION = 5000;
    const REQUEST_INACCESSIBLE_EXCEPTION = 5001;
    const REQUEST_INVALID_EXCEPTION = 5002;

    // fulfillment exception

    const INVALID_ACTIVITY_EXCEPTION = 6000;

    // general exceptions

    const NEUBUS_SRM_GENERAL_EXCEPTION = 9000;
    const NEU_WRAPPER_EXCEPTION = 9001;
    const NEU_DATA_EXCEPTION = 9002;
    const MENU_BUILDER_EXCEPTION = 9003;
    const NEU_CLASS_NOT_FOUND_EXCEPTION = 9004;


}