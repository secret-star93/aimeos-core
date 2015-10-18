<?php

/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Checkout\Update;


/**
 * Default implementation of update checkout HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/checkout/update/standard/subparts
	 * List of HTML sub-clients rendered within the checkout update section
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
	private $subPartPath = 'client/html/checkout/update/standard/subparts';
	private $subPartNames = array();


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
			$view->updateBody = $html;
		}
		catch( \Aimeos\Client\Html\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->updateErrorList = $view->get( 'updateErrorList', array() ) + $error;
		}
		catch( \Aimeos\Controller\Frontend\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->updateErrorList = $view->get( 'updateErrorList', array() ) + $error;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->updateErrorList = $view->get( 'updateErrorList', array() ) + $error;
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->updateErrorList = $view->get( 'updateErrorList', array() ) + $error;
		}

		/** client/html/checkout/update/standard/template-body
		 * Relative path to the HTML body template of the checkout update client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/update/standard/template-header
		 */
		$tplconf = 'client/html/checkout/update/standard/template-body';
		$default = 'checkout/update/body-default.php';

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
			$view->updateHeader = $html;

			/** client/html/checkout/update/standard/template-header
			 * Relative path to the HTML header template of the checkout update client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the templates directory (usually
			 * in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/checkout/update/standard/template-body
			 */
			$tplconf = 'client/html/checkout/update/standard/template-header';
			$default = 'checkout/update/header-default.php';

			return $view->render( $this->getTemplate( $tplconf, $default ) );
		}
		catch( \Exception $e )
		{
			$this->getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}
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
		/** client/html/checkout/update/decorators/excludes
		 * Excludes decorators added by the "common" option from the checkout update html client
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
		 *  client/html/checkout/update/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/update/decorators/global
		 * @see client/html/checkout/update/decorators/local
		 */

		/** client/html/checkout/update/decorators/global
		 * Adds a list of globally available decorators only to the checkout update html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/checkout/update/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/update/decorators/excludes
		 * @see client/html/checkout/update/decorators/local
		 */

		/** client/html/checkout/update/decorators/local
		 * Adds a list of local decorators only to the checkout update html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Checkout\Decorator\*") around the html client.
		 *
		 *  client/html/checkout/update/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Checkout\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/update/decorators/excludes
		 * @see client/html/checkout/update/decorators/global
		 */

		return $this->createSubClient( 'checkout/update/' . $type, $name );
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

		try
		{
			$provider = $this->getServiceProvider( $view->param( 'code' ) );

			$config = array( 'absoluteUri' => true, 'namespace' => false );
			$params = array( 'code' => $view->param( 'code' ), 'orderid' => $view->param( 'orderid' ) );
			$urls = array(
				'payment.url-success' => $this->getUrlConfirm( $view, $params, $config ),
				'payment.url-update' => $this->getUrlUpdate( $view, $params, $config ),
			);
			$urls['payment.url-self'] = $urls['payment.url-update'];
			$provider->injectGlobalConfigBE( $urls );

			$response = null;
			$headers = array();

			try
			{
				$body = $view->request()->getBody();

				if( ( $orderItem = $provider->updateSync( $view->param(), $body, $response, $headers ) ) !== null ) {
					\Aimeos\Controller\Frontend\Factory::createController( $context, 'order' )->update( $orderItem ); // stock, coupons
				}

				$view->updateMessage = $response;
			}
			catch( \Aimeos\MShop\Service\Exception $e )
			{
				$view->updateMessage = $e->getMessage();
			}

			if( !empty( $headers ) ) {
				$view->updateHttpHeaders = $headers;
			}

			parent::process();
		}
		catch( \Exception $e )
		{
			/** client/html/checkout/standard/update/http-error
			 * HTTP header sent for failed attempts to update the order status
			 *
			 * This HTTP header is returned to the remote system if the status
			 * update failed due to an error in the application. This header is
			 * not sent if e.g. a payment was refused by the payment gateway!
			 * It should be one of the 5xx HTTP headers.
			 *
			 * @param array List of valid HTTP headers
			 * @since 2015.07
			 * @category Developer
			 * @see client/html/checkout/standard/update/http-success
			 */
			$default = array( 'HTTP/1.1 500 Error updating order status' );
			$headerList = $context->getConfig()->get( 'client/html/checkout/standard/update/http-error', $default );

			$view->updateHttpHeaders = $headerList;
			$view->updateMessage = $e->getMessage();

			$body = $view->request()->getBody();
			$params = print_r( $view->param(), true );
			$msg = "Updating order status failed: %1\$s\n%2\$s\n%3\$s";
			$context->getLogger()->log( sprintf( $msg, $e->getMessage(), $params, $body ) );
		}
	}


	/**
	 * Returns the service provider for the given code
	 *
	 * @param string $code Unique service code
	 * @throws \Aimeos\Client\Html\Exception If no service item could be found
	 * @return \Aimeos\MShop\Service\Provider\Iface Service provider object
	 */
	protected function getServiceProvider( $code )
	{
		$serviceManager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'service' );

		$search = $serviceManager->createSearch();
		$search->setConditions( $search->compare( '==', 'service.code', $code ) );

		$result = $serviceManager->searchItems( $search );

		if( ( $serviceItem = reset( $result ) ) === false )
		{
			$msg = sprintf( 'No service for code "%1$s" found', $code );
			throw new \Aimeos\Client\Html\Exception( $msg );
		}

		return $serviceManager->getProvider( $serviceItem );
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
	 * Returns the URL to the confirm page.
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $params Parameters that should be part of the URL
	 * @param array $config Default URL configuration
	 * @return string URL string
	 */
	protected function getUrlConfirm( \Aimeos\MW\View\Iface $view, array $params, array $config )
	{
		$target = $view->config( 'client/html/checkout/confirm/url/target' );
		$cntl = $view->config( 'client/html/checkout/confirm/url/controller', 'checkout' );
		$action = $view->config( 'client/html/checkout/confirm/url/action', 'confirm' );
		$config = $view->config( 'client/html/checkout/confirm/url/config', $config );

		return $view->url( $target, $cntl, $action, $params, array(), $config );
	}


	/**
	 * Returns the URL to the update page.
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $params Parameters that should be part of the URL
	 * @param array $config Default URL configuration
	 * @return string URL string
	 */
	protected function getUrlUpdate( \Aimeos\MW\View\Iface $view, array $params, array $config )
	{
		$target = $view->config( 'client/html/checkout/update/url/target' );
		$cntl = $view->config( 'client/html/checkout/update/url/controller', 'checkout' );
		$action = $view->config( 'client/html/checkout/update/url/action', 'update' );
		$config = $view->config( 'client/html/checkout/update/url/config', $config );

		return $view->url( $target, $cntl, $action, $params, array(), $config );
	}
}
