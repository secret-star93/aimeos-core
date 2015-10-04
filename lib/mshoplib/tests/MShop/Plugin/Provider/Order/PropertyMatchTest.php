<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

/**
 * Test class for MShop_Plugin_Provider_Order_PropertyMatch.
 */
class MShop_Plugin_Provider_Order_PropertyMatchTest extends PHPUnit_Framework_TestCase
{
	private $object;
	private $plugin;
	private $order;
	private $products;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$pluginManager = MShop_Plugin_Manager_Factory::createManager( TestHelper::getContext() );
		$this->plugin = $pluginManager->createItem();
		$this->plugin->setTypeId( 2 );
		$this->plugin->setProvider( 'PropertyMatch' );
		$this->plugin->setConfig( array( 'product.suppliercode' => 'unitSupplier' ) );
		$this->plugin->setStatus( '1' );


		$orderManager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$orderBaseManager = $orderManager->getSubManager( 'base' );
		$orderBaseProductManager = $orderBaseManager->getSubManager( 'product' );

		$manager = MShop_Product_Manager_Factory::createManager( TestHelper::getContext() );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', array( 'CNE', 'CNC' ) ) );

		$products = $manager->searchItems( $search );

		if( count( $products ) !== 2 ) {
			throw new Exception( 'Wrong number of products' );
		}

		$this->products = array();

		foreach( $products as $product )
		{
			$item = $orderBaseProductManager->createItem();
			$item->copyFrom( $product );

			$this->products[$product->getCode()] = $item;
		}

		$this->order = $orderBaseManager->createItem();

		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object, $this->order, $this->plugin, $this->products );
	}


	public function testRegister()
	{
		$this->object->register( $this->order );
	}


	public function testUpdateOk()
	{
		// single condition
		$this->assertTrue( $this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] ) );

		$this->plugin->setConfig( array( 'product.stock.warehouse.code' => 'default' ) );
		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

		$this->assertTrue( $this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] ) );


		// two conditions
		$this->plugin->setConfig( array(
			'product.stock.warehouse.code' => 'default',
			'product.suppliercode' => 'unitSupplier',
		) );
		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

		$this->assertTrue( $this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] ) );
	}


	public function testUpdateFail()
	{
		$this->plugin->setConfig( array( 'product.suppliercode' => 'wrongSupplier' ) );
		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

		$this->setExpectedException( 'MShop_Plugin_Exception' );
		$this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] );
	}


	public function testUpdateFailMultipleConditions()
	{
		$this->plugin->setConfig( array(
			'product.stock.warehouse.code' => 'unit_warehouse2',
			'product.suppliercode' => 'wrongSupplier',
		) );
		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

		$this->setExpectedException( 'MShop_Plugin_Exception' );
		$this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] );
	}

	public function testUpdateFailList()
	{
		$this->plugin->setConfig( array( 'product.lists.domain' => 'foobar' ) );
		$this->object = new MShop_Plugin_Provider_Order_PropertyMatch( TestHelper::getContext(), $this->plugin );

		$this->setExpectedException( 'MShop_Plugin_Exception' );
		$this->object->update( $this->order, 'addProduct.before', $this->products['CNC'] );
	}
}
