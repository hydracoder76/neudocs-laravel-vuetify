<?php
/**
 * User: mlawson
 * Date: 11/8/18
 * Time: 1:42 PM
 */

namespace Tests\Unit\Lib\Constants;

use NeubusSrm\Lib\Constants\ExceptionConstants;
use Tests\TestCase;

/**
 * Class ExceptionConstantsTest
 * @package Tests\Unit\Lib\Constants
 */
class ExceptionConstantsTest extends TestCase
{

    public function testExceptionConstants() {

        self::assertEquals( 1000, ExceptionConstants::ENTITY_NOT_FOUND_EXCEPTION);
        self::assertEquals( 1001, ExceptionConstants::ENTITY_INVALID_EXCEPTION);
        self::assertEquals( 1002, ExceptionConstants::ENTITY_EMPTY_EXCEPTION);
        self::assertEquals( 1003, ExceptionConstants::ENTITY_BAD_PARAMS_EXCEPTION);
        self::assertEquals( 2000, ExceptionConstants::PROJECT_EXISTS_EXCEPTION);
        self::assertEquals( 2001, ExceptionConstants::PROJECT_PENDING_EXCEPTION);
        self::assertEquals( 2002, ExceptionConstants::PROJECT_GENERAL_EXCEPTION);
        self::assertEquals( 3000, ExceptionConstants::USER_NOT_FOUND_EXCEPTION);
        self::assertEquals( 3001, ExceptionConstants::USER_INVALID_ACCESS_EXCEPTION);
        self::assertEquals( 3002, ExceptionConstants::USER_INFO_EXISTS_EXCEPTION);
        self::assertEquals( 3003, ExceptionConstants::USER_INVALID_ACTION_EXCEPTION);
        self::assertEquals( 4000, ExceptionConstants::COMPANY_NOT_FOUND_EXCEPTION);
        self::assertEquals( 4001, ExceptionConstants::COMPANY_INVALID_ACCESS_EXCEPTION);
        self::assertEquals( 5000, ExceptionConstants::REQUEST_EXISTS_EXCEPTION);
        self::assertEquals( 5001, ExceptionConstants::REQUEST_INACCESSIBLE_EXCEPTION);
        self::assertEquals( 5002, ExceptionConstants::REQUEST_INVALID_EXCEPTION);
        self::assertEquals( 9000, ExceptionConstants::NEUBUS_SRM_GENERAL_EXCEPTION);
    }
}