<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package MShop
 * @subpackage Coupon
 */


namespace Aimeos\MShop\Coupon\Item\Code;


/**
 * Default coupon code implementation.
 *
 * @package MShop
 * @subpackage Coupon
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Coupon\Item\Code\Iface
{
	private $values;

	/**
	 * Initializes the coupon code instance
	 *
	 * @param array $values Associative array with ID, coupon item ID, code and counter
	 */
	public function __construct( array $values = [] )
	{
		parent::__construct( 'coupon.code.', $values );

		$this->values = $values;
	}


	/**
	 * Returns the unique ID of the coupon item the code belongs to.
	 *
	 * @return string|null Unique ID of the coupon item
	 */
	public function getParentId()
	{
		if( isset( $this->values['coupon.code.parentid'] ) ) {
			return (string) $this->values['coupon.code.parentid'];
		}
	}


	/**
	 * Sets the new unique ID of the coupon item the code belongs to.
	 *
	 * @param string $id Unique ID of the coupon item
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface Coupon code item for chaining method calls
	 */
	public function setParentId( $id )
	{
		if( (string) $id !== $this->getParentId() )
		{
			$this->values['coupon.code.parentid'] = (string) $id;
			$this->setModified();
		}

		return $this;
	}


	/**
	 * Returns the code of the coupon item.
	 *
	 * @return string|null Coupon code
	 */
	public function getCode()
	{
		if( isset( $this->values['coupon.code.code'] ) ) {
			return (string) $this->values['coupon.code.code'];
		}
	}


	/**
	 * Sets the new code for the coupon item.
	 *
	 * @param string $code Coupon code
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface Coupon code item for chaining method calls
	 */
	public function setCode( $code )
	{
		if( (string) $code !== $this->getCode() )
		{
			$this->values['coupon.code.code'] = $this->checkCode( $code );
			$this->setModified();
		}

		return $this;
	}


	/**
	 * Returns the number of tries the code is valid.
	 *
	 * @return integer Number of available tries
	 */
	public function getCount()
	{
		if( isset( $this->values['coupon.code.count'] ) ) {
			return (int) $this->values['coupon.code.count'];
		}

		return 0;
	}


	/**
	 * Sets the new number of tries the code is valid.
	 *
	 * @param integer $count Number of tries
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface Coupon code item for chaining method calls
	 */
	public function setCount( $count )
	{
		if( (int) $count !== $this->getCount() )
		{
			$this->values['coupon.code.count'] = (int) $count;
			$this->setModified();
		}

		return $this;
	}


	/**
	 * Returns the starting point of time, in which the code is available.
	 *
	 * @return string|null ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function getDateStart()
	{
		if( isset( $this->values['coupon.code.datestart'] ) ) {
			return (string) $this->values['coupon.code.datestart'];
		}
	}


	/**
	 * Sets a new starting point of time, in which the code is available.
	 *
	 * @param string New ISO date in YYYY-MM-DD hh:mm:ss format
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface Coupon code item for chaining method calls
	 */
	public function setDateStart( $date )
	{
		if( $date !== $this->getDateStart() )
		{
			$this->values['coupon.code.datestart'] = $this->checkDateFormat( $date );
			$this->setModified();
		}

		return $this;
	}


	/**
	 * Returns the ending point of time, in which the code is available.
	 *
	 * @return string|null ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function getDateEnd()
	{
		if( isset( $this->values['coupon.code.dateend'] ) ) {
			return (string) $this->values['coupon.code.dateend'];
		}
	}


	/**
	 * Sets a new ending point of time, in which the code is available.
	 *
	 * @param string New ISO date in YYYY-MM-DD hh:mm:ss format
	 * @return \Aimeos\MShop\Coupon\Item\Code\Iface Coupon code item for chaining method calls
	 */
	public function setDateEnd( $date )
	{
		if( (string) $date !== $this->getDateEnd() )
		{
			$this->values['coupon.code.dateend'] = $this->checkDateFormat( $date );
			$this->setModified();
		}

		return $this;
	}


	/**
	 * Returns the item type
	 *
	 * @return string Item type, subtypes are separated by slashes
	 */
	public function getResourceType()
	{
		return 'coupon/code';
	}


	/**
	 * Tests if the item is available based on status, time, language and currency
	 *
	 * @return boolean True if available, false if not
	 */
	public function isAvailable()
	{
		return parent::isAvailable()
			&& ( $this->getDateStart() === null || $this->getDateStart() < $this->values['date'] )
			&& ( $this->getDateEnd() === null || $this->getDateEnd() > $this->values['date'] );

	}


	/**
	 * Sets the item values from the given array.
	 *
	 * @param array $list Associative list of item keys and their values
	 * @return array Associative list of keys and their values that are unknown
	 */
	public function fromArray( array $list )
	{
		$unknown = [];
		$list = parent::fromArray( $list );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'coupon.code.count': $this->setCount( $value ); break;
				case 'coupon.code.code': $this->setCode( $value ); break;
				case 'coupon.code.parentid': $this->setParentId( $value ); break;
				case 'coupon.code.datestart': $this->setDateStart( $value ); break;
				case 'coupon.code.dateend': $this->setDateEnd( $value ); break;
				default: $unknown[$key] = $value;
			}
		}

		return $unknown;
	}


	/**
	 * Returns the item values as array.
	 *
	 * @param boolean True to return private properties, false for public only
	 * @return array Associative list of item properties and their values
	 */
	public function toArray( $private = false )
	{
		$list = parent::toArray( $private );

		$list['coupon.code.code'] = $this->getCode();
		$list['coupon.code.count'] = $this->getCount();
		$list['coupon.code.datestart'] = $this->getDateStart();
		$list['coupon.code.dateend'] = $this->getDateEnd();

		if( $private === true ) {
			$list['coupon.code.parentid'] = $this->getParentId();
		}

		return $list;
	}

}
