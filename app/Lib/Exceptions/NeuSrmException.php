<?php
/**
 * User: mlawson
 * Date: 11/8/18
 * Time: 1:24 PM
 */

namespace NeubusSrm\Lib\Exceptions;

use Throwable;

/**
 * Class NeuSrmException
 * @package NeubusSrm\Lib\Exceptions
 */
abstract class NeuSrmException extends \Exception
{

    /**
     * NeuSrmException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public abstract function getInternalCode() : int;


    /**
     * @return int
     */
    public abstract function getHttpCode() : int;

}