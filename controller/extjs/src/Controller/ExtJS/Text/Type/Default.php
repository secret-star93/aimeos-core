<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Controller
 * @subpackage ExtJS
 */


/**
 * ExtJS text type controller for admin interfaces.
 *
 * @package Controller
 * @subpackage ExtJS
 */
class Controller_ExtJS_Text_Type_Default
	extends Controller_ExtJS_Abstract
	implements Controller_ExtJS_Common_Interface
{
	private $_manager = null;


	/**
	 * Initializes the text type controller.
	 *
	 * @param MShop_Context_Item_Interface $context MShop context object
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		parent::__construct( $context, 'Text_Type' );

		$manager = MShop_Text_Manager_Factory::createManager( $context );
		$this->_manager = $manager->getSubManager( 'type' );
	}


	/**
	 * Creates a new text type item or updates an existing one or a list thereof.
	 *
	 * @param stdClass $params Associative array containing the text item properties
	 */
	public function saveItems( stdClass $params )
	{
		$this->_checkParams( $params, array( 'site', 'items' ) );
		$this->_setLocale( $params->site );

		$ids = array();
		$items = ( !is_array( $params->items ) ? array( $params->items ) : $params->items );

		foreach( $items as $entry )
		{
			$item = $this->_createItem( $entry );
			$this->_manager->saveItem( $item );

			$ids[] = $item->getId();
		}

		$search = $this->_manager->createSearch();
		$search->setConditions( $search->compare( '==', 'text.type.id', $ids ) );
		$search->setSlice( 0, count( $ids ) );
		$items = $this->_toArray( $this->_manager->searchItems( $search ) );

		return array(
			'items' => ( !is_array( $params->items ) ? reset( $items ) : $items ),
			'success' => true,
		);
	}


	/**
	 * Creates a new text type item and sets the properties from the given object.
	 *
	 * @param stdClass $entry Object with public properties using the "text.type" prefix
	 * @return MShop_Common_Item_Type_Interface Common type item
	 */
	protected function _createItem( stdClass $entry )
	{
		$item = $this->_manager->createItem();

		foreach( $entry as $name => $value )
		{
			switch( $name )
			{
				case 'text.type.id': $item->setId( $value ); break;
				case 'text.type.code': $item->setCode( $value ); break;
				case 'text.type.domain': $item->setDomain( $value ); break;
				case 'text.type.label': $item->setLabel( $value ); break;
				case 'text.type.status': $item->setStatus( $value ); break;
			}
		}

		return $item;
	}


	/**
	 * Returns the manager the controller is using.
	 *
	 * @return MShop_Common_Manager_Interface Manager object
	 */
	protected function _getManager()
	{
		return $this->_manager;
	}
}
