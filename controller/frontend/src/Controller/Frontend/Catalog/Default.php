<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Controller
 * @subpackage Frontend
 */


/**
 * Default implementation of the catalog frontend controller.
 *
 * @package Controller
 * @subpackage Frontend
 */
class Controller_Frontend_Catalog_Default
	extends Controller_Frontend_Abstract
	implements Controller_Frontend_Catalog_Interface
{
	/**
	 * Returns the default catalog filter
	 *
	 * @return MW_Common_Criteria_Interface Criteria object for filtering
	 */
	public function createCatalogFilterDefault()
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog' )->createSearch();
	}


	/**
	 * Returns the list of categries that are in the path to the root node including the one specified by its ID.
	 *
	 * @param integer $id Category ID to start from, null for root node
	 * @param string[] $domains Domain names of items that are associated with the categories and that should be fetched too
	 * @return array Associative list of items implementing MShop_Catalog_Item_Interface with their IDs as keys
	 */
	public function getCatalogPath( $id, array $domains = array( 'text', 'media' ) )
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog' )->getPath( $id, $domains );
	}


	/**
	 * Returns the hierarchical catalog tree starting from the given ID.
	 *
	 * @param integer|null $id Category ID to start from, null for root node
	 * @param string[] $domains Domain names of items that are associated with the categories and that should be fetched too
	 * @param integer $level Constant from MW_Tree_Manager_Abstract for the depth of the returned tree, LEVEL_ONE for
	 * 	specific node only, LEVEL_LIST for node and all direct child nodes, LEVEL_TREE for the whole tree
	 * @param MW_Common_Criteria_Interface|null $search Optional criteria object with conditions
	 * @return MShop_Catalog_Item_Interface Catalog node, maybe with children depending on the level constant
	 */
	public function getCatalogTree( $id = null, array $domains = array( 'text', 'media' ),
		$level = MW_Tree_Manager_Abstract::LEVEL_TREE, MW_Common_Criteria_Interface $search = null )
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog' )->getTree( $id, $domains, $level, $search );
	}


	/**
	 * Returns the aggregated count of products for the given key.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @param string $key Search key to aggregate for, e.g. "catalog.index.attribute.id"
	 * @return array Associative list of key values as key and the product count for this key as value
	 */
	public function aggregate( MW_Common_Criteria_Interface $filter, $key )
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog/index' )->aggregate( $filter, $key );
	}


	/**
	 * Returns the default product filter.
	 *
	 * @param string|null $sort Sortation of the product list like "name", "code", "price" and "position", null for no sortation
	 * @param string $direction Sort direction of the product list ("+", "-")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype Type of the product list, e.g. default, promotion, etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function createProductFilterDefault( $sort = null, $direction = '+', $start = 0, $size = 100, $listtype = 'default' )
	{
		$sortations = array();
		$context = $this->_getContext();

		$search = MShop_Factory::createManager( $context, 'catalog/index' )->createSearch( true );
		$expr = array( $search->compare( '!=', 'catalog.index.catalog.id', null ) );

		switch( $sort )
		{
			case 'code':
				$sortations[] = $search->sort( $direction, 'product.code' );
				break;

			case 'name':
				$langid = $context->getLocale()->getLanguageId();

				$cmpfunc = $search->createFunction( 'catalog.index.text.value', array( $listtype, $langid, 'name', 'product' ) );
				$expr[] = $search->compare( '>=', $cmpfunc, '' );

				$sortfunc = $search->createFunction( 'sort:catalog.index.text.value', array( $listtype, $langid, 'name' ) );
				$sortations[] = $search->sort( $direction, $sortfunc );
				break;

			case 'price':
				$currencyid = $context->getLocale()->getCurrencyId();

				$cmpfunc = $search->createFunction( 'catalog.index.price.value', array( $listtype, $currencyid, 'default' ) );
				$expr[] = $search->compare( '>=', $cmpfunc, '0.00' );

				$sortfunc = $search->createFunction( 'sort:catalog.index.price.value', array( $listtype, $currencyid, 'default' ) );
				$sortations[] = $search->sort( $direction, $sortfunc );
				break;
		}

		$expr[] = $search->getConditions();

		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSortations( $sortations );
		$search->setSlice( $start, $size );

		return $search;
	}


	/**
	 * Returns a product filter for the given category ID.
	 *
	 * @param integer $catid ID of the category to get the product list from
	 * @param string|null $sort Sortation of the product list like "name", "code", "price" and "position", null for no sortation
	 * @param string $direction Sort direction of the product list ("+", "-")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype Type of the product list, e.g. default, promotion, etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function createProductFilterByCategory( $catid, $sort = null, $direction = '+', $start = 0, $size = 100, $listtype = 'default' )
	{
		$search = $this->createProductFilterDefault( $sort, $direction, $start, $size, $listtype );
		$expr = array( $search->compare( '==', 'catalog.index.catalog.id', $catid ) );

		if( $sort === 'relevance' )
		{
			$cmpfunc = $search->createFunction( 'catalog.index.catalog.position', array( $listtype, $catid ) );
			$expr[] = $search->compare( '>=', $cmpfunc, 0 );

			$sortfunc = $search->createFunction( 'sort:catalog.index.catalog.position', array( $listtype, $catid ) );
			$search->setSortations( array( $search->sort( $direction, $sortfunc ) ) );
		}

		$expr[] = $search->getConditions();
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $search;
	}


	/**
	 * Returns product filter for the given search string.
	 *
	 * @param string $input Search string entered by the user
	 * @param string|null $sort Sortation of the product list like "name", "price" and "relevance", null for no sortation
	 * @param string $direction Sort direction of the product list ("+", "-")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function createProductFilterByText( $input, $sort = null, $direction = '+', $start = 0, $size = 100, $listtype = 'default' )
	{
		$langid = $this->_getContext()->getLocale()->getLanguageId();
		$search = $this->createProductFilterDefault( $sort, $direction, $start, $size, $listtype );
		$expr = array( $search->compare( '>', $search->createFunction( 'catalog.index.text.relevance', array( $listtype, $langid, $input ) ), 0 ) );

		// we don't need to sort by 'sort:catalog.index.text.relevance' because it's a boolean match (relevance is either 0 or 1)

		$expr[] = $search->getConditions();
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $search;
	}


	/**
	 * Returns the given search filter with the conditions attached for filtering texts.
	 *
	 * @param MW_Common_Criteria_Interface $search Criteria object used for product search
	 * @param string $catid Selected category by the user
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function addProductFilterCategory( MW_Common_Criteria_Interface $search, $catid )
	{
		$expr = array( $search->compare( '==', 'catalog.index.catalog.id', $catid ) );

		$expr[] = $search->getConditions();
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $search;
	}


	/**
	 * Returns the given search filter with the conditions attached for filtering texts.
	 *
	 * @param MW_Common_Criteria_Interface $search Criteria object used for product search
	 * @param string $input Search string entered by the user
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function addProductFilterText( MW_Common_Criteria_Interface $search, $input, $listtype = 'default' )
	{
		$langid = $this->_getContext()->getLocale()->getLanguageId();
		$expr = array( $search->compare( '>', $search->createFunction( 'catalog.index.text.relevance', array( $listtype, $langid, $input ) ), 0 ) );

		$expr[] = $search->getConditions();
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $search;
	}


	/**
	 * Returns a product list filtered by the given criteria object.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @param integer &$total Parameter where the total number of found products will be stored in
	 * @param array $domains Domain names of items that are associated with the products and that should be fetched too
	 * @return array Ordered list of product items implementing MShop_Product_Item_Interface
	 */
	public function getProductList( MW_Common_Criteria_Interface $filter, &$total = null, array $domains = array( 'media', 'price', 'text' ) )
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog/index' )->searchItems( $filter, $domains, $total );
	}


	/**
	 * Returns text filter for the given search string.
	 *
	 * @param string $input Search string entered by the user
	 * @param string|null $sort Sortation of the product list like "name" and "relevance", null for no sortation
	 * @param string $direction Sort direction of the product list ("asc", "desc")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @param string $type Type of the text like "name", "short", "long", etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function createTextFilter( $input, $sort = null, $direction = '+', $start = 0, $size = 25, $listtype = 'default', $type = 'name' )
	{
		$locale = $this->_getContext()->getLocale();
		$langid = $locale->getLanguageId();

		$search = MShop_Factory::createManager( $this->_getContext(), 'catalog/index/text' )->createSearch( true );

		$expr = array(
			$search->compare( '>', $search->createFunction( 'catalog.index.text.relevance', array( $listtype, $langid, $input ) ), 0 ),
			$search->compare( '>', $search->createFunction( 'catalog.index.text.value', array( $listtype, $langid, $type, 'product' ) ), '' ),
		);

		$sortations = array();

		switch( $sort )
		{
			case 'name':
				$cmpfunc = $search->createFunction( 'catalog.index.text.value', array( $listtype, $langid, 'name', 'product' ) );
				$expr[] = $search->compare( '>=', $cmpfunc, '' );

				$sortfunc = $search->createFunction( 'sort:catalog.index.text.value', array( $listtype, $langid, 'name' ) );
				$sortations[] = $search->sort( $direction, $sortfunc );
				break;

			case 'relevance':
				// we don't need to sort by 'sort:catalog.index.text.relevance' because it's a boolean match (relevance is either 0 or 1)
		}

		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSortations( $sortations );
		$search->setSlice( $start, $size );

		return $search;
	}


	/**
	 * Returns an list of product text strings matched by the filter.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @return array Associative list of the product ID as key and the product text as value
	 */
	public function getTextList( MW_Common_Criteria_Interface $filter )
	{
		return MShop_Factory::createManager( $this->_getContext(), 'catalog/index/text' )->searchTexts( $filter );
	}
}
