<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 13:08
 */

namespace NeubusSrm\Lib\Builders;

/**
 * Class BuilderFactory
 * @package NeubusSrm\Lib\Builders
 */
abstract class BuilderFactory
{

	/**
	 * @return Builder
	 */
	public abstract static function create() : Builder;

	final protected function __construct() {
	}
}