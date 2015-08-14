<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MShop
 * @subpackage Customer
 */


/**
 * Default implementation of the customer group manager
 *
 * @package MShop
 * @subpackage Customer
 */
class MShop_Customer_Manager_Group_Default
	extends MShop_Common_Manager_Abstract
	implements MShop_Customer_Manager_Group_Interface
{
	private $_searchConfig = array(
		'customer.group.id' => array(
			'code' => 'customer.group.id',
			'internalcode' => 'mcusgr."id"',
			'label' => 'Customer group ID',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
		),
		'customer.group.siteid' => array(
			'code' => 'customer.group.siteid',
			'internalcode' => 'mcusgr."siteid"',
			'label' => 'Customer group site ID',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'customer.group.code' => array(
			'code' => 'customer.group.code',
			'internalcode' => 'mcusgr."code"',
			'label' => 'Customer group code',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'customer.group.label' => array(
			'code' => 'customer.group.label',
			'internalcode' => 'mcusgr."label"',
			'label' => 'Customer group label',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'customer.group.ctime'=> array(
			'code' => 'customer.group.ctime',
			'internalcode' => 'mcusgr."ctime"',
			'label' => 'Customer group creation time',
			'type' => 'datetime',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'customer.group.mtime'=> array(
			'code' => 'customer.group.mtime',
			'internalcode' => 'mcusgr."mtime"',
			'label' => 'Customer group modification time',
			'type' => 'datetime',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'customer.group.editor'=> array(
			'code'=>'customer.group.editor',
			'internalcode'=>'mcusgr."editor"',
			'label'=>'Customer group editor',
			'type'=> 'string',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
	);


	/**
	 * Initializes the object
	 *
	 * @param MShop_Context_Item_Interface $context Context object
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		parent::__construct( $context );
		$this->_setResourceName( 'db-customer' );
	}


	/**
	 * Removes old entries from the database
	 *
	 * @param integer[] $siteids List of IDs for sites whose entries should be deleted
	 */
	public function cleanup( array $siteids )
	{
		$path = 'classes/customer/manager/group/submanagers';
		foreach( $this->_getContext()->getConfig()->get( $path, array() ) as $domain ) {
			$this->getSubManager( $domain )->cleanup( $siteids );
		}

		$this->_cleanup( $siteids, 'mshop/customer/manager/group/default/item/delete' );
	}


	/**
	 * Instantiates a new customer group item object
	 *
	 * @return MShop_Customer_Item_Group_Interface
	 */
	public function createItem()
	{
		$values = array( 'siteid'=> $this->_getContext()->getLocale()->getSiteId() );

		return $this->_createItem( $values );
	}


	/**
	 * Returns the attributes that can be used for searching
	 *
	 * @param boolean $withsub Return attributes of sub-managers too if true
	 * @return array List of attribute items implementing MW_Common_Criteria_Attribute_Interface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		/** classes/customer/manager/group/submanagers
		 * List of manager names that can be instantiated by the customer group manager
		 *
		 * Managers provide a generic interface to the underlying storage.
		 * Each manager has or can have sub-managers caring about particular
		 * aspects. Each of these sub-managers can be instantiated by its
		 * parent manager using the getSubManager() method.
		 *
		 * The search keys from sub-managers can be normally used in the
		 * manager as well. It allows you to search for items of the manager
		 * using the search keys of the sub-managers to further limit the
		 * retrieved list of items.
		 *
		 * @param array List of sub-manager names
		 * @since 2015.08
		 * @category Developer
		 */
		$path = 'classes/customer/manager/group/submanagers';

		return $this->_getSearchAttributes( $this->_searchConfig, $path, array(), $withsub );
	}


	/**
	 * Removes multiple items specified by their IDs
	 *
	 * @param array $ids List of IDs
	 */
	public function deleteItems( array $ids )
	{
		/** mshop/customer/manager/group/default/item/delete
		 * Deletes the items matched by the given IDs from the database
		 *
		 * Removes the records specified by the given IDs from the customer group
		 * database. The records must be from the site that is configured via the
		 * context item.
		 *
		 * The ":cond" placeholder is replaced by the name of the ID column and
		 * the given ID or list of IDs while the site ID is bound to the question
		 * mark.
		 *
		 * The SQL statement should conform to the ANSI standard to be
		 * compatible with most relational database systems. This also
		 * includes using double quotes for table and column names.
		 *
		 * @param string SQL statement for deleting items
		 * @since 2015.08
		 * @category Developer
		 * @see mshop/customer/manager/group/default/item/insert
		 * @see mshop/customer/manager/group/default/item/update
		 * @see mshop/customer/manager/group/default/item/newid
		 * @see mshop/customer/manager/group/default/item/search
		 * @see mshop/customer/manager/group/default/item/count
		 */
		$path = 'mshop/customer/manager/group/default/item/delete';
		$this->_deleteItems( $ids, $this->_getContext()->getConfig()->get( $path, $path ) );
	}


	/**
	 * Returns the customer group item object specificed by its ID
	 *
	 * @param integer $id Unique customer ID referencing an existing customer group
	 * @param string[] $ref List of domains to fetch list items and referenced items for
	 * @return MShop_Customer_Item_Group_Interface Returns the customer group item for the given ID
	 * @throws MShop_Exception If item couldn't be found
	 */
	public function getItem( $id, array $ref = array() )
	{
		return $this->_getItem( 'customer.group.id', $id, $ref );
	}


	/**
	 * Inserts a new or updates an existing customer group item
	 *
	 * @param MShop_Customer_Item_Group_Interface $item Customer group item
	 * @param boolean $fetch True if the new ID should be returned in the item
	 */
	public function saveItem( MShop_Common_Item_Interface $item, $fetch = true )
	{
		$iface = 'MShop_Customer_Item_Group_Interface';
		if( !( $item instanceof $iface ) ) {
			throw new MShop_Customer_Exception( sprintf( 'Object is not of required type "%1$s"', $iface ) );
		}

		if( !$item->isModified() ) { return; }

		$context = $this->_getContext();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->_getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$id = $item->getId();
			$date = date( 'Y-m-d H:i:s' );

			if( $id === null )
			{
				/** mshop/customer/manager/group/default/item/insert
				 * Inserts a new customer group record into the database table
				 *
				 * Items with no ID yet (i.e. the ID is NULL) will be created in
				 * the database and the newly created ID retrieved afterwards
				 * using the "newid" SQL statement.
				 *
				 * The SQL statement must be a string suitable for being used as
				 * prepared statement. It must include question marks for binding
				 * the values from the customer group item to the statement before
				 * they are sent to the database server. The number of question
				 * marks must be the same as the number of columns listed in the
				 * INSERT statement. The order of the columns must correspond to
				 * the order in the saveItems() method, so the correct values are
				 * bound to the columns.
				 *
				 * The SQL statement should conform to the ANSI standard to be
				 * compatible with most relational database systems. This also
				 * includes using double quotes for table and column names.
				 *
				 * @param string SQL statement for inserting records
				 * @since 2015.08
				 * @category Developer
				 * @see mshop/customer/manager/group/default/item/update
				 * @see mshop/customer/manager/group/default/item/newid
				 * @see mshop/customer/manager/group/default/item/delete
				 * @see mshop/customer/manager/group/default/item/search
				 * @see mshop/customer/manager/group/default/item/count
				 */
				$path = 'mshop/customer/manager/group/default/item/insert';
			}
			else
			{
				/** mshop/customer/manager/group/default/item/update
				 * Updates an existing customer group record in the database
				 *
				 * Items which already have an ID (i.e. the ID is not NULL) will
				 * be updated in the database.
				 *
				 * The SQL statement must be a string suitable for being used as
				 * prepared statement. It must include question marks for binding
				 * the values from the customer group item to the statement before
				 * they are sent to the database server. The order of the columns
				 * must correspond to the order in the saveItems() method, so the
				 * correct values are bound to the columns.
				 *
				 * The SQL statement should conform to the ANSI standard to be
				 * compatible with most relational database systems. This also
				 * includes using double quotes for table and column names.
				 *
				 * @param string SQL statement for updating records
				 * @since 2015.08
				 * @category Developer
				 * @see mshop/customer/manager/group/default/item/insert
				 * @see mshop/customer/manager/group/default/item/newid
				 * @see mshop/customer/manager/group/default/item/delete
				 * @see mshop/customer/manager/group/default/item/search
				 * @see mshop/customer/manager/group/default/item/count
				 */
				$path = 'mshop/customer/manager/group/default/item/update';
			}

			$stmt = $this->_getCachedStatement( $conn, $path );

			$stmt->bind( 1, $context->getLocale()->getSiteId(), MW_DB_Statement_Abstract::PARAM_INT );
			$stmt->bind( 2, $item->getCode() );
			$stmt->bind( 3, $item->getLabel() );
			$stmt->bind( 4, $date ); // mtime
			$stmt->bind( 5, $context->getEditor() );

			if( $id !== null ) {
				$stmt->bind( 6, $id, MW_DB_Statement_Abstract::PARAM_INT );
				$item->setId( $id );
			} else {
				$stmt->bind( 6, $date ); // ctime
			}

			$stmt->execute()->finish();

			if( $id === null && $fetch === true )
			{
				/** mshop/customer/manager/group/default/item/newid
				 * Retrieves the ID generated by the database when inserting a new record
				 *
				 * As soon as a new record is inserted into the database table,
				 * the database server generates a new and unique identifier for
				 * that record. This ID can be used for retrieving, updating and
				 * deleting that specific record from the table again.
				 *
				 * For MySQL:
				 *  SELECT LAST_INSERT_ID()
				 * For PostgreSQL:
				 *  SELECT currval('seq_mcus_id')
				 * For SQL Server:
				 *  SELECT SCOPE_IDENTITY()
				 * For Oracle:
				 *  SELECT "seq_mcus_id".CURRVAL FROM DUAL
				 *
				 * There's no way to retrive the new ID by a SQL statements that
				 * fits for most database servers as they implement their own
				 * specific way.
				 *
				 * @param string SQL statement for retrieving the last inserted record ID
				 * @since 2015.08
				 * @category Developer
				 * @see mshop/customer/manager/group/default/item/insert
				 * @see mshop/customer/manager/group/default/item/update
				 * @see mshop/customer/manager/group/default/item/delete
				 * @see mshop/customer/manager/group/default/item/search
				 * @see mshop/customer/manager/group/default/item/count
				 */
				$path = 'mshop/customer/manager/group/default/item/newid';
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
	 * Returns the item objects matched by the given search criteria.
	 *
	 * @param MW_Common_Criteria_Interface $search Search criteria object
	 * @param array $ref List of domain items that should be fetched too
	 * @param integer &$total Number of items that are available in total
	 * @return array List of items implementing MShop_Customer_Item_Group_Interface
	 * @throws MShop_Exception If retrieving items failed
	 */
	public function searchItems( MW_Common_Criteria_Interface $search, array $ref = array(), &$total = null )
	{
		$map = array();
		$context = $this->_getContext();

		$dbm = $context->getDatabaseManager();
		$dbname = $this->_getResourceName();
		$conn = $dbm->acquire( $dbname );

		try
		{
			$required = array( 'customer.group' );
			$level = MShop_Locale_Manager_Abstract::SITE_ALL;

			/** mshop/customer/manager/group/default/item/search
			 * Retrieves the records matched by the given criteria in the database
			 *
			 * Fetches the records matched by the given criteria from the customer
			 * database. The records must be from one of the sites that are
			 * configured via the context item. If the current site is part of
			 * a tree of sites, the SELECT statement can retrieve all records
			 * from the current site and the complete sub-tree of sites.
			 *
			 * As the records can normally be limited by criteria from sub-managers,
			 * their tables must be joined in the SQL context. This is done by
			 * using the "internaldeps" property from the definition of the ID
			 * column of the sub-managers. These internal dependencies specify
			 * the JOIN between the tables and the used columns for joining. The
			 * ":joins" placeholder is then replaced by the JOIN strings from
			 * the sub-managers.
			 *
			 * To limit the records matched, conditions can be added to the given
			 * criteria object. It can contain comparisons like column names that
			 * must match specific values which can be combined by AND, OR or NOT
			 * operators. The resulting string of SQL conditions replaces the
			 * ":cond" placeholder before the statement is sent to the database
			 * server.
			 *
			 * If the records that are retrieved should be ordered by one or more
			 * columns, the generated string of column / sort direction pairs
			 * replaces the ":order" placeholder. In case no ordering is required,
			 * the complete ORDER BY part including the "\/*-orderby*\/...\/*orderby-*\/"
			 * markers is removed to speed up retrieving the records. Columns of
			 * sub-managers can also be used for ordering the result set but then
			 * no index can be used.
			 *
			 * The number of returned records can be limited and can start at any
			 * number between the begining and the end of the result set. For that
			 * the ":size" and ":start" placeholders are replaced by the
			 * corresponding values from the criteria object. The default values
			 * are 0 for the start and 100 for the size value.
			 *
			 * The SQL statement should conform to the ANSI standard to be
			 * compatible with most relational database systems. This also
			 * includes using double quotes for table and column names.
			 *
			 * @param string SQL statement for searching items
			 * @since 2015.08
			 * @category Developer
			 * @see mshop/customer/manager/group/default/item/insert
			 * @see mshop/customer/manager/group/default/item/update
			 * @see mshop/customer/manager/group/default/item/newid
			 * @see mshop/customer/manager/group/default/item/delete
			 * @see mshop/customer/manager/group/default/item/count
			 */
			$cfgPathSearch = 'mshop/customer/manager/group/default/item/search';

			/** mshop/customer/manager/group/default/item/count
			 * Counts the number of records matched by the given criteria in the database
			 *
			 * Counts all records matched by the given criteria from the customer
			 * database. The records must be from one of the sites that are
			 * configured via the context item. If the current site is part of
			 * a tree of sites, the statement can count all records from the
			 * current site and the complete sub-tree of sites.
			 *
			 * As the records can normally be limited by criteria from sub-managers,
			 * their tables must be joined in the SQL context. This is done by
			 * using the "internaldeps" property from the definition of the ID
			 * column of the sub-managers. These internal dependencies specify
			 * the JOIN between the tables and the used columns for joining. The
			 * ":joins" placeholder is then replaced by the JOIN strings from
			 * the sub-managers.
			 *
			 * To limit the records matched, conditions can be added to the given
			 * criteria object. It can contain comparisons like column names that
			 * must match specific values which can be combined by AND, OR or NOT
			 * operators. The resulting string of SQL conditions replaces the
			 * ":cond" placeholder before the statement is sent to the database
			 * server.
			 *
			 * Both, the strings for ":joins" and for ":cond" are the same as for
			 * the "search" SQL statement.
			 *
			 * Contrary to the "search" statement, it doesn't return any records
			 * but instead the number of records that have been found. As counting
			 * thousands of records can be a long running task, the maximum number
			 * of counted records is limited for performance reasons.
			 *
			 * The SQL statement should conform to the ANSI standard to be
			 * compatible with most relational database systems. This also
			 * includes using double quotes for table and column names.
			 *
			 * @param string SQL statement for counting items
			 * @since 2015.08
			 * @category Developer
			 * @see mshop/customer/manager/group/default/item/insert
			 * @see mshop/customer/manager/group/default/item/update
			 * @see mshop/customer/manager/group/default/item/newid
			 * @see mshop/customer/manager/group/default/item/delete
			 * @see mshop/customer/manager/group/default/item/search
			 */
			$cfgPathCount = 'mshop/customer/manager/group/default/item/count';

			$results = $this->_searchItems( $conn, $search, $cfgPathSearch, $cfgPathCount, $required, $total, $level );

			while( ( $row = $results->fetch() ) !== false ) {
				$map[$row['id']] = $this->_createItem( $row );
			}

			$dbm->release( $conn, $dbname );
		}
		catch( Exception $e )
		{
			$dbm->release( $conn, $dbname );
			throw $e;
		}

		return $map;
	}


	/**
	 * Returns a new manager for customer group extensions
	 *
	 * @param string $manager Name of the sub manager type in lower case
	 * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
	 * @return MShop_Common_Manager_Interface Manager for different extensions
	 */
	public function getSubManager( $manager, $name = null )
	{
		/** classes/customer/manager/group/name
		 * Class name of the used customer group manager implementation
		 *
		 * Each default customer group manager can be replaced by an alternative
		 * imlementation. To use this implementation, you have to set the last
		 * part of the class name as configuration value so the manager factory
		 * knows which class it has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  MShop_Customer_Manager_Group_Default
		 *
		 * and you want to replace it with your own version named
		 *
		 *  MShop_Customer_Manager_Group_Mygroup
		 *
		 * then you have to set the this configuration option:
		 *
		 *  classes/customer/manager/group/name = Mygroup
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyGroup"!
		 *
		 * @param string Last part of the class name
		 * @since 2015.08
		 * @category Developer
		 */

		/** mshop/customer/manager/group/decorators/excludes
		 * Excludes decorators added by the "common" option from the customer group manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "mshop/common/manager/group/decorators/default" before they are wrapped
		 * around the customer group manager.
		 *
		 *  mshop/customer/manager/group/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("MShop_Common_Manager_Decorator_*") added via
		 * "mshop/common/manager/decorators/default" for the customer group manager.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/customer/manager/group/decorators/global
		 * @see mshop/customer/manager/group/decorators/local
		 */

		/** mshop/customer/manager/group/decorators/global
		 * Adds a list of globally available decorators only to the customer group manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("MShop_Common_Manager_Decorator_*") around the customer manager.
		 *
		 *  mshop/customer/manager/group/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "MShop_Common_Manager_Decorator_Decorator1" only to the customer
		 * group manager.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/customer/manager/group/decorators/excludes
		 * @see mshop/customer/manager/group/decorators/local
		 */

		/** mshop/customer/manager/group/decorators/local
		 * Adds a list of local decorators only to the customer group manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("MShop_Common_Manager_Decorator_*") around the customer group manager.
		 *
		 *  mshop/customer/manager/group/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "MShop_Common_Manager_Decorator_Decorator2" only to the customer
		 * group manager.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/customer/manager/group/decorators/excludes
		 * @see mshop/customer/manager/group/decorators/global
		 */

		return $this->_getSubManager( 'customer/group', $manager, $name );
	}


	/**
	 * Creates a new customer group item
	 *
	 * @param array $values List of attributes for customer group item
	 * @return MShop_Customer_Item_Group_Interface New customer group item
	 */
	protected function _createItem( array $values = array() )
	{
		return new MShop_Customer_Item_Group_Default( $values );
	}
}
