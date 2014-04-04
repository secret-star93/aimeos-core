<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


// Strings for translation
_('delivery');


/**
 * Default implementation of checkout delivery HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Checkout_Standard_Delivery_Default
	extends Client_Html_Abstract
{
	/** client/html/checkout/standard/delivery/default/subparts
	 * List of HTML sub-clients rendered within the checkout standard delivery section
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
	private $_subPartPath = 'client/html/checkout/standard/delivery/default/subparts';
	private $_subPartNames = array();
	private $_cache;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @return string HTML code
	 */
	public function getBody()
	{
		$view = $this->getView();

		if( $view->get( 'standardStepActive' ) != 'delivery' ) {
			return '';
		}

		$view = $this->_setViewParams( $view );

		$html = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getBody();
		}
		$view->deliveryBody = $html;

		/** client/html/checkout/standard/delivery/default/template-body
		 * Relative path to the HTML body template of the checkout standard delivery client.
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
		 * @see client/html/checkout/standard/delivery/default/template-header
		 */
		$tplconf = 'client/html/checkout/standard/delivery/default/template-body';
		$default = 'checkout/standard/delivery-body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @return string String including HTML tags for the header
	 */
	public function getHeader()
	{
		$view = $this->getView();

		if( $view->get( 'standardStepActive' ) != 'delivery' ) {
			return '';
		}

		$view = $this->_setViewParams( $view );

		$html = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader();
		}
		$view->deliveryHeader = $html;

		/** client/html/checkout/standard/delivery/default/template-header
		 * Relative path to the HTML header template of the checkout standard delivery client.
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
		 * @see client/html/checkout/standard/delivery/default/template-body
		 */
		$tplconf = 'client/html/checkout/standard/delivery/default/template-header';
		$default = 'checkout/standard/delivery-header-default.html';

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
		return $this->_createSubClient( 'checkout/standard/delivery/' . $type, $name );
	}


	/**
	 * Tests if the output of is cachable.
	 *
	 * @param integer $what Header or body constant from Client_HTML_Abstract
	 * @return boolean True if the output can be cached, false if not
	 */
	public function isCachable( $what )
	{
		return false;
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();

		try
		{
			// only start if there's something to do
			if( ( $serviceId = $view->param( 'c-delivery-option', null ) ) === null ) {
				return;
			}

			$context = $this->_getContext();
			$serviceCtrl = Controller_Frontend_Service_Factory::createController( $context );

			$attributes = $view->param( 'c-delivery/' . $serviceId, array() );
			$errors = $serviceCtrl->checkServiceAttributes( 'delivery', $serviceId, $attributes );

			foreach( $errors as $key => $msg )
			{
				if( $msg === null ) {
					unset( $errors[$key] );
				}
			}

			if( count( $errors ) === 0 )
			{
				$basketCtrl = Controller_Frontend_Basket_Factory::createController( $context );
				$basketCtrl->setService( 'delivery', $serviceId, $attributes );
			}
			else
			{
				$view->standardStepActive = 'delivery';
			}

			$view->deliveryError = $errors;

			$this->_process( $this->_subPartPath, $this->_subPartNames );
		}
		catch( Exception $e )
		{
			$view->standardStepActive = 'delivery';
			throw $e;
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

			$basketCntl = Controller_Frontend_Basket_Factory::createController( $context );
			$serviceCntl = Controller_Frontend_Service_Factory::createController( $context );

			$basket = $basketCntl->get();

			$services = $serviceCntl->getServices( 'delivery', $basket );
			$serviceAttributes = $servicePrices = array();

			foreach( $services as $id => $service )
			{
				$serviceAttributes[$id] = $serviceCntl->getServiceAttributes( 'delivery', $id, $basket );
				$servicePrices[$id] = $serviceCntl->getServicePrice( 'delivery', $id, $basket );
			}

			$view->deliveryServices = $services;
			$view->deliveryServiceAttributes = $serviceAttributes;
			$view->deliveryServicePrices = $servicePrices;

			$this->_cache = $view;
		}

		return $this->_cache;
	}
}