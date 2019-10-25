<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 * @package MShop
 * @subpackage Common
 */


namespace Aimeos\MShop\Common\Item\PropertyRef;


/**
 * Common trait for items containing property items
 *
 * @package MShop
 * @subpackage Common
 */
trait Traits
{
	private $propItems = [];
	private $propRmItems = [];
	private $propMax = 0;


	/**
	 * Creates a deep clone of all objects
	 */
	public function __clone()
	{
		parent::__clone();

		foreach( $this->propItems as $key => $item ) {
			$this->propItems[$key] = clone $item;
		}

		foreach( $this->propRmItems as $key => $item ) {
			$this->propRmItems[$key] = clone $item;
		}
	}


	/**
	 * Adds a new property item or overwrite an existing one
	 *
	 * @param \Aimeos\MShop\Common\Item\Property\Iface $item New or existing property item
	 * @return \Aimeos\MShop\Common\Item\Iface Self object for method chaining
	 */
	public function addPropertyItem( \Aimeos\MShop\Common\Item\Property\Iface $item )
	{
		$id = $item->getId() ?: '_' . $this->getId() . '_' . $item->getType() . '_' . $item->getLanguageId() . '_' . $item->getValue();

		unset( $this->propItems[$id] ); // append at the end
		$this->propItems[$id] = $item;

		return $this;
	}


	/**
	 * Removes an existing property item
	 *
	 * @param \Aimeos\MShop\Common\Item\Property\Iface $item Existing property item
	 * @return \Aimeos\MShop\Common\Item\Iface Self object for method chaining
	 */
	public function deletePropertyItem( \Aimeos\MShop\Common\Item\Property\Iface $item )
	{
		foreach( $this->propItems as $key => $propItem )
		{
			if( $propItem === $item )
			{
				$this->propRmItems[$propItem->getId()] = $propItem;
				unset( $this->propItems[$key] );

				return $this;
			}
		}

		return $this;
	}


	/**
	 * Removes a list of existing property items
	 *
	 * @param \Aimeos\MShop\Common\Item\Property\Iface[] $items Existing property items
	 * @return \Aimeos\MShop\Common\Item\Iface Self object for method chaining
	 * @throws \Aimeos\MShop\Exception If an item isn't a property item or isn't found
	 */
	public function deletePropertyItems( array $items )
	{
		foreach( $items as $item ) {
			$this->deletePropertyItem( $item );
		}

		return $this;
	}


	/**
	 * Returns the deleted property items
	 *
	 * @return \Aimeos\MShop\Common\Item\Property\Iface[] Property items
	 */
	public function getPropertyItemsDeleted()
	{
		return $this->propRmItems;
	}


	/**
	 * Returns the property values for the given type
	 *
	 * @param string $type Type of the properties
	 * @return array List of property values
	 */
	public function getProperties( $type )
	{
		$list = [];

		foreach( $this->getPropertyItems( $type ) as $id => $item ) {
			$list[$id] = $item->getValue();
		}

		return $list;
	}


	/**
	 * Returns the property item for the given type, language and value
	 *
	 * @param string $type Name of the property type
	 * @param string $langId ISO language code (e.g. "en" or "en_US") or null if not language specific
	 * @param string $value Value of the property
	 * @param boolean $active True to return only active items, false to return all
	 * @return \Aimeos\MShop\Common\Item\Property\Iface|null Matching property item or null if none
	 */
	public function getPropertyItem( $type, $langId, $value, $active = true )
	{
		foreach( $this->propItems as $propItem )
		{
			if( $propItem->getType() === $type && $propItem->getLanguageId() === $langId
				&& $propItem->getValue() === $value && ( $active === false || $propItem->isAvailable() )
			) {
				return $propItem;
			}
		}
	}


	/**
	 * Returns the property items of the product
	 *
	 * @param array|string|null $type Name of the property item type or null for all
	 * @param boolean $active True to return only active items, false to return all
	 * @return \Aimeos\MShop\Common\Item\Property\Iface[] Associative list of property IDs as keys and property items as values
	 */
	public function getPropertyItems( $type = null, $active = true )
	{
		$list = [];

		foreach( $this->propItems as $propId => $propItem )
		{
			if( ( $type === null || in_array( $propItem->getType(), (array) $type ) )
				&& ( $active === false || $propItem->isAvailable() )
			) {
				$list[$propId] = $propItem;
			}
		}

		return $list;
	}


	/**
	 * Adds new list items, updates existing ones and deletes old ones
	 *
	 * {@inheritDoc}
	 *
	 * @param array $entries List of arrays with key/value pairs for lists data and referenced data
	 * @param array|string|null $types Name of the types to updata the entries with (null for all)
	 */
	public function setProperties( array $entries, $types = null )
	{
		$items = $this->getPropertyItems( $types, false );

		foreach( $entries as $entry )
		{
			$p = new \Aimeos\MShop\Common\Item\Property\Standard( $this->getResourceType() . '.property.' );
			$p = $p->fromArray( $entry );

			if( ( $prop = $this->getPropertyItem( $p->getType(), $p->getLanguageId(), $p->getValue(), false ) ) === null ) {
				$this->addPropertyItem( $p );
			} else {
				unset( $items[$prop->getId()] );
			}
		}

		return $this->deletePropertyItems( $items );
	}


	/**
	 * Adds a new property item or overwrite an existing one
	 *
	 * @param \Aimeos\MShop\Common\Item\Property\Iface[] $items New list of property items
	 * @return \Aimeos\MShop\Common\Item\Iface Self object for method chaining
	 */
	public function setPropertyItems( array $items )
	{
		$this->propItems = [];

		foreach( $items as $item )
		{
			$id = $item->getId() ?: '_' . $this->getId() . '_' . $item->getType() . '_' . $item->getLanguageId() . '_' . $item->getValue();
			$this->propItems[$id] = $item;
		}

		return $this;
	}


	/**
	 * Returns the resource type of the item.
	 *
	 * @return string Resource type of the item
	 */
	abstract public function getResourceType();


	/**
	 * Returns the unique ID of the item.
	 *
	 * @return string|null ID of the item
	 */
	abstract public function getId();


	/**
	 * Sets the property items in the trait
	 *
	 * @param \Aimeos\MShop\Common\Item\Property\Iface[] $items Property items
	 */
	protected function initPropertyItems( array $items )
	{
		$this->propItems = $items;
	}
}
