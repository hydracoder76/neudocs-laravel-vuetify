<?php
/**
 * User: mlawson
 * Date: 11/8/18
 * Time: 1:49 PM
 */

namespace Tests\Unit\Lib\Constants;


use NeubusSrm\Lib\Constants\HttpConstants;
use Tests\TestCase;

/**
 * Class HttpConstantsTest
 * @package Tests\Unit\Lib\Constants
 */
class HttpConstantsTest extends TestCase
{

    public function testHttpConstants() {
        self::assertEquals(200, HttpConstants::HTTP_OK);
        self::assertEquals(201, HttpConstants::HTTP_CREATED);
        self::assertEquals(400, HttpConstants::HTTP_BAD_REQUEST);
        self::assertEquals(403, HttpConstants::HTTP_FORBIDDEN);
        self::assertEquals(404, HttpConstants::HTTP_NOT_FOUND);
        self::assertEquals(422, HttpConstants::HTTP_INVALID_REQUEST);
        self::assertEquals(500, HttpConstants::HTTP_SERVER_ERROR);
    }
}