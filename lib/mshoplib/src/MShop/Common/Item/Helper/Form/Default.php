<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MShop
 * @subpackage Common
 */


/**
 * Default implementation of the helper form item.
 *
 * @package MShop
 * @subpackage Common
 */
class MShop_Common_Item_Helper_Form_Default implements MShop_Common_Item_Helper_Form_Interface
{
	private $_url;
	private $_method;
	private $_values;
	private $_external;


	/**
	 * Initializes the object.
	 *
	 * @param string $url Initial url
	 * @param string $method Initial method (e.g. post or get)
	 * @param array $values Form parameters implementing MW_Common_Criteria_Attribute_Interface
	 * @param boolean $external True if URL points to an external site, false if it stays on the same site
	 */
	public function __construct( $url = '', $method = '', array $values = array(), $external = true )
	{
		MW_Common_Abstract::checkClassList( 'MW_Common_Criteria_Attribute_Interface', $values );

		$this->_url = (string) $url;
		$this->_external = (bool) $external;
		$this->_method = (string) $method;
		$this->_values = $values;
	}


	/**
	 * Returns if the URL points to an external site.
	 *
	 * @return boolean True if URL points to an external site, false if it stays on the same site
	 */
	public function getExternal()
	{
		return $this->_external;
	}


	/**
	 * Sets if the URL points to an external site.
	 *
	 * @param boolean $value True if URL points to an external site, false if it stays on the same site
	 */
	public function setExternal( $value )
	{
		$this->_external = (bool) $value;
	}


	/**
	 * Returns the url.
	 *
	 * @return string Url
	 */
	public function getUrl()
	{
		return $this->_url;
	}


	/**
	 * Sets the url.
	 *
	 * @param string $url Url
	 */
	public function setUrl( $url )
	{
		$this->_url = (string) $url;
	}


	/**
	 * Returns the method.
	 *
	 * @return string Method
	 */
	public function getMethod()
	{
		return $this->_method;
	}


	/**
	 * Sets the method.
	 *
	 * @param string $method Method
	 */
	public function setMethod( $method )
	{
		$this->_method = (string) $method;
	}


	/**
	 * Returns the value for the given key.
	 *
	 * @param string $key Unique key
	 * @return MW_Common_Criteria_Attribute_Interface Attribute item for the given key
	 */
	public function getValue( $key )
	{
		if( !isset( $this->_values[$key] ) ) {
			return null;
		}

		return $this->_values[$key];
	}


	/**
	 * Sets the value for the key.
	 *
	 * @param string $key Unique key
	 * @param MW_Common_Criteria_Attribute_Interface $value Attribute item for the given key
	 */
	public function setValue( $key, MW_Common_Criteria_Attribute_Interface $value )
	{
		$this->_values[$key] = $value;
	}


	/**
	 * Returns the all key/value pairs.
	 *
	 * @return array Key/value pairs, values implementing MW_Common_Criteria_Attribute_Interface
	 */
	public function getValues()
	{
		return $this->_values;
	}
}
