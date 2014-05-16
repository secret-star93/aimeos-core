<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of confirm checkout HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Checkout_Confirm_Default
	extends Client_Html_Abstract
{
	/** client/html/checkout/confirm/default/subparts
	 * List of HTML sub-clients rendered within the checkout confirm section
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
	private $_subPartPath = 'client/html/checkout/confirm/default/subparts';

	/** client/html/checkout/confirm/intro/name
	 * Name of the intro part used by the checkout confirm client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Confirm_Intro_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.07
	 * @category Developer
	 */

	/** client/html/checkout/confirm/basic/name
	 * Name of the basic part used by the checkout confirm client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Confirm_Basic_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/confirm/retry/name
	 * Name of the retry part used by the checkout confirm client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Confirm_Retry_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.07
	 * @category Developer
	 */
	private $_subPartNames = array( 'intro', 'basic', 'retry' );


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @return string HTML code
	 */
	public function getBody()
	{
		try
		{
			$view = $this->_setViewParams( $this->getView() );

			$html = '';
			foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
				$html .= $subclient->setView( $view )->getBody();
			}
			$view->confirmBody = $html;
		}
		catch( Client_Html_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context = $this->_getContext();
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$view = $this->getView();
			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}

		/** client/html/checkout/confirm/default/template-body
		 * Relative path to the HTML body template of the checkout confirm client.
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
		 * @see client/html/checkout/confirm/default/template-header
		 */
		$tplconf = 'client/html/checkout/confirm/default/template-body';
		$default = 'checkout/confirm/body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @return string String including HTML tags for the header
	 */
	public function getHeader()
	{
		try
		{
			$view = $this->_setViewParams( $this->getView() );

			$html = '';
			foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader();
			}
			$view->confirmHeader = $html;
		}
		catch( Exception $e )
		{
			$this->_getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
			return '';
		}

		/** client/html/checkout/confirm/default/template-header
		 * Relative path to the HTML header template of the checkout confirm client.
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
		 * @see client/html/checkout/confirm/default/template-body
		 */
		$tplconf = 'client/html/checkout/confirm/default/template-header';
		$default = 'checkout/confirm/header-default.html';

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
		return $this->_createSubClient( 'checkout/confirm/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		try
		{
			$context = $this->_getContext();
			$params = $this->getView()->param();

			$serviceManager = MShop_Factory::createManager( $context, 'service' );

			$search = $serviceManager->createSearch();
			$search->setConditions( $search->compare( '==', 'service.type.code', 'payment' ) );
			$search->setSortations( array( $search->sort( '+', 'service.position' ) ) );

			$start = 0;

			do
			{
				$serviceItems = $serviceManager->searchItems( $search );

				foreach( $serviceItems as $serviceItem )
				{
					try
					{
						$provider = $serviceManager->getProvider( $serviceItem );

						if( ( $orderItem = $provider->updateSync( $params ) ) !== null )
						{
							if( $orderItem->getPaymentStatus() === MShop_Order_Item_Abstract::PAY_UNFINISHED
								&& $provider->isImplemented( MShop_Service_Provider_Payment_Abstract::FEAT_QUERY )
							) {
								$provider->query( $orderItem );
							}

							break 2;
						}
					}
					catch( Exception $e )
					{
						$msg = 'Updating order ID "%1$s" failed: %2$s';
						$context->getLogger()->log( sprintf( $msg, $orderid, $e->getMessage() ) );
					}
				}
				$count = count( $serviceItems );
				$start += $count;
				$search->setSlice( $start );
			}
			while( $count >= $search->getSliceSize() );


			$this->_process( $this->_subPartPath, $this->_subPartNames );


			// Clear basket
			$orderid = $context->getSession()->get( 'arcavias/orderid' );
			$orderManager = MShop_Factory::createManager( $context, 'order' );

			if( $orderManager->getItem( $orderid )->getPaymentStatus() > MShop_Order_Item_Abstract::PAY_REFUSED )
			{
				$orderBaseManager = MShop_Factory::createManager( $context, 'order/base' );
				$orderBaseManager->setSession( $orderBaseManager->createItem() );
			}
		}
		catch( Client_Html_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context = $this->_getContext();
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$view = $this->getView();
			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->confirmErrorList = $view->get( 'confirmErrorList', array() ) + $error;
		}
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param MW_View_Interface $view The view object which generates the HTML output
	 * @return MW_View_Interface Modified view object
	 */
	protected function _setViewParams( MW_View_Interface $view )
	{
		if( !isset( $this->_cache ) )
		{
			$context = $this->_getContext();
			$orderid = $context->getSession()->get( 'arcavias/orderid' );
			$orderManager = MShop_Order_Manager_Factory::createManager( $context );

			$view->confirmOrderItem = $orderManager->getItem( $orderid );

			$this->_cache = $view;
		}

		return $this->_cache;
	}
}
