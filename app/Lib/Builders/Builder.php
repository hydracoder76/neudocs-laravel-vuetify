<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 13:10
 */

namespace NeubusSrm\Lib\Builders;

/**
 * Interface Builder
 * @package NeubusSrm\Lib\Builders
 */
interface Builder
{
    /**
     * @param array $params
     * @return array
     */
    public function build(array $params = []) : array;

}