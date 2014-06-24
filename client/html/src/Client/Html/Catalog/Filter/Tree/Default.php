<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of catalog tree filter section in HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Catalog_Filter_Tree_Default
	extends Client_Html_Abstract
{
	/** client/html/catalog/filter/tree/default/subparts
	 * List of HTML sub-clients rendered within the catalog filter tree section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $_subPartPath = 'client/html/catalog/filter/tree/default/subparts';
	private $_subPartNames = array();
	private $_tags = array();
	private $_expire;
	private $_cache;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->_setViewParams( $this->getView(), $tags, $expire );

		$navHelper = new MW_View_Helper_NavTree_Default( $view );
		$view->addHelper( 'navtree', $navHelper );

		$html = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->treeBody = $html;

		/** client/html/catalog/filter/tree/default/template-body
		 * Relative path to the HTML body template of the catalog filter tree client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the layouts directory (usually in client/html/layouts).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/catalog/filter/tree/default/template-header
		 */
		$tplconf = 'client/html/catalog/filter/tree/default/template-body';
		$default = 'catalog/filter/tree-body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string String including HTML tags for the header
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->_setViewParams( $this->getView(), $tags, $expire );

		$html = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->treeHeader = $html;

		/** client/html/catalog/filter/tree/default/template-header
		 * Relative path to the HTML header template of the catalog filter tree client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the HTML code that is inserted into the HTML page header
		 * of the rendered page in the frontend. The configuration string is the
		 * path to the template file relative to the layouts directory (usually
		 * in client/html/layouts).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page head
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/catalog/filter/tree/default/template-body
		 */
		$tplconf = 'client/html/catalog/filter/tree/default/template-header';
		$default = 'catalog/filter/tree-header-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return Client_Html_Interface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		return $this->_createSubClient( 'catalog/filter/tree/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function _getSubClientNames()
	{
		return $this->_getContext()->getConfig()->get( $this->_subPartPath, $this->_subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param MW_View_Interface $view The view object which generates the HTML output
	 * @return MW_View_Interface Modified view object
	 */
	protected function _setViewParams( MW_View_Interface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->_cache ) )
		{
			$context = $this->_getContext();
			$manager = MShop_Catalog_Manager_Factory::createManager( $context );

			/** client/html/catalog/filter/tree/startid
			 * The ID of the category node that should be the root of the displayed category tree
			 *
			 * If you want to display only a part of your category tree, you can
			 * configure the ID of the category node from which rendering the
			 * remaining sub-tree should start.
			 *
			 * In most cases you can set this value via the administration interface
			 * of the shop application. In that case you often can configure the
			 * start ID individually for each catalog filter.
			 *
			 * @param string Category ID
			 * @since 2014.03
			 * @category User
			 * @category Developer
			 * @see client/html/catalog/filter/tree/levels-always
			 * @see client/html/catalog/filter/tree/levels-only
			 */
			$startid = $view->config( 'client/html/catalog/filter/tree/startid', '' );
			$currentid = (string) $view->param( 'f-catalog-id', '' );
			$ref = array( 'text', 'media', 'attribute' );
			$catItems = array();


			if( $currentid != '' )
			{
				$catItems = $manager->getPath( $currentid );

				if( $startid != '' )
				{
					foreach( $catItems as $key => $item )
					{
						if( $key == $startid ) {
							break;
						}
						unset( $catItems[$key] );
					}
				}

				if( ( $node = reset( $catItems ) ) === false )
				{
					$msg = sprintf( 'Category with ID "%1$s" not below ID "%2$s"', $currentid, $startid );
					throw new Client_Html_Exception( $msg );
				}
			}
			else if( $startid != '' )
			{
				$node = $manager->getItem( $startid );
				$catItems = array( $node->getId() => $node );
			}
			else
			{
				$node = $manager->getTree( null, array(), MW_Tree_Manager_Abstract::LEVEL_ONE );
				$catItems = array( $node->getId() => $node );
			}


			$search = $manager->createSearch();
			$expr = $search->compare( '==', 'catalog.parentid', array_keys( $catItems ) );
			$expr = $search->combine( '||', array( $expr, $search->compare( '==', 'catalog.id', $node->getId() ) ) );

			/** client/html/catalog/filter/tree/levels-always
			 * The number of levels in the category tree that should be always displayed
			 *
			 * Usually, only the root node and the first level of the category
			 * tree is shown in the frontend. Only if the user clicks on a
			 * node in the first level, the page reloads and the sub-nodes of
			 * the chosen category are rendered as well.
			 *
			 * Using this configuration option you can enforce the given number
			 * of levels to be always displayed. The root node uses level 0, the
			 * categories below level 1 and so on.
			 *
			 * In most cases you can set this value via the administration interface
			 * of the shop application. In that case you often can configure the
			 * levels individually for each catalog filter.
			 *
			 * @param integer Number of tree levels
			 * @since 2014.03
			 * @category User
			 * @category Developer
			 * @see client/html/catalog/filter/tree/startid
			 * @see client/html/catalog/filter/tree/levels-only
			 */
			if( ( $levels = $view->config( 'client/html/catalog/filter/tree/levels-always' ) ) != null ) {
				$expr = $search->combine( '||', array( $expr, $search->compare( '<=', 'catalog.level', $levels ) ) );
			}

			/** client/html/catalog/filter/tree/levels-only
			 * No more than this number of levels in the category tree should be displayed
			 *
			 * If the user clicks on a category node, the page reloads and the
			 * sub-nodes of the chosen category are rendered as well.
			 * Using this configuration option you can enforce that no more than
			 * the given number of levels will be displayed at all. The root
			 * node uses level 0, the categories below level 1 and so on.
			 *
			 * In most cases you can set this value via the administration interface
			 * of the shop application. In that case you often can configure the
			 * levels individually for each catalog filter.
			 *
			 * @param integer Number of tree levels
			 * @since 2014.03
			 * @category User
			 * @category Developer
			 * @see client/html/catalog/filter/tree/startid
			 * @see client/html/catalog/filter/tree/levels-always
			 */
			if( ( $levels = $view->config( 'client/html/catalog/filter/tree/levels-only' ) ) != null ) {
				$expr = $search->combine( '&&', array( $expr, $search->compare( '<=', 'catalog.level', $levels ) ) );
			}

			$search->setConditions( $expr );

			$id = ( $startid != '' ? $startid : null );
			$level = MW_Tree_Manager_Abstract::LEVEL_TREE;

			$view->treeCatalogPath = $catItems;
			$view->treeCatalogTree = $manager->getTree( $id, $ref, $level, $search );
			$view->treeCatalogIds = $this->_getCatalogIds( $view->treeCatalogTree, $catItems, $currentid );
			$view->treeFilterParams = $this->_getClientParams( $view->param(), array( 'f' ) );

			$this->_addMetaDataCatalog( $view->treeCatalogTree, $ref, $this->_tags, $this->_expire );

			$this->_cache = $view;
		}

		$expire = ( $this->_expire !== null ? ( $expire !== null ? min( $this->_expire, $expire ) : $this->_expire ) : $expire );
		$tags = array_merge( $tags, $this->_tags );

		return $this->_cache;
	}


	/**
	 * Returns the category IDs of the given catalog tree.
	 *
	 * Only the IDs of the children of the current category are returned.
	 *
	 * @param MShop_Catalog_Item_Interface $tree Catalog node as entry point of the tree
	 * @param array $path Associative list of category IDs as keys and the catalog
	 * 	nodes from the currently selected category up to the root node
	 * @param string $currentId Currently selected category
	 * @return array List of category IDs
	 */
	protected function _getCatalogIds( MShop_Catalog_Item_Interface $tree, array $path, $currentId )
	{
		if( $tree->getId() == $currentId )
		{
			$ids = array();
			foreach( $tree->getChildren() as $item ) {
				$ids[] = $item->getId();
			}

			return $ids;
		}

		foreach( $tree->getChildren() as $child )
		{
			if( isset( $path[ $child->getId() ] ) ) {
				return $this->_getCatalogIds( $child, $path, $currentId );
			}
		}

		return array();
	}


	/**
	 * Adds the cache tags to the given list and sets a new expiration date if necessary based on the given catalog tree.
	 *
	 * @param MShop_Common_Item_Interface $item Item, maybe with associated list items
	 * @param array $domains List of domains whose items are associated via the list to the item
	 * @param array &$tags List of tags the new tags will be added to
	 * @param string|null &$expire Expiration date that will be overwritten if an earlier date is found
	 */
	protected function _addMetaDataCatalog( MShop_Catalog_Item_Interface $tree, $domains, array &$tags = array(), &$expire )
	{
		$this->_addMetaData( $tree, 'catalog', $domains, $tags, $expire );

		foreach( $tree->getChildren() as $child ) {
			$this->_addMetaData( $child, 'catalog', $domains, $tags, $expire );
		}
	}
}
