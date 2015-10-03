<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Controller
 * @subpackage ExtJS
 */


/**
 * ExtJS supplier address controller for admin interfaces.
 *
 * @package Controller
 * @subpackage ExtJS
 */
class Controller_ExtJS_Supplier_Address_Default
	extends Controller_ExtJS_Base
	implements Controller_ExtJS_Common_Interface
{
	private $manager = null;


	/**
	 * Initializes the supplier address controller.
	 *
	 * @param MShop_Context_Item_Interface $context MShop context object
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		parent::__construct( $context, 'Supplier_Address' );
	}


	/**
	 * Returns the manager the controller is using.
	 *
	 * @return MShop_Common_Manager_Interface Manager object
	 */
	protected function getManager()
	{
		if( $this->manager === null ) {
			$this->manager = MShop_Factory::createManager( $this->getContext(), 'supplier/address' );
		}

		return $this->manager;
	}


	/**
	 * Returns the prefix for searching items
	 *
	 * @return string MShop search key prefix
	 */
	protected function getPrefix()
	{
		return 'supplier.address';
	}


	/**
	 * Transforms ExtJS values to be suitable for storing them
	 *
	 * @param stdClass $entry Entry object from ExtJS
	 * @return stdClass Modified object
	 */
	protected function transformValues( stdClass $entry )
	{
		if( isset( $entry->{'supplier.address.languageid'} ) && $entry->{'supplier.address.languageid'} === '' ) {
			$entry->{'supplier.address.languageid'} = null;
		}

		if( isset( $entry->{'supplier.address.countryid'} ) && $entry->{'supplier.address.countryid'} === '' ) {
			$entry->{'supplier.address.countryid'} = null;
		}

		return $entry;
	}
}
