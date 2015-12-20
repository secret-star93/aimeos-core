<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Physical;


/**
 * Default implementation of product physical JQAdm client.
 *
 * @package Client
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/physical/standard/subparts
	 * List of JQAdm sub-clients rendered within the product physical section
	 *
	 * The output of the frontend is composed of the code generated by the JQAdm
	 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain JQAdm clients themselves and therefore a
	 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the JQAdm code generated by the parent is printed, then
	 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural JQAdm, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2016.01
	 * @category Developer
	 */
	private $subPartPath = 'admin/jqadm/product/physical/standard/subparts';
	private $subPartNames = array();


	/**
	 * Copies a resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		$view = $this->getView();

		$view->physicalItems = $this->getProperties( $view->item->getId() );
		$view->physicalBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->physicalBody .= $client->copy();
		}

		$tplconf = 'admin/jqadm/product/physical/template-item';
		$default = 'product/item-physical-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function create()
	{
		$view = $this->getView();

		$view->physicalItems = $this->getProperties( $view->item->getId() );
		$view->physicalBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->physicalBody .= $client->create();
		}

		$tplconf = 'admin/jqadm/product/physical/template-item';
		$default = 'product/item-physical-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function get()
	{
		$view = $this->getView();

		$view->physicalItems = $this->getProperties( $view->item->getId() );
		$view->physicalBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->physicalBody .= $client->get();
		}

		$tplconf = 'admin/jqadm/product/physical/template-item';
		$default = 'product/item-physical-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/property' );
		$manager->begin();

		try
		{
			$this->updateProperties( $view );

			$view->physicalBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->physicalBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-physical' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', array() ) + $error;
			$manager->rollback();
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-physical' => $e->getMessage() );
			$view->errors = $view->get( 'errors', array() ) + $error;
			$manager->rollback();
		}

		return $this->create();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/product/physical/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/physical/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "client/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/physical/decorators/global
		 * @see admin/jqadm/product/physical/decorators/local
		 */

		/** admin/jqadm/product/physical/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/physical/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/physical/decorators/excludes
		 * @see admin/jqadm/product/physical/decorators/local
		 */

		/** admin/jqadm/product/physical/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/physical/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/physical/decorators/excludes
		 * @see admin/jqadm/product/physical/decorators/global
		 */
		return $this->createSubClient( 'product/physical/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Returns the list of property items for physical values
	 *
	 * @param string $prodid Unique product ID
	 * @return array List of items implementing \Aimeos\MShop\Product\Property\Iface
	 */
	protected function getProperties( $prodid )
	{
		$list = array();
		$types = array( 'package-length', 'package-width', 'package-height', 'package-weight' );
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/property' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.property.parentid', $prodid ),
			$search->compare( '==', 'product.property.type.code', $types ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		foreach( $manager->searchItems( $search ) as $item ) {
			$list[$item->getType()] = $item;
		}

		return $list;
	}


	/**
	 * Updates existing property items or creates new ones
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 */
	protected function updateProperties( \Aimeos\MW\View\Iface $view )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/property' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/property/type' );

		$ids = array();
		$items = $this->getProperties( $view->item->getId() );

		foreach( (array) $view->param( 'physical', array() ) as $type => $value )
		{
			$value = trim( $value );

			if( $value == '' )
			{
				if( isset( $items[$type] ) ) {
					$ids[] = $items[$type]->getId();
				}
				continue;
			}

			if( !isset( $items[$type] ) )
			{
				$items[$type] = $manager->createItem();
				$items[$type]->setParentId( $view->item->getId() );
				$items[$type]->setTypeId( $typeManager->findItem( $type, array(), 'product/property' )->getId() );
			}

			$items[$type]->setValue( $value );
			$manager->saveItem( $items[$type], false );
		}

		$manager->deleteItems( $ids );
	}
}
