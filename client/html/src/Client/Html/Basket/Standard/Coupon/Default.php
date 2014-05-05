<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of standard basket coupon HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Basket_Standard_Coupon_Default
	extends Client_Html_Abstract
{
	/** client/html/basket/standard/coupon/default/subparts
	 * List of HTML sub-clients rendered within the basket standard coupon section
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
	private $_subPartPath = 'client/html/basket/standard/coupon/default/subparts';
	private $_subPartNames = array();
	private $_cache;


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
		$view->couponBody = $html;

		/** client/html/basket/standard/coupon/default/template-body
		 * Relative path to the HTML body template of the basket standard coupon client.
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
		 * @see client/html/basket/standard/coupon/default/template-header
		 */
		$tplconf = 'client/html/basket/standard/coupon/default/template-body';
		$default = 'basket/standard/coupon-body-default.html';

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
		$view->coupponHeader = $html;

		/** client/html/basket/standard/coupon/default/template-header
		 * Relative path to the HTML header template of the basket standard coupon client.
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
		 * @see client/html/basket/standard/coupon/default/template-body
		 */
		$tplconf = 'client/html/basket/standard/coupon/default/template-header';
		$default = 'basket/standard/coupon-header-default.html';

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
		return $this->_createSubClient( 'basket/standard/coupon/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();

		switch( $view->param( 'b-action' ) )
		{
			case 'coupon-delete':

				if( ( $coupon = $view->param( 'b-coupon' ) ) != '' )
				{
					$cntl = Controller_Frontend_Factory::createController( $this->_getContext(), 'basket' );
					$cntl->deleteCoupon( $coupon );
				}

				break;

			default:

				if( ( $coupon = $view->param( 'b-coupon' ) ) != '' )
				{
					$context = $this->_getContext();
					$cntl = Controller_Frontend_Factory::createController( $context, 'basket' );

					/** client/html/basket/standard/coupon/allowed
					 * Number of coupon codes a customer is allowed to enter
					 *
					 * This configuration option enables shop owners to limit the number of coupon
					 * codes that can be added by a customer to his current basket. By default, only
					 * one coupon code is allowed per order.
					 *
					 * Coupon codes are valid until a payed order is placed by the customer. The
					 * "count" of the codes is decreased afterwards. If codes are not personalized
					 * the codes can be reused in the next order until their "count" reaches zero.
					 *
					 * @param integer Positive number of coupon codes including zero
					 * @since 2014.05
					 * @category User
					 * @category Developer
					 */
					$allowed = $context->getConfig()->get( 'client/html/basket/standard/coupon/allowed', 1 );

					if( $allowed <= count( $cntl->get()->getCoupons() ) ) {
						throw new Client_Html_Exception( sprintf( 'Number of coupon codes exceeds the limit' ) );
					}

					$cntl->addCoupon( $coupon );
				}

				break;
		}

		$this->_process( $this->_subPartPath, $this->_subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param MW_View_Interface $view The view object which generates the HTML output
	 */
	protected function _setViewParams( MW_View_Interface $view )
	{
		return $view;
	}
}