<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of email html HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Email_Payment_Html_Default
	extends Client_Html_Common_Client_Factory_Abstract
	implements Client_Html_Common_Client_Factory_Interface
{
	/** client/html/email/payment/html/default/subparts
	 * List of HTML sub-clients rendered within the email payment html section
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
	private $subPartPath = 'client/html/email/payment/html/default/subparts';

	/** client/html/email/payment/html/salutation/name
	 * Name of the salutation part used by the email payment html client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Email_Payment_Html_Salutation_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/email/payment/html/intro/name
	 * Name of the introduction part used by the email payment html client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Email_Payment_Html_Intro_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/email/payment/html/summary/name
	 * Name of the summary part used by the email payment html client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Email_Payment_Html_Summary_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/email/payment/html/outro/name
	 * Name of the footer part used by the email payment html client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Email_Payment_Html_Outro_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/email/payment/html/legal/name
	 * Name of the legal part used by the email payment html client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Email_Payment_Html_Legal_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartNames = array( 'salutation', 'intro', 'summary', 'outro', 'legal' );


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
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$content = '';
		foreach( $this->getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->htmlBody = $content;

		/** client/html/email/payment/html/default/template-body
		 * Relative path to the HTML body template of the email payment html client.
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
		 * The email payment html client allows to use a different template for
		 * each payment status value. You can create a template for each payment
		 * status and store it in the "email/payment/<status number>/" directory
		 * below the "layouts" directory (usually in client/html/layouts). If no
		 * specific layout template is found, the common template in the
		 * "email/payment/" directory is used.
		 *
		 * @param string Relative path to the template creating code for the HTML e-mail body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/email/payment/html/default/template-header
		 */
		$tplconf = 'client/html/email/payment/html/default/template-body';

		$status = $view->extOrderItem->getPaymentStatus();
		$default = array( 'email/payment/' . $status . '/html-body-default.html', 'email/common/html-body-default.html' );

		$html = $view->render( $this->getTemplate( $tplconf, $default ) );
		$view->mail()->setBodyHtml( $html );
		return $html;
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
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$content = '';
		foreach( $this->getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->htmlHeader = $content;

		/** client/html/email/payment/html/default/template-header
		 * Relative path to the HTML header template of the email payment html client.
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
		 * @see client/html/email/payment/html/default/template-body
		 */
		$tplconf = 'client/html/email/payment/html/default/template-header';

		$status = $view->extOrderItem->getPaymentStatus();
		$default = array( 'email/payment/' . $status . '/html-header-default.html', 'email/common/html-header-default.html' );

		return $view->render( $this->getTemplate( $tplconf, $default ) );
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
		/** client/html/email/payment/html/decorators/excludes
		 * Excludes decorators added by the "common" option from the "email payment html" html client
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
		 *  client/html/email/payment/html/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("Client_Html_Common_Decorator_*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/html/decorators/global
		 * @see client/html/email/payment/html/decorators/local
		 */

		/** client/html/email/payment/html/decorators/global
		 * Adds a list of globally available decorators only to the "email payment html" html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("Client_Html_Common_Decorator_*") around the html client.
		 *
		 *  client/html/email/payment/html/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "Client_Html_Common_Decorator_Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/html/decorators/excludes
		 * @see client/html/email/payment/html/decorators/local
		 */

		/** client/html/email/payment/html/decorators/local
		 * Adds a list of local decorators only to the "email payment html" html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("Client_Html_Checkout_Decorator_*") around the html client.
		 *
		 *  client/html/email/payment/html/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "Client_Html_Checkout_Decorator_Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/html/decorators/excludes
		 * @see client/html/email/payment/html/decorators/global
		 */

		return $this->createSubClient( 'email/payment/html/' . $type, $name );
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
}