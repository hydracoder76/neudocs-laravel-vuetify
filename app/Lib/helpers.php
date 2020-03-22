<?php
/**
 * User: mlawson
 * Date: 2019-03-10
 * Time: 12:56
 */

if (!function_exists('neu_throw_if')) {

    /**
     * @param bool $condition
     * @param $exception
     * @param mixed ...$params
     * @return bool
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    function neu_throw_if(bool $condition, $exception, ...$params) {

        if ($exception instanceof \NeubusSrm\Lib\Exceptions\NeuSrmException && $condition) {
            throw is_string($exception) ? new $exception(...$params) : $exception;
        }

        return $condition;
    }
}

if (!function_exists('neu_throw_unless')) {

    /**
     * @param bool $condition
     * @param $exception
     * @param mixed ...$params
     * @return bool
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    function neu_throw_unless(bool $condition, $exception, ...$params) {
        // invert the condition and it's literally the same
        return neu_throw_if(!$condition, $exception, $params);
    }
}
