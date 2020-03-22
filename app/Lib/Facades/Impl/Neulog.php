<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-22
 * Time: 13:53
 */

namespace NeubusSrm\Lib\Facades\Impl;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Facades\NeulogContract;
use NeubusSrm\Lib\Logging\Neulogger;
use NeubusSrm\Lib\Wrappers\Collections\NeuTypedCollection;

/**
 * Class Neulog
 * @package NeubusSrm\Lib\Facades\Impl
 */
class Neulog implements NeulogContract
{

    /**
     * @var Neulogger
     */
    protected $dataStore;

    /**
     * Neulog constructor.
     */
    public function __construct() {
        $logStorageClassName = config('srm.log_storage');
        $this->dataStore = resolve($logStorageClassName);
    }

    /**
     * @return bool
     */
    public function canShowLogForUserRole(): bool {
        // TODO: Implement canShowLogForUserRole() method.
    }

    /**
     * @param string $message
     * @param Collection $collection
     * @param string $logLevel
     * @throws \ReflectionException
     */
    public function write(string $message, Collection $collection, string $logLevel) : void {
        $constants = (new \ReflectionClass(Neulogger::class))->getConstants();
        if (in_array($logLevel, $constants, true)) {
            $this->dataStore->log($message, $collection, $logLevel);
        }
        // fail silently
    }

    /**
     * @return Neulogger
     */
    public function getLogDataSource() : Neulogger {
        return $this->dataStore;
    }

    /**
     * Creates a "where" condition such that a log item can be grabbed by anything valid in the datasource
     * The datasource decides how to interpret this parameter
     * @param $logKey
     * @param $byValue
     * @return NeuTypedCollection
     */
    public function getLogItem($logKey, $byValue) : NeuTypedCollection {
        return $this->dataStore->getLogItemByKey($logKey, $byValue);
    }

    /**
     * @param string $action
     * @return array
     */
    public function getRolesForLogAction(string $action) : array {
        // TODO: Implement getRolesForLogAction() method.
    }
}
