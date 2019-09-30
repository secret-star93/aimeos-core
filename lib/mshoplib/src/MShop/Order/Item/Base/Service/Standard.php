<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Item\Base\Service;


/**
 * Default implementation for order item base service.
 *
 * @package MShop
 * @subpackage Order
 */
class Standard extends Base implements Iface
{
	/**
	 * Initializes the order base service item
	 *
	 * @param \Aimeos\MShop\Price\Item\Iface $price
	 * @param array $values Values to be set on initialisation
	 * @param array $attributes Attributes to be set on initialisation
	 */
	public function __construct( \Aimeos\MShop\Price\Item\Iface $price, array $values = [], array $attributes = [] )
	{
		parent::__construct( $price, $values, $attributes );
	}


	/**
	 * Returns the ID of the site the item is stored
	 *
	 * @return string|null Site ID (or null if not available)
	 */
	public function getSiteId()
	{
		return $this->get( 'order.base.service.siteid' );
	}


	/**
	 * Sets the site ID of the item.
	 *
	 * @param string $value Unique site ID of the item
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setSiteId( $value )
	{
		return $this->set( 'order.base.service.siteid', (string) $value );
	}


	/**
	 * Returns the order base ID of the order service if available.
	 *
	 * @return string|null Base ID of the item.
	 */
	public function getBaseId()
	{
		return $this->get( 'order.base.service.baseid' );
	}


	/**
	 * Sets the order service base ID of the order service item.
	 *
	 * @param string $value Order service base ID
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setBaseId( $value )
	{
		return $this->set( 'order.base.service.baseid', (string) $value );
	}


	/**
	 * Returns the original ID of the service item used for the order.
	 *
	 * @return string Original service ID
	 */
	public function getServiceId()
	{
		return (string) $this->get( 'order.base.service.serviceid', '' );
	}


	/**
	 * Sets a new ID of the service item used for the order.
	 *
	 * @param string $servid ID of the service item used for the order
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setServiceId( $servid )
	{
		return $this->set( 'order.base.service.serviceid', (string) $servid );
	}


	/**
	 * Returns the code of the service item.
	 *
	 * @return string Service item code
	 */
	public function getCode()
	{
		return (string) $this->get( 'order.base.service.code', '' );
	}


	/**
	 * Sets a new code for the service item.
	 *
	 * @param string $code Code as defined by the service provider
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setCode( $code )
	{
		return $this->set( 'order.base.service.code', $this->checkCode( $code ) );
	}


	/**
	 * Returns the name of the service item.
	 *
	 * @return string Service item name
	 */
	public function getName()
	{
		return (string) $this->get( 'order.base.service.name', '' );
	}


	/**
	 * Sets a new name for the service item.
	 *
	 * @param string $name service item name
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setName( $name )
	{
		return $this->set( 'order.base.service.name', (string) $name );
	}


	/**
	 * Returns the type of the service item.
	 *
	 * @return string service item type
	 */
	public function getType()
	{
		return (string) $this->get( 'order.base.service.type', '' );
	}


	/**
	 * Sets a new type for the service item.
	 *
	 * @param string $type Type of the service item
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setType( $type )
	{
		return $this->set( 'order.base.service.type', $this->checkCode( $type ) );
	}


	/**
	 * Returns the location of the media.
	 *
	 * @return string Location of the media
	 */
	public function getMediaUrl()
	{
		return (string) $this->get( 'order.base.service.mediaurl', '' );
	}


	/**
	 * Sets the media url of the service item.
	 *
	 * @param string $value Location of the media/picture
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function setMediaUrl( $value )
	{
		return $this->set( 'order.base.service.mediaurl', (string) $value );
	}


	/**
	 * Returns the position of the service in the order.
	 *
	 * @return integer|null Service position in the order from 0-n
	 */
	public function getPosition()
	{
		return $this->get( 'order.base.service.position' );
	}


	/**
	 * Sets the position of the service within the list of ordered servicees
	 *
	 * @param integer|null $value Service position in the order from 0-n or null for resetting the position
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 * @throws \Aimeos\MShop\Order\Exception If the position is invalid
	 */
	public function setPosition( $value )
	{
		if( $value < 0 ) {
			throw new \Aimeos\MShop\Order\Exception( sprintf( 'Order service position "%1$s" must be greater than 0', $value ) );
		}

		return $this->set( 'order.base.service.position', ( $value !== null ? (int) $value : null ) );
	}


	/*
	 * Sets the item values from the given array and removes that entries from the list
	 *
	 * @param array &$list Associative list of item keys and their values
	 * @param boolean True to set private properties too, false for public only
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order service item for chaining method calls
	 */
	public function fromArray( array &$list, $private = false )
	{
		$item = parent::fromArray( $list, $private );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'order.base.service.siteid': !$private ?: $item = $item->setSiteId( $value ); break;
				case 'order.base.service.baseid': !$private ?: $item = $item->setBaseId( $value ); break;
				case 'order.base.service.serviceid': !$private ?: $item = $item->setServiceId( $value ); break;
				case 'order.base.service.type': $item = $item->setType( $value ); break;
				case 'order.base.service.code': $item = $item->setCode( $value ); break;
				case 'order.base.service.name': $item = $item->setName( $value ); break;
				case 'order.base.service.position': $item = $item->setPosition( $value ); break;
				case 'order.base.service.mediaurl': $item = $item->setMediaUrl( $value ); break;
				default: continue 2;
			}

			unset( $list[$key] );
		}

		return $item;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @param boolean True to return private properties, false for public only
	 * @return array Associative list of item properties and their values.
	 */
	public function toArray( $private = false )
	{
		$list = parent::toArray( $private );

		$list['order.base.service.type'] = $this->getType();
		$list['order.base.service.code'] = $this->getCode();
		$list['order.base.service.name'] = $this->getName();
		$list['order.base.service.position'] = $this->getPosition();
		$list['order.base.service.mediaurl'] = $this->getMediaUrl();

		if( $private === true )
		{
			$list['order.base.service.baseid'] = $this->getBaseId();
			$list['order.base.service.serviceid'] = $this->getServiceId();
		}

		return $list;
	}


	/**
	 * Copys all data from a given service item.
	 *
	 * @param \Aimeos\MShop\Service\Item\Iface $service New service item
	 * @return \Aimeos\MShop\Order\Item\Base\Service\Iface Order base service item for chaining method calls
	 */
	public function copyFrom( \Aimeos\MShop\Service\Item\Iface $service )
	{
		$this->setSiteId( $service->getSiteId() );
		$this->setCode( $service->getCode() );
		$this->setName( $service->getName() );
		$this->setType( $service->getType() );
		$this->setServiceId( $service->getId() );

		$items = $service->getRefItems( 'media', 'default', 'default' );

		if( ( $item = reset( $items ) ) !== false ) {
			$this->setMediaUrl( $item->getUrl() );
		}

		return $this->setModified();
	}
}
