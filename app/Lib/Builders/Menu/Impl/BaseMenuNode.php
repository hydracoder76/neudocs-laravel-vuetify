<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 12:54
 */

namespace NeubusSrm\Lib\Builders\Menu\Impl;

use NeubusSrm\Lib\Builders\Menu\MenuNode;

/**
 * Class BaseMenuNode
 * @package NeubusSrm\Lib\Builders\Menu\Impl
 */
class BaseMenuNode implements MenuNode
{
	public function addAttribute(string $attrName, string $attrValue): MenuNode {
		// TODO: Implement addAttribute() method.
	}

	public function addDirectChild(MenuNode $menuNode): MenuNode {
		// TODO: Implement addDirectChild() method.
	}

	public function createEmptyNode(): MenuNode {
		// TODO: Implement createEmptyNode() method.
	}


}