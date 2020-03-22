<?php
/**
 * User: mlawson
 * Date: 2019-01-07
 * Time: 14:12
 */

namespace NeubusSrm\Lib\Wrappers\Database\Partitions;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Interface QueriesPartitions
 * @package NeubusSrm\Lib\Wrappers\Database\Partitions
 */
interface QueriesPartitions
{


	/**
	 * Set the actual driver that this partition model will
	 * use to talk to the partition table
	 * @param PartitionDriver $partitionDriver
	 */
	public function setDriver(PartitionDriver $partitionDriver) : void;

	/**
	 * Set the base table name that acts as the master table that guides the database's partitioning
	 * system
	 * @param string $tableName
	 * @return mixed
	 */
	public function setBaseTableName(string $tableName) : void;

	/**
	 * Return the base table name
	 * @return string
	 */
	public function getBaseTableName() : string;

	/**
	 * Return the name of the table that is the partition currently being used to write to.
	 * For reads this doesn't really matter since the database chooses that
	 * @return string
	 */
	public function getCurrentPartitionTblName() : string;

	/**
	 * @param string $tableName
	 * @return bool
	 */

	// TODO: commented out methods will be moved to the driver interface
	//public function forceNewPartition(string $tableName) : bool;

	/**
	 * @return string
	 */
	//public function getCurrPartitionThreshold() : string;

	/**
	 * Return the last result that was a returned for this given instance
	 * @return PartitionResult
	 */
	public function getLastResult() : PartitionResult;

	/**
	 * Insert a collection in to the database where
	 * the field keys match the
	 * @param Collection $fields
	 * @return PartitionResult
	 */
	public function insert(Collection $fields) : PartitionResult;

	/**
	 * Update fields specified in the collection. If the update is to happen
	 * by key, then the primary key will be used to lookup the field to be updated
	 * else any key will be used instead, whatever is easiest
	 * @param Collection $fields
	 * @param bool $byKey
	 * @return PartitionResult
	 */
	public function update(Collection $fields, bool $byKey = true) : PartitionResult;

	/**
	 * Select fields specified by the array, if any. If none
	 * are specified then all fields in a row will be returned.
	 * If the select is to be by key, that will be used to filter rows
	 * otherwise this will be determined automatically by the contents of the
	 * array
	 * @param array $fields
	 * @param bool $byKey
	 * @return PartitionResult
	 */
	public function select(array $fields = [], bool $byKey = true) : PartitionResult;

	/**
	 * Delete a record by setting its DELETED_AT field to a given timestamp. The DELETED_AT field name
	 * is pulled from Eloquent
	 * @param Collection $conditions
	 * @param bool $byKey
	 * @return int
	 */
	public function delete(Collection $conditions, bool $byKey = true) : int;

	/**
	 * Completely removes a record
	 * @see delete()
	 * @param Collection $condittions
	 * @param bool $byKey
	 * @return int
	 */
	public function hardDelete(Collection $condittions, bool $byKey = true) : int;

	/**
	 * Return the current Builder object to be used in generating a query
	 * @return Builder
	 */
	public function getQueryBuilder() : Builder;

	/**
	 * If a prebuilt Builder object is to be used, you can set it here
	 * @param Builder $altBuilder
	 */
	public function setAlternateQueryBuilder(Builder $altBuilder) : void;

	/**
	 * @param string $queryString
	 * @return PartitionResult
	 */
	//public function query(string $queryString) : PartitionResult;

	/**
	 * @param string $queryString
	 * @param array $params
	 * @return PartitionResult
	 */
	//public function paramQuery(string $queryString, array $params) : PartitionResult;

	/**
	 * The name of the driver in use as defined in the databases.php config file
	 * @return string
	 */
	public function getDriverName() : string;

	/**
	 * The class reference to the in use driver
	 * @return string
	 */
	public function getDriverClass() : string;

	/**
	 * The key to use for partitioning, however it is
	 * recommended to use the default
	 * @param string $keyName
	 */
	public function setPartitionKey(string $keyName) : void;

	/**
	 * Return the current partition key column name
	 * @return string
	 */
	public function getPartitionKey() : string;

	/**
	 * @param string $triggerName
	 * @param string $dbFunctionName
	 */
	//public function callPartitionTrigger(string $triggerName, string $dbFunctionName = '') : void;

	/**
	 * @return PartitionResult
	 */
	//public function getLastPartitionId() : PartitionResult;




}