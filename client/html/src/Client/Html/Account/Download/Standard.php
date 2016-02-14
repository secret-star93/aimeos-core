<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Account\Download;


/**
 * Default implementation of account download HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/account/download/standard/subparts
	 * List of HTML sub-clients rendered within the account download section
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
	 * @since 2016.02
	 * @category Developer
	 */
	private $subPartPath = 'client/html/account/download/standard/subparts';
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
		return '';
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
		return '';
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
		/** client/html/account/download/decorators/excludes
		 * Excludes decorators added by the "common" option from the account download html client
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
		 *  client/html/account/download/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2016.02
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/download/decorators/global
		 * @see client/html/account/download/decorators/local
		 */

		/** client/html/account/download/decorators/global
		 * Adds a list of globally available decorators only to the account download html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/account/download/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2016.02
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/download/decorators/excludes
		 * @see client/html/account/download/decorators/local
		 */

		/** client/html/account/download/decorators/local
		 * Adds a list of local decorators only to the account download html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Account\Decorator\*") around the html client.
		 *
		 *  client/html/account/download/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Account\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2016.02
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/account/download/decorators/excludes
		 * @see client/html/account/download/decorators/global
		 */
		return $this->createSubClient( 'account/download/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$context = $this->getContext();

		try
		{
			$view = $this->getView();
			$id = $view->param( 'dl_id' );
			$customerId = $context->getUserId();

			if( $this->checkAccess( $customerId, $id ) === false ) {
				return;
			}

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'order/base/product/attribute' );
			$item = $manager->getItem( $id );

			if( $this->checkDownload( $context->getUserId(), $id ) === true ) {
				$this->addDownload( $item );
			} else {
				$view->response()->withStatus( 403 );
			}

			parent::process();
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() );
		}
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


	protected function addDownload( \Aimeos\MShop\Order\Item\Base\Product\Attribute\Iface $item )
	{
		$fs = $this->getContext()->getFilesystemManager()->get( 'fs-secure' );
		$response = $this->getView()->response();
		$value = (string) $item->getValue();

		if( $fs->has( $value ) )
		{
			$name = $item->getName();

			if( pathinfo( $name, PATHINFO_EXTENSION ) == null
					&& ( $ext = pathinfo( $value, PATHINFO_EXTENSION ) ) != null
			) {
				$name .= '.' . $ext;
			}

			$response->withHeader( 'Content-Description', 'File Transfer' );
			$response->withHeader( 'Content-Type', 'application/octet-stream' );
			$response->withHeader( 'Content-Disposition', 'attachment; filename="' . $name . '"' );
			$response->withHeader( 'Content-Length', $fs->size( $value ) );
			$response->withHeader( 'Cache-Control', 'must-revalidate' );
			$response->withHeader( 'Pragma', 'private' );
			$response->withHeader( 'Expires', 0 );

			$response->withBody( $response->createStream( $fs->reads( $value ) ) );
		}
		elseif( filter_var( $value, FILTER_VALIDATE_URL ) !== false )
		{
			$response->withHeader( 'Location', $value );
			$response->withStatus( 303 );
		}
		else
		{
			$response->withStatus( 404 );
		}
	}


	/**
	 * Checks if the customer is allowed to download the file
	 *
	 * @param string $customerId Unique customer ID
	 * @param string $id Unique order base product attribute ID referencing the download file
	 * @return boolean True if download is allowed, false if not
	 */
	protected function checkAccess( $customerId, $id )
	{
		if( $customerId !== null && $id !== null )
		{
			$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'order/base' );

			$search = $manager->createSearch();
			$expr = array(
				$search->compare( '==', 'order.base.customerid', $customerId ),
				$search->compare( '==', 'order.base.product.attribute.id', $id ),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );
			$search->setSlice( 0, 1 );

			if( count( $manager->searchItems( $search ) ) > 0 ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Updates the download counter for the downloaded file
	 *
	 * @param string $customerId Unique customer ID
	 * @param string $id Unique order base product attribute ID referencing the download file
	 * @return boolean True if download is allowed, false if not
	 */
	protected function checkDownload( $customerId, $id )
	{
		$context = $this->getContext();

		/** client/html/account/download/maxcount
		 * Maximum number of file downloads allowed for an ordered product
		 *
		 * This configuration setting enables you to limit the number of downloads
		 * of a product download file. The count is the maximum number for each
		 * bought product and customer, i.e. setting the count to "3" allows
		 * a customer to download the bought product file up to three times.
		 *
		 * The default value of null enforces no limit.
		 *
		 * @param integer Maximum number of downloads
		 * @since 2016.02
		 * @category Developer
		 * @category User
		 */
		$maxcnt = $context->getConfig()->get( 'client/html/account/download/maxcount' );

		$listItem = $this->getListItem( $customerId, $id );
		$config = $listItem->getConfig();

		if( !isset( $config['count'] ) ) {
			$config['count'] = 0;
		}

		if( $maxcnt === null || ((int) $config['count']) < $maxcnt )
		{
			$config['count']++;
			$listItem->setConfig( $config );

			$manager = \Aimeos\MShop\Factory::createManager( $context, 'customer/lists' );
			$manager->saveItem( $listItem, false );

			return true;
		}

		return false;
	}


	/**
	 * Returns the list item storing the download counter
	 *
	 * @param string $customerId Unique customer ID
	 * @param string $refId Unique order base product attribute ID referencing the download file
	 * @return \Aimeos\MSho\Common\Item\Lists\Iface List item object
	 */
	protected function getListItem( $customerId, $refId )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'customer/lists' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'customer.lists.parentid', $customerId ),
			$search->compare( '==', 'customer.lists.refid', $refId ),
			$search->compare( '==', 'customer.lists.domain', 'order' ),
			$search->compare( '==', 'customer.lists.type.domain', 'order' ),
			$search->compare( '==', 'customer.lists.type.code', 'download' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		$listItems = $manager->searchItems( $search );

		if( ( $listItem = reset( $listItems ) ) === false )
		{
			$listItem = $manager->createItem();
			$listItem->setTypeId( $this->getTypeItem( 'customer/lists/type', 'order', 'download' )->getId() );
			$listItem->setParentId( $customerId );
			$listItem->setDomain( 'order' );
			$listItem->setRefId( $refId );
		}

		return $listItem;
	}
}