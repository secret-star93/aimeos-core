<?php

/**
 * @copyright Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Filter\Tree;


/**
 * Default implementation of catalog tree filter section in HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/catalog/filter/tree/standard/subparts
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
	private $subPartPath = 'client/html/catalog/filter/tree/standard/subparts';
	private $subPartNames = array();
	private $tags = array();
	private $expire;
	private $cache;


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
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$html = '';
		foreach( $this->getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->treeBody = $html;

		/** client/html/catalog/filter/tree/standard/template-body
		 * Relative path to the HTML body template of the catalog filter tree client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
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
		 * @see client/html/catalog/filter/tree/standard/template-header
		 */
		$tplconf = 'client/html/catalog/filter/tree/standard/template-body';
		$default = 'catalog/filter/tree-body-default.php';

		return $view->render( $this->getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$html = '';
		foreach( $this->getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->treeHeader = $html;

		/** client/html/catalog/filter/tree/standard/template-header
		 * Relative path to the HTML header template of the catalog filter tree client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the HTML code that is inserted into the HTML page header
		 * of the rendered page in the frontend. The configuration string is the
		 * path to the template file relative to the templates directory (usually
		 * in client/html/templates).
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
		 * @see client/html/catalog/filter/tree/standard/template-body
		 */
		$tplconf = 'client/html/catalog/filter/tree/standard/template-header';
		$default = 'catalog/filter/tree-header-default.php';

		return $view->render( $this->getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/catalog/filter/tree/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog filter tree html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/catalog/filter/tree/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/tree/decorators/global
		 * @see client/html/catalog/filter/tree/decorators/local
		 */

		/** client/html/catalog/filter/tree/decorators/global
		 * Adds a list of globally available decorators only to the catalog filter tree html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/tree/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/tree/decorators/excludes
		 * @see client/html/catalog/filter/tree/decorators/local
		 */

		/** client/html/catalog/filter/tree/decorators/local
		 * Adds a list of local decorators only to the catalog filter tree html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Catalog\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/filter/tree/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Catalog\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/filter/tree/decorators/excludes
		 * @see client/html/catalog/filter/tree/decorators/global
		 */

		return $this->createSubClient( 'catalog/filter/tree/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function setViewParams( \Aimeos\MW\View\Iface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->cache ) )
		{
			$catItems = array();
			$context = $this->getContext();
			$controller = \Aimeos\Controller\Frontend\Factory::createController( $context, 'catalog' );

			$currentid = (string) $view->param( 'f_catid', '' );
			$currentid = ( $currentid != '' ? $currentid : null );

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
			 * @see client/html/catalog/filter/tree/domains
			 */
			$startid = $view->config( 'client/html/catalog/filter/tree/startid', '' );
			$startid = ( $startid != '' ? $startid : null );

			/** client/html/catalog/filter/tree/domains
			 * List of domain names whose items should be fetched with the filter categories
			 *
			 * The templates rendering the categories in the catalog filter usually
			 * add the images and texts associated to each item. If you want to
			 * display additional content, you can configure your own list of
			 * domains (attribute, media, price, product, text, etc. are domains)
			 * whose items are fetched from the storage. Please keep in mind that
			 * the more domains you add to the configuration, the more time is
			 * required for fetching the content!
			 *
			 * @param array List of domain item names
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/catalog/filter/tree/startid
			 * @see client/html/catalog/filter/tree/levels-always
			 * @see client/html/catalog/filter/tree/levels-only
			 */
			$ref = $view->config( 'client/html/catalog/filter/tree/domains', array( 'text', 'media' ) );


			if( $currentid )
			{
				$catItems = $controller->getCatalogPath( $currentid );

				if( $startid )
				{
					foreach( $catItems as $key => $item )
					{
						if( $key == $startid ) {
							break;
						}
						unset( $catItems[$key] );
					}
				}
			}

			if( ( $node = reset( $catItems ) ) === false )
			{
				$node = $controller->getCatalogTree( $startid, array(), \Aimeos\MW\Tree\Manager\Base::LEVEL_ONE );
				$catItems = array( $node->getId() => $node );
			}


			$search = $controller->createCatalogFilter();
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
			 * @see client/html/catalog/filter/tree/domains
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
			 * @see client/html/catalog/filter/tree/domains
			 */
			if( ( $levels = $view->config( 'client/html/catalog/filter/tree/levels-only' ) ) != null ) {
				$expr = $search->combine( '&&', array( $expr, $search->compare( '<=', 'catalog.level', $levels ) ) );
			}

			$search->setConditions( $expr );

			$level = \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE;

			$view->treeCatalogPath = $catItems;
			$view->treeCatalogTree = $controller->getCatalogTree( $startid, $ref, $level, $search );
			$view->treeCatalogIds = $this->getCatalogIds( $view->treeCatalogTree, $catItems, $currentid );
			$view->treeFilterParams = $this->getClientParams( $view->param(), array( 'f' ) );

			$this->addMetaItemCatalog( $view->treeCatalogTree, $this->expire, $this->tags );

			$this->cache = $view;
		}

		$expire = $this->expires( $this->expire, $expire );
		$tags = array_merge( $tags, $this->tags );

		return $this->cache;
	}


	/**
	 * Returns the category IDs of the given catalog tree.
	 *
	 * Only the IDs of the children of the current category are returned.
	 *
	 * @param \Aimeos\MShop\Catalog\Item\Iface $tree Catalog node as entry point of the tree
	 * @param array $path Associative list of category IDs as keys and the catalog
	 * 	nodes from the currently selected category up to the root node
	 * @param string $currentId Currently selected category
	 * @return array List of category IDs
	 */
	protected function getCatalogIds( \Aimeos\MShop\Catalog\Item\Iface $tree, array $path, $currentId )
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
			if( isset( $path[$child->getId()] ) ) {
				return $this->getCatalogIds( $child, $path, $currentId );
			}
		}

		return array();
	}


	/**
	 * Adds the cache tags to the given list and sets a new expiration date if necessary based on the given catalog tree.
	 *
	 * @param \Aimeos\MShop\Catalog\Item\Iface $tree Tree node, maybe with sub-nodes
	 * @param string|null &$expire Expiration date that will be overwritten if an earlier date is found
	 * @param array &$tags List of tags the new tags will be added to
	 */
	protected function addMetaItemCatalog( \Aimeos\MShop\Catalog\Item\Iface $tree, &$expire, array &$tags = array() )
	{
		$this->addMetaItem( $tree, 'catalog', $expire, $tags );

		foreach( $tree->getChildren() as $child ) {
			$this->addMetaItemCatalog( $child, $expire, $tags );
		}
	}
}
