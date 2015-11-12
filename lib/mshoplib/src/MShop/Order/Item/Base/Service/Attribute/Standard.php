<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Item\Base\Service\Attribute;


/**
 * Default order item base service attribute.
 *
 * @package MShop
 * @subpackage Order
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Order\Item\Base\Service\Attribute\Iface
{
	private $values;


	/**
	 * Initializes the order item base service attribute item.
	 *
	 * @param array $values Associative array of key/value pairs.
	 */
	public function __construct( array $values = array( ) )
	{
		parent::__construct( 'order.base.service.attribute.', $values );

		$this->values = $values;
	}


	/**
	 * Returns the original attribute ID of the service attribute item.
	 *
	 * @return string Attribute ID of the service attribute item
	 */
	public function getAttributeId()
	{
		return ( isset( $this->values['attrid'] ) ? (string) $this->values['attrid'] : '' );
	}


	/**
	 * Sets the original attribute ID of the service attribute item.
	 *
	 * @param string $id Attribute ID of the service attribute item
	 */
	public function setAttributeId( $id )
	{
		if( $id == $this->getAttributeId() ) { return; }

		$this->values['attrid'] = (string) $id;
		$this->setModified();
	}


	/**
	 * Returns the ID of the ordered service item as parent
	 *
	 * @return integer|null ID of the ordered service item
	 */
	public function getParentId()
	{
		return ( isset( $this->values['ordservid'] ) ? (int) $this->values['ordservid'] : null );
	}


	/**
	 * Sets the ID of the ordered service item as parent
	 *
	 * @param integer $id ID of the ordered service item
	 */
	public function setParentId( $id )
	{
		if( $id == $this->getParentId() ) { return; }

		$this->values['ordservid'] = (int) $id;
		$this->setModified();
	}


	/**
	 * Returns the type of the service attribute item.
	 *
	 * @return string Type of the service attribute item
	 */
	public function getType()
	{
		return ( isset( $this->values['type'] ) ? (string) $this->values['type'] : '' );
	}


	/**
	 * Sets a new type for the service attribute item.
	 *
	 * @param string $type Type of the service attribute
	 */
	public function setType( $type )
	{
		if( $type == $this->getType() ) { return; }

		$this->values['type'] = (string) $type;
		$this->setModified();
	}


	/**
	 * Returns the name of the service attribute item.
	 *
	 * @return string Name of the service attribute item
	 */
	public function getName()
	{
		return ( isset( $this->values['name'] ) ? (string) $this->values['name'] : '' );
	}


	/**
	 * Sets a new name for the service attribute item.
	 *
	 * @param string $name Name as defined by the service provider
	 */
	public function setName( $name )
	{
		$this->values['name'] = (string) $name;
		$this->setModified();
	}


	/**
	 * Returns the code of the service attribute item.
	 *
	 * @return string Code of the service attribute item
	 */
	public function getCode()
	{
		return ( isset( $this->values['code'] ) ? (string) $this->values['code'] : '' );
	}


	/**
	 * Sets a new code for the service attribute item.
	 *
	 * @param string $code Code as defined by the service provider
	 */
	public function setCode( $code )
	{
		$this->checkCode( $code );

		if( $code == $this->getCode() ) { return; }

		$this->values['code'] = (string) $code;
		$this->setModified();
	}


	/**
	 * Returns the value of the service attribute item.
	 *
	 * @return string|array Service attribute item value
	 */
	public function getValue()
	{
		return ( isset( $this->values['value'] ) ? $this->values['value'] : '' );
	}


	/**
	 * Sets a new value for the service item.
	 *
	 * @param string|array $value service attribute item value
	 */
	public function setValue( $value )
	{
		if( $value == $this->getValue() ) { return; }

		$this->values['value'] = $value;
		$this->setModified();
	}


	/**
	 * Returns the item type
	 *
	 * @return Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'order/base/service/attribute';
	}


	/**
	 * Copys all data from a given attribute item.
	 *
	 * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item to copy from
	 */
	public function copyFrom( \Aimeos\MShop\Attribute\Item\Iface $item )
	{
		$this->setAttributeId( $item->getId() );
		$this->setName( $item->getName() );
		$this->setCode( $item->getType() );
		$this->setValue( $item->getCode() );

		$this->setModified();
	}


	/**
	 * Sets the item values from the given array.
	 *
	 * @param array $list Associative list of item keys and their values
	 * @return array Associative list of keys and their values that are unknown
	 */
	public function fromArray( array $list )
	{
		$unknown = array();
		$list = parent::fromArray( $list );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'order.base.service.attribute.attrid': $this->setAttributeId( $value ); break;
				case 'order.base.service.attribute.parentid': $this->setParentId( $value ); break;
				case 'order.base.service.attribute.type': $this->setType( $value ); break;
				case 'order.base.service.attribute.name': $this->setName( $value ); break;
				case 'order.base.service.attribute.code': $this->setCode( $value ); break;
				case 'order.base.service.attribute.value': $this->setValue( $value ); break;
				default: $unknown[$key] = $value;
			}
		}

		return $unknown;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @return Associative list of item properties and their values
	 */
	public function toArray()
	{
		$list = parent::toArray();

		$list['order.base.service.attribute.attrid'] = $this->getAttributeId();
		$list['order.base.service.attribute.parentid'] = $this->getParentId();
		$list['order.base.service.attribute.type'] = $this->getType();
		$list['order.base.service.attribute.name'] = $this->getName();
		$list['order.base.service.attribute.code'] = $this->getCode();
		$list['order.base.service.attribute.value'] = $this->getValue();

		return $list;
	}
}