<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-21
 * Time: 18:48
 */

namespace NeubusSrm\Lib\Logging;

use Illuminate\Support\Collection;

/**
 * Interface Neulogger
 * @package NeubusSrm\Repositories
 */
interface Neulogger
{

    const LEVEL_INFO = 'info';
    const LEVEL_DEBUG = 'debug';
    const LEVEL_WARN = 'warn';
    const LEVEL_ERROR = 'error';

    const OP_CREATE = 'create';
    const OP_UPDATE = 'update';
    const OP_RETRIEVE = 'retrieve';
    const OP_DELETE = 'delete';

    /**
     * Only one needed method, everything else is up to the implementation
     * @param string $message
     * @param Collection $toBeLogged
     * @param string $level
     * @param array $options
     */
    public function log(string $message, Collection $toBeLogged, string $level, array $options = []) : void;

    /**
     * @return string
     */
    public function getStorageItemClassName() : string;

    /**
     * @param $key
     * @param $byValue
     * @return mixed
     */
    public function getLogItemByKey($key, $byValue);

}
