<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of email html outro part.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Email_Delivery_Html_Outro_Default
	extends Client_Html_Abstract
{
	/** client/html/email/delivery/html/outro/default/subparts
	 * List of HTML sub-clients rendered within the email delivery html outro section
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
	private $_subPartPath = 'client/html/email/delivery/html/outro/default/subparts';
	private $_subPartNames = array();


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

		$content = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->outroBody = $content;

		/** client/html/email/delivery/html/outro/default/template-body
		 * Relative path to the HTML body template of the email delivery html footer client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the e-mail. The
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
		 * The footer part of the email delivery html client allows to use
		 * a different template for each delivery status value. You can create a
		 * template for each delivery status and store it in the "email/delivery/<status number>/"
		 * directory below the "layouts" directory (usually in client/html/layouts).
		 * If no specific layout template is found, the common template in the
		 * "email/delivery/" directory is used.
		 *
		 * @param string Relative path to the template creating code for the HTML e-mail body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/email/delivery/html/outro/default/template-header
		 */
		$tplconf = 'client/html/email/delivery/html/outro/default/template-body';

		$status = $view->extOrderItem->getDeliveryStatus();
		$default = array( 'email/delivery/' . $status . '/html-outro-body-default.html', 'email/common/html-outro-body-default.html' );

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

		$content = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->outroHeader = $content;

		/** client/html/email/delivery/html/outro/default/template-header
		 * Relative path to the HTML header template of the email delivery html footer client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the HTML code that is inserted into the header
		 * of the e-mail. The configuration string is the
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
		 * The email payment HTML client allows to use a different template for
		 * each payment status value. You can create a template for each payment
		 * status and store it in the "email/payment/<status number>/" directory
		 * below the "layouts" directory (usually in client/html/layouts). If no
		 * specific layout template is found, the common template in the
		 * "email/payment/" directory is used.
		 *
		 * @param string Relative path to the template creating code for the e-mail header
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/email/delivery/html/outro/default/template-body
		 */
		$tplconf = 'client/html/email/delivery/html/outro/default/template-header';

		$status = $view->extOrderItem->getDeliveryStatus();
		$default = array( 'email/delivery/' . $status . '/html-outro-header-default.html', 'email/common/html-outro-header-default.html' );

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
		return $this->_createSubClient( 'email/delivery/html/outro/' . $type, $name );
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