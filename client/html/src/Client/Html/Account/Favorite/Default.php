<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of account favorite HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Account_Favorite_Default
	extends Client_Html_Common_Client_Factory_Abstract
	implements Client_Html_Common_Client_Factory_Interface
{
	/** client/html/account/favorite/default/subparts
	 * List of HTML sub-clients rendered within the account favorite section
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
	private $subPartPath = 'client/html/account/favorite/default/subparts';
	private $subPartNames = array();
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
		$context = $this->getContext();
		$view = $this->getView();

		try
		{
			$view = $this->setViewParams( $view, $tags, $expire );

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
			}
			$view->favoriteBody = $html;
		}
		catch( Client_Html_Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->favoriteErrorList = $view->get( 'favoriteErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->favoriteErrorList = $view->get( 'favoriteErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->favoriteErrorList = $view->get( 'favoriteErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->favoriteErrorList = $view->get( 'favoriteErrorList', array() ) + $error;
		}

		/** client/html/account/favorite/default/template-body
		 * Relative path to the HTML body template of the account favorite client.
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
		 * @see client/html/account/favorite/default/template-header
		 */
		$tplconf = 'client/html/account/favorite/default/template-body';
		$default = 'account/favorite/body-default.html';

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
		try
		{
			$view = $this->setViewParams( $this->getView(), $tags, $expire );

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
			}
			$view->favoriteHeader = $html;

			/** client/html/account/favorite/default/template-header
			 * Relative path to the HTML header template of the account favorite client.
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
			 * @see client/html/account/favorite/default/template-body
			 */
			$tplconf = 'client/html/account/favorite/default/template-header';
			$default = 'account/favorite/header-default.html';

			return $view->render( $this->getTemplate( $tplconf, $default ) );
		}
		catch( Exception $e )
		{
			$this->getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}
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
		/** client/html/account/favorite/decorators/excludes
		 * Excludes decorators added by the "common" option from the account favorite html client
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
		 *  client/html/account/favorite/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("Client_Html_Common_Decorator_*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/favorite/decorators/global
		 * @see client/html/account/favorite/decorators/local
		 */

		/** client/html/account/favorite/decorators/global
		 * Adds a list of globally available decorators only to the account favorite html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("Client_Html_Common_Decorator_*") around the html client.
		 *
		 *  client/html/account/favorite/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "Client_Html_Common_Decorator_Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/favorite/decorators/excludes
		 * @see client/html/account/favorite/decorators/local
		 */

		/** client/html/account/favorite/decorators/local
		 * Adds a list of local decorators only to the account favorite html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("Client_Html_Account_Decorator_*") around the html client.
		 *
		 *  client/html/account/favorite/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "Client_Html_Account_Decorator_Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/favorite/decorators/excludes
		 * @see client/html/account/favorite/decorators/global
		 */
		return $this->createSubClient( 'account/favorite/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();
		$context = $this->getContext();
		$ids = $view->param( 'fav_id', array() );


		if( $context->getUserId() != null && !empty( $ids ) )
		{
			$typeItem = $this->getTypeItem( 'customer/list/type', 'product', 'favorite' );
			$manager = MShop_Factory::createManager( $context, 'customer/list' );

			$search = $manager->createSearch();
			$expr = array(
				$search->compare( '==', 'customer.list.parentid', $context->getUserId() ),
				$search->compare( '==', 'customer.list.refid', $ids ),
				$search->compare( '==', 'customer.list.domain', 'product' ),
				$search->compare( '==', 'customer.list.typeid', $typeItem->getId() ),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );

			$items = array();
			foreach( $manager->searchItems( $search ) as $item ) {
				$items[$item->getRefId()] = $item;
			}


			switch( $view->param( 'fav_action' ) )
			{
				case 'add':

					$item = $manager->createItem();
					$item->setParentId( $context->getUserId() );
					$item->setTypeId( $typeItem->getId() );
					$item->setDomain( 'product' );
					$item->setStatus( 1 );

					foreach( (array) $view->param( 'fav_id', array() ) as $id )
					{
						if( !isset( $items[$id] ) )
						{
							$item->setId( null );
							$item->setRefId( $id );

							$manager->saveItem( $item );
							$manager->moveItem( $item->getId() );
						}
					}

					break;

				case 'delete':

					$listIds = array();

					foreach( (array) $view->param( 'fav_id', array() ) as $id )
					{
						if( isset( $items[$id] ) ) {
							$listIds[] = $items[$id]->getId();
						}
					}

					$manager->deleteItems( $listIds );
					break;
			}
		}

		parent::process();
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
	 * Returns the sanitized page from the parameters for the product list.
	 *
	 * @param MW_View_Interface $view View instance with helper for retrieving the required parameters
	 * @return integer Page number starting from 1
	 */
	protected function getProductListPage( MW_View_Interface $view )
	{
		$page = (int) $view->param( 'fav_page', 1 );
		return ( $page < 1 ? 1 : $page );
	}


	/**
	 * Returns the sanitized page size from the parameters for the product list.
	 *
	 * @param MW_View_Interface $view View instance with helper for retrieving the required parameters
	 * @return integer Page size
	 */
	protected function getProductListSize( MW_View_Interface $view )
	{
		/** client/html/account/favorite/size
		 * The number of products shown in a list page for favorite products
		 *
		 * Limits the number of products that is shown in the list pages to the
		 * given value. If more products are available, the products are split
		 * into bunches which will be shown on their own list page. The user is
		 * able to move to the next page (or previous one if it's not the first)
		 * to display the next (or previous) products.
		 *
		 * The value must be an integer number from 1 to 100. Negative values as
		 * well as values above 100 are not allowed. The value can be overwritten
		 * per request if the "l_size" parameter is part of the URL.
		 *
		 * @param integer Number of products
		 * @since 2014.09
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/list/size
		 */
		$defaultSize = $this->getContext()->getConfig()->get( 'client/html/account/favorite/size', 48 );

		$size = (int) $view->param( 'fav-size', $defaultSize );
		return ( $size < 1 || $size > 100 ? $defaultSize : $size );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param MW_View_Interface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return MW_View_Interface Modified view object
	 */
	protected function setViewParams( MW_View_Interface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->cache ) )
		{
			$total = 0;
			$productIds = array();
			$context = $this->getContext();
			$typeItem = $this->getTypeItem( 'customer/list/type', 'product', 'favorite' );

			$size = $this->getProductListSize( $view );
			$current = $this->getProductListPage( $view );
			$last = ( $total != 0 ? ceil( $total / $size ) : 1 );


			$manager = MShop_Factory::createManager( $context, 'customer/list' );

			$search = $manager->createSearch();
			$expr = array(
				$search->compare( '==', 'customer.list.parentid', $context->getUserId() ),
				$search->compare( '==', 'customer.list.typeid', $typeItem->getId() ),
				$search->compare( '==', 'customer.list.domain', 'product' ),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );
			$search->setSortations( array( $search->sort( '-', 'customer.list.position' ) ) );
			$search->setSlice( ( $current - 1 ) * $size, $size );

			$view->favoriteListItems = $manager->searchItems( $search, array(), $total );


			/** client/html/account/favorite/domains
			 * A list of domain names whose items should be available in the account favorite view template
			 *
			 * The templates rendering product details usually add the images,
			 * prices and texts associated to the product item. If you want to
			 * display additional or less content, you can configure your own
			 * list of domains (attribute, media, price, product, text, etc. are
			 * domains) whose items are fetched from the storage. Please keep
			 * in mind that the more domains you add to the configuration, the
			 * more time is required for fetching the content!
			 *
			 * @param array List of domain names
			 * @since 2014.09
			 * @category Developer
			 * @see client/html/catalog/domains
			 */
			$default = array( 'text', 'price', 'media' );
			$domains = $context->getConfig()->get( 'client/html/account/favorite/domains', $default );

			foreach( $view->favoriteListItems as $listItem ) {
				$productIds[] = $listItem->getRefId();
			}

			$controller = Controller_Frontend_Factory::createController( $context, 'catalog' );

			$view->favoriteProductItems = $controller->getProductItems( $productIds, $domains );
			$view->favoritePageFirst = 1;
			$view->favoritePagePrev = ( $current > 1 ? $current - 1 : 1 );
			$view->favoritePageNext = ( $current < $last ? $current + 1 : $last );
			$view->favoritePageLast = $last;
			$view->favoritePageCurr = $current;

			$this->cache = $view;
		}

		return $this->cache;
	}
}