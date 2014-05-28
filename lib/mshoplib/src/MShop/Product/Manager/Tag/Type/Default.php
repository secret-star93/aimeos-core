<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package MShop
 * @subpackage Product
 */


/**
 * Default product tag type manager for creating and handling product tag type items.
 * @package MShop
 * @subpackage Product
 */
class MShop_Product_Manager_Tag_Type_Default
	extends MShop_Common_Manager_Type_Abstract
	implements MShop_Product_Manager_Tag_Type_Interface
{
	private $_searchConfig = array(
		'product.tag.type.id' => array(
			'code' => 'product.tag.type.id',
			'internalcode' => 'mprotaty."id"',
			'internaldeps' => array( 'LEFT JOIN "mshop_product_tag_type" AS mprotaty ON ( mprota."typeid" = mprotaty."id" )' ),
			'label' => 'Product tag type ID',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'product.tag.type.siteid' => array(
			'code' => 'product.tag.type.siteid',
			'internalcode' => 'mprotaty."siteid"',
			'label' => 'Product tag type site ID',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'product.tag.type.code' => array(
			'code' => 'product.tag.type.code',
			'internalcode' => 'mprotaty."code"',
			'label' => 'Product tag type code',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'product.tag.type.domain' => array(
			'code' => 'product.tag.type.domain',
			'internalcode' => 'mprotaty."domain"',
			'label' => 'Product tag type domain',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'product.tag.type.label' => array(
			'code' => 'product.tag.type.label',
			'internalcode' => 'mprotaty."label"',
			'label' => 'Product tag type label',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'product.tag.type.status' => array(
			'code' => 'product.tag.type.status',
			'internalcode' => 'mprotaty."status"',
			'label' => 'Product tag type status',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
		),
		'product.tag.type.mtime'=> array(
			'code'=>'product.tag.type.mtime',
			'internalcode'=>'mprotaty."mtime"',
			'label'=>'Product tag type modification date',
			'type'=> 'datetime',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
		'product.tag.type.ctime'=> array(
			'code'=>'product.tag.type.ctime',
			'internalcode'=>'mprotaty."ctime"',
			'label'=>'Product tag type creation date/time',
			'type'=> 'datetime',
			'internaltype'=> MW_DB_Statement_Abstract::PARAM_STR,
		),
		'product.tag.type.editor'=> array(
			'code'=>'product.tag.type.editor',
			'internalcode'=>'mprotaty."editor"',
			'label'=>'Product tag type editor',
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
		$this->_setResourceName( 'db-product' );
	}


	/**
	 * Removes old entries from the storage.
	 *
	 * @param array $siteids List of IDs for sites whose entries should be deleted
	 */
	public function cleanup( array $siteids )
	{
		$path = 'classes/product/manager/tag/type/submanagers';
		foreach( $this->_getContext()->getConfig()->get( $path, array() ) as $domain ) {
			$this->getSubManager( $domain )->cleanup( $siteids );
		}

		$this->_cleanup( $siteids, 'mshop/product/manager/tag/type/default/item/delete' );
	}


	/**
	 * Returns the attributes that can be used for searching.
	 *
	 * @param boolean $withsub Return also attributes of sub-managers if true
	 * @return array List of attribute items implementing MW_Common_Criteria_Attribute_Interface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		$list = parent::getSearchAttributes();

		if( $withsub === true )
		{
			$context = $this->_getContext();

			$path = 'classes/product/manager/tag/type/submanagers';
			foreach( $context->getConfig()->get($path, array() ) as $domain ) {
				$list = array_merge( $list, $this->getSubManager( $domain )->getSearchAttributes() );
			}
		}

		return $list;
	}


	/**
	 * Returns a new manager for product type extensions.
	 *
	 * @param string $manager Name of the sub manager type in lower case
	 * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
	 * @return mixed Manager for different extensions, e.g types, lists etc.
	 */
	public function getSubManager( $manager, $name = null )
	{
		/** classes/product/manager/tag/type/name
		 * Class name of the used product tag type manager implementation
		 *
		 * Each default product tag type manager can be replaced by an alternative imlementation.
		 * To use this implementation, you have to set the last part of the class
		 * name as configuration value so the manager factory knows which class it
		 * has to instantiate.
		 *
		 * For example, if the name of the default class is
		 *
		 *  MShop_Product_Manager_List_Type_Default
		 *
		 * and you want to replace it with your own version named
		 *
		 *  MShop_Product_Manager_List_Type_Mytype
		 *
		 * then you have to set the this configuration option:
		 *
		 *  classes/product/manager/tag/type/name = Mytype
		 *
		 * The value is the last part of your own class name and it's case sensitive,
		 * so take care that the configuration value is exactly named like the last
		 * part of the class name.
		 *
		 * The allowed characters of the class name are A-Z, a-z and 0-9. No other
		 * characters are possible! You should always start the last part of the class
		 * name with an upper case character and continue only with lower case characters
		 * or numbers. Avoid chamel case names like "MyType"!
		 *
		 * @param string Last part of the class name
		 * @since 2014.03
		 * @category Developer
		 */

		/** mshop/product/manager/tag/type/decorators/excludes
		 * Excludes decorators added by the "common" option from the product tag type manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "mshop/common/manager/decorators/default" before they are wrapped
		 * around the product tag type manager.
		 *
		 *  mshop/product/manager/tag/type/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("MShop_Common_Manager_Decorator_*") added via
		 * "mshop/common/manager/decorators/default" for the product tag type manager.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/product/manager/tag/type/decorators/global
		 * @see mshop/product/manager/tag/type/decorators/local
		 */

		/** mshop/product/manager/tag/type/decorators/global
		 * Adds a list of globally available decorators only to the product tag type manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("MShop_Common_Manager_Decorator_*") around the product tag type manager.
		 *
		 *  mshop/product/manager/tag/type/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "MShop_Common_Manager_Decorator_Decorator1" only to the product controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/product/manager/tag/type/decorators/excludes
		 * @see mshop/product/manager/tag/type/decorators/local
		 */

		/** mshop/product/manager/tag/type/decorators/local
		 * Adds a list of local decorators only to the product tag type manager
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("MShop_Common_Manager_Decorator_*") around the product tag type manager.
		 *
		 *  mshop/product/manager/tag/type/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "MShop_Common_Manager_Decorator_Decorator2" only to the product
		 * controller.
		 *
		 * @param array List of decorator names
		 * @since 2014.03
		 * @category Developer
		 * @see mshop/common/manager/decorators/default
		 * @see mshop/product/manager/tag/type/decorators/excludes
		 * @see mshop/product/manager/tag/type/decorators/global
		 */

		return $this->_getSubManager( 'product', 'tag/type/' . $manager, $name );
	}


	/**
	 * Returns the config path for retrieving the configuration values.
	 *
	 * @return string Configuration path
	 */
	protected function _getConfigPath()
	{
		return 'mshop/product/manager/tag/type/default/item/';
	}


	/**
	 * Returns the search configuration for searching items.
	 *
	 * @return array Associative list of search keys and search definitions
	 */
	protected function _getSearchConfig()
	{
		return $this->_searchConfig;
	}
}