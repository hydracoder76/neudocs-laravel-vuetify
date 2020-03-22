<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 12:44
 */

namespace NeubusSrm\Lib\Builders\Menu;

use NeubusSrm\Lib\Builders\Builder;
use NeubusSrm\Lib\Builders\Menu\MenuNode;

/**
 * Interface MenuBuilder
 * @package NeubusSrm\Lib\Builders
 */
interface MenuBuilder extends Builder
{

	/**
	 * Add a new menu node to the root, or add a child to the existing menu node
	 * @param \NeubusSrm\Lib\Builders\Menu\MenuNode $menuNode
	 * @param array $params
	 * @return MenuNode
	 */
	public function addMenuNode(MenuNode $menuNode, array $params = []) : MenuNode;

	/**
	 * @param \NeubusSrm\Lib\Builders\Menu\MenuNode $menuNode
	 * @param array $params
	 * @return \NeubusSrm\Lib\Builders\Menu\MenuNode
	 */
	public function setMenuItemRoot(MenuNode $menuNode, array $params = []) : MenuNode;

	/**
	 * Take all gathered nodes and send back a tree as an array
	 * @return array
	 */
	public function buildMenuTree() : array;

}