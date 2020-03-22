<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-22
 * Time: 13:48
 */

namespace NeubusSrm\Lib\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Neulog
 * @package NeubusSrm\Lib\Facades
 */
class Neulog extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor() : string {
        return 'neulog';
    }
}
