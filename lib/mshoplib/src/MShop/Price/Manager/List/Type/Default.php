<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package MShop
 * @subpackage Price
 */


/**
 * Default price list type manager for creating and handling price list type items.
 * @package MShop
 * @subpackage Price
 */
class MShop_Price_Manager_List_Type_Default
	extends MShop_Common_Manager_Type_Default
	implements MShop_Price_Manager_List_Type_Interface
{
	private $_searchConfig = array(
		'price.list.type.id' => array(
			'code' => 'price.list.type.id',
			'internalcode' => 'mprility."id"',
			'internaldeps' => array( 'LEFT JOIN "mshop_price_list_type" AS mprility ON ( mprili."typeid" = mprility."id" )' ),
			'label' => 'Price list type Id',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'price.list.type.siteid' => array(
			'code' => 'price.list.type.siteid',
			'internalcode' => 'mprility."siteid"',
			'label' => 'Price list type site Id',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
			'public' => false,
		),
		'price.list.type.code' => array(
			'code' => 'price.list.type.code',
			'internalcode' => 'mprility."code"',
			'label' => 'Price list type code',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'price.list.type.domain' => array(
			'code' => 'price.list.type.domain',
			'internalcode' => 'mprility."domain"',
			'label' => 'Price list type domain',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'price.list.type.label' => array(
			'label' => 'Price list type label',
			'code' => 'price.list.type.label',
			'internalcode' => 'mprility."label"',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'price.list.type.status' => array(
			'label' => 'Price list type status',
			'code' => 'price.list.type.status',
			'internalcode' => 'mprility."status"',
			'type' => 'integer',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_INT,
		),
		'price.list.type.ctime' => array(
			'code' => 'price.list.type.ctime',
			'internalcode' => 'mprility."ctime"',
			'label' => 'Price list type create date/time',
			'type' => 'datetime',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'price.list.type.mtime' => array(
			'code' => 'price.list.type.mtime',
			'internalcode' => 'mprility."mtime"',
			'label' => 'Price list type modification date/time',
			'type' => 'datetime',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
		'price.list.type.editor' => array(
			'code' => 'price.list.type.editor',
			'internalcode' => 'mprility."editor"',
			'label' => 'Price list type editor',
			'type' => 'string',
			'internaltype' => MW_DB_Statement_Abstract::PARAM_STR,
		),
	);


	/**
	 * Creates the type manager using the given context object.
	 *
	 * @param MShop_Context_Item_Interface $context Context object with required objects
	 * @param array $config Associative list of SQL statements
	 * @param array $searchConfig Associative list of search configuration
	 *
	 * @throws MShop_Common_Exception if no configuration is available
	 */
	public function __construct( MShop_Context_Item_Interface $context )
	{
		$config = $context->getConfig();
		$confpath = 'mshop/price/manager/list/type/default/item/';
		$conf = array(
			'insert' => $config->get( $confpath . 'insert' ),
			'update' => $config->get( $confpath . 'update' ),
			'delete' => $config->get( $confpath . 'delete' ),
			'search' => $config->get( $confpath . 'search' ),
			'count' => $config->get( $confpath . 'count' ),
			'newid' => $config->get( $confpath . 'newid' ),
		);

		parent::__construct( $context, $conf, $this->_searchConfig );
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
			$context = $this->_getContext();

			$path = 'classes/price/manager/list/type/submanagers';
			foreach( $context->getConfig()->get($path, array() ) as $domain ) {
				$list = array_merge( $list, $this->getSubManager( $domain )->getSearchAttributes() );
			}
		}

		return $list;
	}
}