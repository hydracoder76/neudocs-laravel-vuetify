<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 13:12
 */

namespace NeubusSrm\Lib\Builders;

use NeubusSrm\Lib\Builders\Menu\MenuBuilder;

/**
 * Class MenuBuilderFactory
 * @package NeubusSrm\Lib\Builders
 */
class MenuBuilderFactory extends BuilderFactory
{

	/**
	 * @return Builder
	 */
	public static function create(): Builder {
		return resolve(MenuBuilder::class);
	}


}