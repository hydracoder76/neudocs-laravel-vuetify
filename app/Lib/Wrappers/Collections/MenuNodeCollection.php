<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 13:24
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use NeubusSrm\Lib\Builders\Menu\MenuNode;

/**
 * Class MenuNodeCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class MenuNodeCollection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		MenuNode::class;
	}


}