<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package MShop
 * @subpackage Text
 */


/**
 * Default text manager implementation
 *
 * @package MShop
 * @subpackage Text
 */
class MShop_Text_Manager_Default
	extends MShop_Common_Manager_Abstract
	implements MShop_Text_Manager_Interface
{
	private $_searchConfig = array(
		'text.id'=> array(
			'code'=>'text.id',
			'internalcode'=>'mtex."id"',
			'label'=>'Text ID',
			'type'=> 'integer',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_INT,
		),
		'text.siteid'=> array(
			'code'=>'text.siteid',
			'internalcode'=>'mtex."siteid"',
			'label'=>'Text site ID',
			'type'=> 'integer',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'text.languageid' => array(
			'code'=>'text.languageid',
			'internalcode'=>'mtex."langid"',
			'label'=>'Text language code',
			'type'=> 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.typeid' => array(
			'code'=>'text.typeid',
			'internalcode'=>'mtex."typeid"',
			'label'=>'Text type ID',
			'type'=> 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'text.label' => array(
			'code'=>'text.label',
			'internalcode'=>'mtex."label"',
			'label'=>'Text label',
			'type'=> 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.domain' => array(
			'code'=>'text.domain',
			'internalcode'=>'mtex."domain"',
			'label'=>'Text domain',
			'type'=> 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.content' => array(
			'code'=>'text.content',
			'internalcode'=>'mtex."content"',
			'label'=>'Text content',
			'type'=> 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.status' => array(
			'code'=>'text.status',
			'internalcode'=>'mtex."status"',
			'label'=>'Text status',
			'type'=> 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
		),
		'text.ctime'=> array(
			'code'=>'text.ctime',
			'internalcode'=>'mtex."ctime"',
			'label'=>'Text create date/time',
			'type'=> 'datetime',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.mtime'=> array(
			'code'=>'text.mtime',
			'internalcode'=>'mtex."mtime"',
			'label'=>'Text modification date/time',
			'type'=> 'datetime',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
		'text.editor'=> array(
			'code'=>'text.editor',
			'internalcode'=>'mtex."editor"',
			'label'=>'Text editor',
			'type'=> 'string',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
	);


	/**
	 * Initializes the object.
	 *
	 * @param MShop_Context_Item_Interface $context Context object
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		parent::__construct( $context );
		$this->_setResourceName( 'db-text' );
	}


	/**
	 * Removes old entries from the storage.
	 *
	 * @param array $siteids List of IDs for sites whose entries should be deleted
	 */
	public function cleanup( array $siteids )
	{
		$path = 'classes/text/manager/submanagers';
		foreach( $this->_getContext()->getConfig()->get( $path, array( 'type', 'list' ) ) as $domain ) {
			$this->getSubManager( $domain )->cleanup( $siteids );
		}

		$this->_cleanup( $siteids, 'mshop/text/manager/default/item/delete' );
	}


	/**
	 * Creates new text item object.
	 *
	 * @return MShop_Text_Item_Interface New text item object
	 */
	public function createItem()
	{
		$values = array('siteid' => $this->_getContext()->getLocale()->getSiteId());
		return $this->_createItem($values);
	}


	/**
	 * Updates or adds a text item object.
	 * This method doesn't update the type string that belongs to the type ID
	 *
	 * @param MShop_Text_Item_Interface $item Text item which should be saved
	 * @param boolean $fetch True if the new ID should be returned in the item
	 */
	public function saveItem( MShop_Common_Item_Interface $item, $fetch = true )
	{
		$iface = 'MShop_Text_Item_Interface';
		if( !( $item instanceof $iface ) ) {
			throw new MShop_Text_Exception( sprintf( 'Object is not of required type "%1$s"', $iface ) );
		}

		if( !$item->isModified() ) { return; }

		$context = $this->_getContext();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->_getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$id = $item->getId();

			$path = 'mshop/text/manager/default/item/';
			$path .= ( $id === null ) ? 'insert' : 'update';

			$stmt = $this->_getCachedStatement( $conn, $path );
			$stmt->bind( 1, $context->getLocale()->getSiteId(), MW_DB_Statement_Abstract::PARAM_INT );
			$stmt->bind( 2, $item->getLanguageId() );
			$stmt->bind( 3, $item->getTypeId(), MW_DB_Statement_Abstract::PARAM_INT );
			$stmt->bind( 4, $item->getDomain() );
			$stmt->bind( 5, $item->getLabel() );
			$stmt->bind( 6, $item->getContent() );
			$stmt->bind( 7, $item->getStatus(), MW_DB_Statement_Abstract::PARAM_INT );
			$stmt->bind( 8, date('Y-m-d H:i:s', time()) );// mtime
			$stmt->bind( 9, $context->getEditor() );

			if ( $id !== null ) {
				$stmt->bind(10, $id, MW_DB_Statement_Abstract::PARAM_INT);
			} else {
				$stmt->bind(10, date('Y-m-d H:i:s', time()) );// ctime
			}

			$result = $stmt->execute()->finish();

			if ( $id === null && $fetch === true ) {
				$path = 'mshop/text/manager/default/item/newid';
				$item->setId( $this->_newId( $conn, $context->getConfig()->get( $path, $path ) ) );
			}

			$dbm->release( $conn, $dbname );
		}
		catch( Exception $e )
		{
			$dbm->release( $conn, $dbname );
			throw $e;
		}
	}


	/**
	 * Removes multiple items specified by ids in the array.
	 *
	 * @param array $ids List of IDs
	 */
	public function deleteItems( array $ids )
	{
		$path = 'mshop/text/manager/default/item/delete';
		$this->_deleteItems( $ids, $this->_getContext()->getConfig()->get( $path, $path ) );
	}


	/**
	 * Returns the text item object specified by the given ID.
	 *
	 * @param integer $id Id of the text item
	 * @param array $ref List of domains to fetch list items and referenced items for
	 * @return MShop_Text_Item_Interface Returns the text item of the given id
	 * @throws MShop_Exception If item couldn't be found
	 */
	public function getItem( $id, array $ref = array() )
	{
		return $this->_getItem( 'text.id', $id, $ref );
	}


	/**
	 * Returns the attributes that can be used for searching.
	 *
	 * @param boolean $withsub Return also attributes of sub-managers if true
	 * @return array List of attribute items implementing MW_Common_Criteria_Attribute_Interface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		$list = array();

		foreach( $this->_searchConfig as $key => $fields ) {
			$list[ $key ] = new MW_Common_Criteria_Attribute_Default( $fields );
		}

		if( $withsub === true )
		{
			$config = $this->_getContext()->getConfig();

			foreach( $config->get( 'classes/text/manager/submanagers', array( 'type', 'list' ) ) as $domain ) {
				$list = array_merge( $list, $this->getSubManager( $domain )->getSearchAttributes() );
			}
		}

		return $list;
	}


	/**
	 * Searches for all text items matching the given critera.
	 *
	 * @param MW_Common_Criteria_Interface $search Search object with search conditions
	 * @param array $ref List of domains to fetch list items and referenced items for
	 * @param integer &$total Number of items that are available in total
	 * @return array List of text items implementing MShop_Text_Item_Interface
	 */
	public function searchItems( MW_Common_Criteria_Interface $search, array $ref = array(), &$total = null )
	{
		$map = $typeIds = array();
		$context = $this->_getContext();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->_getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$level = MShop_Locale_Manager_Abstract::SITE_ALL;
			$cfgPathSearch = 'mshop/text/manager/default/item/search';
			$cfgPathCount =  'mshop/text/manager/default/item/count';
			$required = array( 'text' );

			$results = $this->_searchItems( $conn, $search, $cfgPathSearch, $cfgPathCount, $required, $total, $level );

			while( ( $row = $results->fetch() ) !== false )
			{
				$map[ $row['id'] ] = $row;
				$typeIds[ $row['typeid'] ] = null;
			}

			$dbm->release( $conn, $dbname );
		}
		catch( Exception $e )
		{
			$dbm->release( $conn, $dbname );
			throw $e;
		}

		if( !empty( $typeIds ) )
		{
			$typeManager = $this->getSubManager( 'type' );
			$typeSearch = $typeManager->createSearch();
			$typeSearch->setConditions( $typeSearch->compare( '==', 'text.type.id', array_keys( $typeIds ) ) );
			$typeSearch->setSlice( 0, $search->getSliceSize() );
			$typeItems = $typeManager->searchItems( $typeSearch );

			foreach( $map as $id => $row )
			{
				if( isset( $typeItems[ $row['typeid'] ] ) ) {
					$map[$id]['type'] = $typeItems[ $row['typeid'] ]->getCode();
				}
			}
		}

		return $this->_buildItems( $map, $ref, 'text' );
	}


	/**
	 * Returns a new manager for text extensions
	 *
	 * @param string $manager Name of the sub manager type in lower case
	 * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
	 * @return mixed Manager for different extensions, e.g types, lists etc.
	 */
	public function getSubManager( $manager, $name = null )
	{
		/** classes/text/manager/name
		 * Class name of the used text manager implementation
		 *
		 * Each default text manager can be replaced by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the manager factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  MShop_Text_Manager_Default
		 *
		 * and you want to replace it with your own version named
		 *
		 *  MShop_Text_Manager_Mytext
		 *
		 * then you have to set the this configuration option:
		 *
		 *  classes/text/manager/name = Mytext
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyText"!
		 *
		 * @param string Last part of the class name
		 * @since 2014.03
		 * @category Developer
		 */

		/** mshop/text/manager/decorators/excludes
		 * Excludes decorators added by the "common" option from the text manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "mshop/common/manager/decorators/default" before they are wrapped
		 * around the text manager.
		 *
		 *  mshop/text/manager/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("MShop_Common_Manager_Decorator_*") added via
		 * "mshop/common/manager/decorators/default" for the text manager.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/text/manager/decorators/global
		 * @see mshop/text/manager/decorators/local
		 */

		/** mshop/text/manager/decorators/global
		 * Adds a list of globally available decorators only to the text manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("MShop_Common_Manager_Decorator_*") around the text manager.
		 *
		 *  mshop/text/manager/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "MShop_Common_Manager_Decorator_Decorator1" only to the text controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/text/manager/decorators/excludes
		 * @see mshop/text/manager/decorators/local
		 */

		/** mshop/text/manager/decorators/local
		 * Adds a list of local decorators only to the text manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("MShop_Common_Manager_Decorator_*") around the text manager.
		 *
		 *  mshop/text/manager/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "MShop_Common_Manager_Decorator_Decorator2" only to the text
		 * controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/text/manager/decorators/excludes
		 * @see mshop/text/manager/decorators/global
		 */

		return $this->_getSubManager( 'text', $manager, $name );
	}


	/**
	 * Creates a search object.
	 *
	 * @param boolean $default If base criteria should be added
	 * @return MW_Common_Criteria_Interface Search criteria object
	 */
	public function createSearch( $default = false )
	{
		if( $default === true )
		{
			$object = $this->_createSearch( 'text' );
			$langid = $this->_getContext()->getLocale()->getLanguageId();

			if( $langid !== null )
			{
				$temp[] = $object->compare( '==', 'text.languageid', $langid );
				$temp[] = $object->compare( '==', 'text.languageid', null );

				$expr[] = $object->getConditions();
				$expr[] = $object->combine( '||', $temp );

				$object->setConditions( $object->combine( '&&', $expr ) );
			}

			return $object;
		}

		return parent::createSearch();
	}


	/**
	 * Creates a new text item instance.
	 *
	 * @param array $values Associative list of key/value pairs
	 * @param array $listitems List of items implementing MShop_Common_Item_List_Interface
	 * @param array $refItems List of items implementing MShop_Text_Item_Interface
	 * @return MShop_Text_Item_Interface New product item
	 */
	protected function _createItem( array $values = array(), array $listItems = array(), array $refItems = array() )
	{
		return new MShop_Text_Item_Default( $values, $listItems, $refItems );
	}
}
