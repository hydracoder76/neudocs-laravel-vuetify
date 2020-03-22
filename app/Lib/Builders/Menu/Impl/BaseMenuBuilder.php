<?php
/**
 * User: mlawson
 * Date: 2018-12-18
 * Time: 12:54
 */

namespace NeubusSrm\Lib\Builders\Menu\Impl;

use NeubusSrm\Lib\Builders\Menu\MenuBuilder;
use NeubusSrm\Lib\Builders\Menu\MenuNode;
use NeubusSrm\Lib\Exceptions\NeuMenuBuilderException;
use NeubusSrm\Lib\Wrappers\Collections\MenuNodeCollection;

/**
 * Class BaseMenuBuilder
 * @package NeubusSrm\Lib\Builders\Menu\Impl
 */
class BaseMenuBuilder implements MenuBuilder
{

	/**
	 * @var MenuNode
	 */
	private $menuNode;

	/**
	 * @var MenuNodeCollection
	 */
	private $menuNodeCollection;

	/**
	 * BaseMenuBuilder constructor.
	 * @param MenuNode $menuNode
	 * @param MenuNodeCollection $menuNodeCollection
	 */
	public function __construct(MenuNode $menuNode, MenuNodeCollection $menuNodeCollection) {
		$this->menuNode = $menuNode;
		$this->menuNodeCollection = $menuNodeCollection;
	}

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
	public function build(array $params = []): array {
        // TODO: Implement build() method.
        throw new \Exception('not implemented for concrete');
    }

    /**
	 * @param MenuNode $menuNode
	 * @param array $params
	 * @return MenuNode
	 */
	public function addMenuNode(MenuNode $menuNode, array $params = []): MenuNode {
		// TODO: Implement addMenuNode() method.
	}

	/**
	 * @param MenuNode $menuNode
	 * @param array $params
	 * @return MenuNode
	 */
	public function setMenuItemRoot(MenuNode $menuNode, array $params = []): MenuNode {
		// TODO: Implement setMenuItemRoot() method.
	}

	/**
	 * @return array
	 */
	public function buildMenuTree(): array {
		// TODO: Implement buildMenuTree() method.
	}


}