<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Controller
 * @subpackage ExtJS
 */


/**
 * ExtJS admin job controller for admin interfaces.
 *
 * @package Controller
 * @subpackage ExtJS
 */
class Controller_ExtJS_Admin_Job_Default
	extends Controller_ExtJS_Abstract
	implements Controller_ExtJS_Common_Interface
{
	private $_manager = null;


	/**
	 * Initializes the job controller.
	 *
	 * @param MShop_Context_Item_Interface $context MShop context object
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		parent::__construct( $context, 'Admin_Job' );

		$this->_manager = MAdmin_Job_Manager_Factory::createManager( $context );
	}


	/**
	 * Creates a new job item or updates existing one or a list thereof.
	 *
	 * @param stdClass $params Associative array containing the attribute properties
	 * @return array Associative list with items and success value
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
		$search->setConditions( $search->compare( '==', 'job.id', $ids ) );
		$search->setSlice( 0, count( $ids ) );
		$items = $this->_toArray( $this->_manager->searchItems( $search ) );

		return array(
			'items' => ( !is_array( $params->items ) ? reset( $items ) : $items ),
			'success' => true,
		);
	}


	/**
	 * Creates a new job item and sets the properties from the given object.
	 *
	 * @param stdClass $entry Object with public properties using the "job" prefix
	 * @return MAdmin_Job_Item_Interface Job item
	 */
	protected function _createItem( stdClass $entry )
	{
		$item = $this->_manager->createItem();

		foreach( $entry as $name => $value )
		{
			switch( $name )
			{
				case 'job.id': $item->setId( $value ); break;
				case 'job.label': $item->setLabel( $value ); break;
				case 'job.method': $item->setMethod( $value ); break;
				case 'job.status': $item->setStatus( $value ); break;
				case 'job.parameter':
					if( is_string( $value ) ) {
						$item->setParameter( json_decode( $value, true ) );
					} else {
						$item->setParameter( (array) $value );
					}
					break;
				case 'job.result':
					if( is_string( $value ) ) {
						$item->setResult( json_decode( $value, true ) );
					} else {
						$item->setResult( (array) $value );
					}
					break;
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
