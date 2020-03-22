<?php
/**
 * User: mlawson
 * Date: 2018-11-30
 * Time: 11:12
 */

namespace NeubusSrm\Lib\DataMappers;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface Formatter
 * @package NeubusSrm\Lib\DataMappers
 */
interface Formatter extends DataMapper
{
	const MODE_PROJECT = 0;
	const MODE_REQUEST = 1;
    const MODE_TODO = 2;
    const MODE_COMPLETE = 3;
    const MODE_PROJECTMANAGEMENT = 4;
    const MODE_PARTS = 5;
    const MODE_LOG = 6;

	/**
	 * @param Collection $data
	 * @param int $mode
	 * @return array
	 */
	public function format(Collection $data, int $mode) : array;
}