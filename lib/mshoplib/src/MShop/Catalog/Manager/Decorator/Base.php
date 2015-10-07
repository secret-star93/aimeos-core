<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MShop
 * @subpackage Catalog
 */


namespace Aimeos\MShop\Catalog\Manager\Decorator;


/**
 * Provides common methods for catalog manager decorators.
 *
 * @package MShop
 * @subpackage Catalog
 */
abstract class Base
	extends \Aimeos\MShop\Common\Manager\Decorator\Base
	implements \Aimeos\MShop\Common\Manager\Decorator\Iface, \Aimeos\MShop\Catalog\Manager\Iface
{
	/**
	 * Returns a list of items starting with the given category that are in the path to the root node
	 *
	 * @param integer $id ID of item to get the path for
	 * @param array $ref List of domains to fetch list items and referenced items for
	 * @return array Associative list of items implementing \Aimeos\MShop\Catalog\Item\Iface with IDs as keys
	 */
	public function getPath( $id, array $ref = array() )
	{
		return $this->getManager()->getPath( $id, $ref );
	}


	/**
	 * Returns a node and its descendants depending on the given resource.
	 *
	 * @param integer|null $id Retrieve nodes starting from the given ID
	 * @param array List of domains (e.g. text, media, etc.) whose referenced items should be attached to the objects
	 * @param integer $level One of the level constants from \Aimeos\MW\Tree\Manager\Base
	 * @param \Aimeos\MW\Common\Criteria\Iface|null $criteria Optional criteria object with conditions
	 * @return \Aimeos\MW\Tree\Node\Iface Node, maybe with subnodes
	 */
	public function getTree( $id = null, array $ref = array(), $level = \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE, \Aimeos\MW\Common\Criteria\Iface $criteria = null )
	{
		return $this->getManager()->getTree( $id, $ref, $level, $criteria );
	}


	/**
	 * Adds a new item object.
	 *
	 * @param \Aimeos\MShop\Common\Item\Iface $item Item which should be inserted
	 */
	public function insertItem( \Aimeos\MShop\Catalog\Item\Iface $item, $parentId = null, $refId = null )
	{
		$this->getManager()->insertItem( $item, $parentId, $refId );
	}


	/**
	 * Moves an existing item to the new parent in the storage.
	 *
	 * @param mixed $id ID of the item that should be moved
	 * @param mixed $oldParentId ID of the old parent item which currently contains the item that should be removed
	 * @param mixed $newParentId ID of the new parent item where the item should be moved to
	 * @param mixed $refId ID of the item where the item should be inserted before (null to append)
	 */
	public function moveItem( $id, $oldParentId, $newParentId, $refId = null )
	{
		$this->getManager()->moveItem( $id, $oldParentId, $newParentId, $refId );
	}


	/**
	 * Rebuild the catalog index for searching products.
	 * This can be a long lasting operation.
	 */
	public function rebuildIndex()
	{
		$this->getManager()->rebuildIndex();
	}
}
