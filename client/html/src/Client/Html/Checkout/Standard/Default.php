<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of standard checkout HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Checkout_Standard_Default
	extends Client_Html_Abstract
	implements Client_Html_Interface
{
	private $_subPartPath = 'client/html/checkout/standard/default/subparts';
	private $_subPartNames = array( 'address', 'delivery', 'payment', 'summary', 'order' );


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @return string HTML code
	 */
	public function getBody()
	{
		$view = $this->_setViewParams( $this->getView() );

		$html = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getBody();
		}
		$view->standardBody = $html;

		$tplconf = 'client/html/checkout/standard/default/template-body';
		$default = 'checkout/standard/body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @return string String including HTML tags for the header
	 */
	public function getHeader()
	{
		$view = $this->_setViewParams( $this->getView() );

		$html = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader();
		}
		$view->standardHeader = $html;

		$tplconf = 'client/html/checkout/standard/default/template-header';
		$default = 'checkout/standard/header-default.html';

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
		return $this->_createSubClient( 'checkout/standard/' . $type, $name );
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
		try
		{
			$this->_process( $this->_subPartPath, $this->_subPartNames );
		}
		catch( MW_Exception $e )
		{
			$context = $this->_getContext();
			$context->getLogger()->log( $e->getMessage . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( Client_Html_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
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
			$view->standardBasket = $basketCntl->get();


			$steps = (array) $context->getConfig()->get( $this->_subPartPath, $this->_subPartNames );
			$view->standardSteps = $steps;

			if( !isset( $view->standardStepActive ) ) {
				$view->standardStepActive = $view->param( 'c-step', reset( $steps ) );
			}
			$activeStep = $view->standardStepActive;


			$step = null;
			do {
			       $lastStep = $step;
			}
			while( ( $step = array_shift( $steps ) ) !== null && $step !== $activeStep );


			if( $lastStep !== null ) {
				$view->standardUrlBack = $view->url( $view->config( 'checkout-target' ), 'checkout', 'index', array( 'c-step' => $lastStep ) );
			} else {
				$view->standardUrlBack = $view->url( $view->config( 'basket-target' ), 'basket', 'index' );
			}

			if( ( $nextStep = array_shift( $steps ) ) !== null ) {
				$view->standardUrlNext = $view->url( $view->config( 'checkout-target' ), 'checkout', 'index', array( 'c-step' => $nextStep ) );
			} else {
				$view->standardUrlNext = '';
			}


			$this->_cache = $view;
		}

		return $this->_cache;
	}
}