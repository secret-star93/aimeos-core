<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of additional attribute item section for catalog detail HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Catalog_Detail_Additional_Attribute_Default
	extends Client_Html_Abstract
{
	/** client/html/catalog/detail/additional/attribute/default/subparts
	 * List of HTML sub-clients rendered within the catalog detail additional attribute section
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
	private $_subPartPath = 'client/html/catalog/detail/additional/attribute/default/subparts';
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
		$view->attributeBody = $html;

		/** client/html/catalog/detail/additional/attribute/default/template-body
		 * Relative path to the HTML body template of the catalog detail additional attribute client.
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
		 * @see client/html/catalog/detail/additional/attribute/default/template-header
		 */
		$tplconf = 'client/html/catalog/detail/additional/attribute/default/template-body';
		$default = 'catalog/detail/additional-attribute-body-default.html';

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
		$view->attributeHeader = $html;

		/** client/html/catalog/detail/additional/attribute/default/template-header
		 * Relative path to the HTML header template of the catalog detail additional attribute client.
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
		 * @see client/html/catalog/detail/additional/attribute/default/template-body
		 */
		$tplconf = 'client/html/catalog/detail/additional/attribute/default/template-header';
		$default = 'catalog/detail/additional-attribute-header-default.html';

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
		return $this->_createSubClient( 'catalog/detail/additional/attribute/' . $type, $name );
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
			$context = $this->_getContext();
			$attrIds = $attributeMap = $subAttrDeps = array();

			if( isset( $view->detailProductItem ) )
			{
				$attrIds = array_keys( $view->detailProductItem->getRefItems( 'attribute', null, 'default' ) );
				$attrIds += array_keys( $view->detailProductItem->getRefItems( 'attribute', null, 'variant' ) );
			}


			// find regular attributes from sub-products
			$products = $view->detailProductItem->getRefItems( 'product', 'default', 'default' );

			$productManager = MShop_Product_Manager_Factory::createManager( $context );

			$search = $productManager->createSearch( true );
			$expr = array(
				$search->compare( '==', 'product.id', array_keys( $products ) ),
				$search->getConditions(),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );

			foreach( $productManager->searchItems( $search, array( 'attribute' ) ) as $subProdId => $subProduct )
			{
				$subItems = $subProduct->getRefItems( 'attribute', null, 'default' );
				$subItems += $subProduct->getRefItems( 'attribute', null, 'variant' );

				foreach( $subItems as $attrId => $attrItem )
				{
					$subAttrDeps[$attrId][] = $subProdId;
					$attrIds[] = $attrId;
				}
			}


			$attrManager = MShop_Attribute_Manager_Factory::createManager( $context );

			$search = $attrManager->createSearch( true );
			$expr = array(
				$search->compare( '==', 'attribute.id', $attrIds ),
				$search->getConditions(),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );

			/** @todo Make referenced domains configurable */
			foreach( $attrManager->searchItems( $search, array( 'text', 'media') ) as $id => $item )
			{
				$this->_addMetaData( $item, 'attribute', array( 'text', 'media' ), $this->_tags, $this->_expire );
				$attributeMap[ $item->getType() ][$id] = $item;
			}


			$view->attributeMap = $attributeMap;
			$view->subAttributeDependencies = $subAttrDeps;

			$this->_cache = $view;
		}

		$expire = ( $this->_expire !== null ? ( $expire !== null ? min( $this->_expire, $expire ) : $this->_expire ) : $expire );
		$tags = array_merge( $tags, $this->_tags );

		return $this->_cache;
	}
}