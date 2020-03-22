<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-24
 * Time: 16:10
 */

namespace NeubusSrm\Lib\Facades;

use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\Neulogger;
use NeubusSrm\Lib\Wrappers\Collections\NeuTypedCollection;

/**
 * Interface SrmLogContract
 * @package NeubusSrm\Lib\Facades
 */
interface NeulogContract
{

    /**
     * Tells the calling code whether or not a specific log item can be shown for
     * the role of the logged in user.
     * @return bool
     */
    public function canShowLogForUserRole() : bool;

    /**
     * Actually write a message using the configured log driver
     * @param string $message
     * @param Collection $collection
     * @param string $logLevel
     */
    public function write(string $message, Collection $collection, string $logLevel) : void;

    /**
     * Returns the underlying data source where logs are written to and read from
     * Normally this is a database connection, but could be anything based on driver
     * @return Neulogger
     */
    public function getLogDataSource() : Neulogger;

    /**
     * Return a single log item based on some sort of key
     * @param $logKey
     * @param $byValue
     * @return NeuTypedCollection
     */
    public function getLogItem($logKey, $byValue) : NeuTypedCollection;

    /**
     * For any specific action passed in, return a list of roles allowed to act on it
     * @param string $action
     * @return array
     */
    public function getRolesForLogAction(string $action) : array;

}
