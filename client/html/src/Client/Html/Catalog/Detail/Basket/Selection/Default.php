<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of catalog detail basket selection section for HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Catalog_Detail_Basket_Selection_Default
	extends Client_Html_Abstract
{
	/** client/html/catalog/detail/basket/selection/default/subparts
	 * List of HTML sub-clients rendered within the catalog detail basket selection section
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
	private $_subPartPath = 'client/html/catalog/detail/basket/selection/default/subparts';
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

		$html = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->selectionBody = $html;

		/** client/html/catalog/detail/basket/selection/default/template-body
		 * Relative path to the HTML body template of the catalog detail basket selection client.
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
		 * @see client/html/catalog/detail/basket/selection/default/template-header
		 */
		$tplconf = 'client/html/catalog/detail/basket/selection/default/template-body';
		$default = 'catalog/detail/basket-selection-body-default.html';

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
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->selectionHeader = $html;

		/** client/html/catalog/detail/basket/selection/default/template-header
		 * Relative path to the HTML header template of the catalog detail basket selection client.
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
		 * @see client/html/catalog/detail/basket/selection/default/template-body
		 */
		$tplconf = 'client/html/catalog/detail/basket/selection/default/template-header';
		$default = 'catalog/detail/basket-selection-header-default.html';

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
		return $this->_createSubClient( 'catalog/detail/basket/selection/' . $type, $name );
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
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return MW_View_Interface Modified view object
	 */
	protected function _setViewParams( MW_View_Interface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->_cache ) )
		{
			if( $view->detailProductItem->getType() === 'select' )
			{
				$context = $this->_getContext();
				$products = $view->detailProductItem->getRefItems( 'product', 'default', 'default' );

				$productManager = MShop_Product_Manager_Factory::createManager( $context );
				$attrManager = MShop_Attribute_Manager_Factory::createManager( $context );

				$search = $productManager->createSearch( true );
				$expr = array(
					$search->compare( '==', 'product.id', array_keys( $products ) ),
					$search->getConditions(),
				);
				$search->setConditions( $search->combine( '&&', $expr ) );

				$domains = array( 'text', 'price', 'media', 'attribute' );
				$subproducts = $productManager->searchItems( $search, $domains );
				$attrIds = $attrMap = $prodDeps = $attrDeps = $attrTypeDeps = array();

				foreach( $subproducts as $subProdId => $subProduct )
				{
					foreach( $subProduct->getRefItems( 'attribute', null, 'variant' ) as $attrId => $attrItem )
					{
						$attrTypeDeps[ $attrItem->getType() ][$attrId] = $attrItem->getPosition();
						$attrDeps[$attrId][] = $subProdId;
						$prodDeps[$subProdId][] = $attrId;
						$attrIds[] = $attrId;
					}
				}

				ksort( $attrTypeDeps );

				$search = $attrManager->createSearch( true );
				$expr = array(
					$search->compare( '==', 'attribute.id', $attrIds ),
					$search->getConditions(),
				);
				$search->setConditions( $search->combine( '&&', $expr ) );
				$attributes = $attrManager->searchItems( $search, array( 'text', 'media' ) );


				foreach( $subproducts as $item ) {
					$this->_addMetaData( $item, 'product', $domains, $this->_tags, $this->_expire );
				}

				foreach( $attributes as $item ) {
					$this->_addMetaData( $item, 'attribute', array( 'text', 'media' ), $this->_tags, $this->_expire );
				}


				$view->selectionProducts = $subproducts;
				$view->selectionProductDependencies = $prodDeps;
				$view->selectionAttributeDependencies = $attrDeps;
				$view->selectionAttributeTypeDependencies = $attrTypeDeps;
				$view->selectionAttributeItems = $attributes;
			}

			$this->_cache = $view;
		}

		$expire = ( $this->_expire !== null ? ( $expire !== null ? min( $this->_expire, $expire ) : $this->_expire ) : $expire );
		$tags = array_merge( $tags, $this->_tags );

		return $this->_cache;
	}
}