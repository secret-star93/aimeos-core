<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of email html salutation part.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Email_Payment_Html_Salutation_Default
	extends Client_Html_Abstract
{
	private $_subPartPath = 'client/html/email/payment/html/salutation/default/subparts';
	private $_subPartNames = array();


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @return string HTML code
	 */
	public function getBody()
	{
		$view = $this->_setViewParams( $this->getView() );

		$content = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$content .= $subclient->setView( $view )->getBody();
		}
		$view->salutationBody = $content;

		$status = $view->extOrderItem->getPaymentStatus();
		$tplconf = 'client/html/email/payment/html/salutation/default/template-body';
		$default = array( 'email/payment/' . $status . '/html-salutation-body-default.html', 'email/common/html-salutation-body-default.html' );

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

		$content = '';
		foreach( $this->_getSubClients( $this->_subPartPath, $this->_subPartNames ) as $subclient ) {
			$content .= $subclient->setView( $view )->getHeader();
		}
		$view->salutationHeader = $content;

		$status = $view->extOrderItem->getPaymentStatus();
		$tplconf = 'client/html/email/payment/html/salutation/default/template-header';
		$default = array( 'email/payment/' . $status . '/html-salutation-header-default.html', 'email/common/html-salutation-header-default.html' );

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
		return $this->_createSubClient( 'email/payment/html/salutation/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$this->_process( $this->_subPartPath, $this->_subPartNames );
	}
}