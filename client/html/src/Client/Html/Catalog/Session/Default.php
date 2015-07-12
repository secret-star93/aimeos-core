<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of catalog session section HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Catalog_Session_Default
	extends Client_Html_Abstract
{
	/** client/html/catalog/session/default/subparts
	 * List of HTML sub-clients rendered within the catalog session section
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
	private $_subPartPath = 'client/html/catalog/session/default/subparts';

	/** client/html/catalog/session/pinned/name
	 * Name of the pinned part used by the catalog session client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Catalog_Session_Pinned_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.09
	 * @category Developer
	 */

	/** client/html/catalog/session/seen/name
	 * Name of the seen part used by the catalog session client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Catalog_Session_Seen_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */
	private $_subPartNames = array( 'pinned', 'seen' );


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
		$context = $this->_getContext();
		$view = $this->getView();

		try
		{
			$view = $this->_setViewParams( $view, $tags, $expire );

			$html = '';
			foreach( $this->_getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
			}
			$view->sessionBody = $html;
		}
		catch( Client_Html_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}

		/** client/html/catalog/session/default/template-body
		 * Relative path to the HTML body template of the catalog session client.
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
		 * @see client/html/catalog/session/default/template-header
		 */
		$tplconf = 'client/html/catalog/session/default/template-body';
		$default = 'catalog/session/body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
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
			$view = $this->_setViewParams( $this->getView(), $tags, $expire );

			$html = '';
			foreach( $this->_getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
			}
			$view->sessionHeader = $html;

			/** client/html/catalog/session/default/template-header
			 * Relative path to the HTML header template of the catalog session client.
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
			 * @see client/html/catalog/session/default/template-body
			 */
			$tplconf = 'client/html/catalog/session/default/template-header';
			$default = 'catalog/session/header-default.html';

			return $view->render( $this->_getTemplate( $tplconf, $default ) );
		}
		catch( Exception $e )
		{
			$this->_getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
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
		return $this->_createSubClient( 'catalog/session/' . $type, $name );
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
			parent::process();
		}
		catch( Client_Html_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$view = $this->getView();
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context = $this->_getContext();
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$view = $this->getView();
			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->sessionErrorList = $view->get( 'sessionErrorList', array() ) + $error;
		}
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
}
