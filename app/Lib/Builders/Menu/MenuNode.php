<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 12:47
 */

namespace NeubusSrm\Lib\Builders\Menu;

/**
 * Interface MenuNode
 * @package NeubusSrm\Lib\Builders\Menu
 */
interface MenuNode
{

	/**
	 * @param string $attrName
	 * @param string $attrValue
	 * @return MenuNode
	 */
	public function addAttribute(string $attrName, string $attrValue) : MenuNode;

	/**
	 * @param MenuNode $menuNode
	 * @return mixed
	 */
	public function addDirectChild(MenuNode $menuNode) : MenuNode;

	/**
	 * @return MenuNode
	 */
	public function createEmptyNode() : MenuNode;
}