<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Test class for MShop_Coupon_Provider_PercentRebate.
 */
class MShop_Coupon_Provider_PercentRebateTest extends PHPUnit_Framework_TestCase
{
	private $object;
	private $orderBase;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = TestHelper::getContext();

		$priceManager = MShop_Price_Manager_Factory::createManager( $context );
		$couponItem = MShop_Coupon_Manager_Factory::createManager( $context )->createItem();
		$couponItem->setConfig( array( 'percentrebate.productcode' => 'U:MD', 'percentrebate.rebate' => '10' ) );

		// Don't create order base item by createItem() as this would already register the plugins
		$this->orderBase = new MShop_Order_Item_Base_Default( $priceManager->createItem(), $context->getLocale() );
		$this->object = new MShop_Coupon_Provider_PercentRebate( $context, $couponItem, 'zyxw' );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
		unset( $this->orderBase );
	}


	public function testAddCoupon()
	{
		$orderProducts = $this->getOrderProducts();

		$this->orderBase->addProduct( $orderProducts['CNE'] );
		$this->orderBase->addProduct( $orderProducts['CNC'] );

		$this->object->addCoupon( $this->orderBase );

		$coupons = $this->orderBase->getCoupons();
		$products = $this->orderBase->getProducts();

		if( ( $product = reset( $coupons['zyxw'] ) ) === false ) {
			throw new Exception( 'No coupon available' );
		}

		$this->assertEquals( 3, count( $products ) );
		$this->assertEquals( 1, count( $coupons['zyxw'] ) );
		$this->assertEquals( '-66.70', $product->getPrice()->getValue() );
		$this->assertEquals( '66.70', $product->getPrice()->getRebate() );
		$this->assertEquals( 'unitSupplier', $product->getSupplierCode() );
		$this->assertEquals( 'U:MD', $product->getProductCode() );
		$this->assertNotEquals( '', $product->getProductId() );
		$this->assertEquals( '', $product->getMediaUrl() );
		$this->assertEquals( 'Geldwerter Nachlass', $product->getName() );
	}


	public function testAddCouponMultipleTaxRates()
	{
		$products = $this->getOrderProducts();

		$products['CNC']->getPrice()->setTaxRate( '10.00' );
		$products['CNE']->getPrice()->setTaxRate( '20.00' );

		$products['CNC']->setQuantity( 1 );
		$products['CNE']->setQuantity( 1 );

		$this->orderBase->addProduct( $products['CNE'] );
		$this->orderBase->addProduct( $products['CNC'] );

		$this->object->addCoupon( $this->orderBase );

		$coupons = $this->orderBase->getCoupons();
		$products = $this->orderBase->getProducts();

		if( ( $couponProduct20 = reset( $coupons['zyxw'] ) ) === false ) {
			throw new Exception( 'No coupon available' );
		}

		if( ( $couponProduct10 = end( $coupons['zyxw'] ) ) === false ) {
			throw new Exception( 'No coupon available' );
		}

		$this->assertEquals( 4, count( $products ) );
		$this->assertEquals( '-37.00', $couponProduct20->getPrice()->getValue() );
		$this->assertEquals( '37.00', $couponProduct20->getPrice()->getRebate() );
		$this->assertEquals( '-29.70', $couponProduct10->getPrice()->getValue() );
		$this->assertEquals( '29.70', $couponProduct10->getPrice()->getRebate() );
	}


	public function testDeleteCoupon()
	{
		$orderProducts = $this->getOrderProducts();
		$this->orderBase->addProduct( $orderProducts['CNE'] );

		$this->object->addCoupon( $this->orderBase );
		$this->object->deleteCoupon( $this->orderBase );

		$products = $this->orderBase->getProducts();
		$coupons = $this->orderBase->getCoupons();

		$this->assertEquals( 1, count( $products ) );
		$this->assertArrayNotHasKey( 'zyxw', $coupons );
	}


	public function testAddCouponInvalidConfig()
	{
		$context = TestHelper::getContext();
		$couponItem = MShop_Coupon_Manager_Factory::createManager( TestHelper::getContext() )->createItem();

		$object = new MShop_Coupon_Provider_PercentRebate( $context, $couponItem, 'zyxw' );

		$this->setExpectedException( 'MShop_Coupon_Exception' );
		$object->addCoupon( $this->orderBase );
	}


	public function testIsAvailable()
	{
		$this->assertTrue( $this->object->isAvailable( $this->orderBase ) );
	}


	/**
	 * Return the order products.
	 *
	 * @return MShop_Order_Item_Base_Product_Iface[]
	 * @throws Exception
	 */
	protected function getOrderProducts()
	{
		$products = array();
		$manager = MShop_Factory::createManager( TestHelper::getContext(), 'order/base/product' );

		$search = $manager->createSearch();
		$search->setConditions( $search->combine( '&&', array(
			$search->compare( '==', 'order.base.product.prodcode', array( 'CNE', 'CNC' ) ),
			$search->compare( '==', 'order.base.product.price', array( '600.00', '36.00' ) )
		) ) );
		$items = $manager->searchItems( $search );

		if( count( $items ) < 2 ) {
			throw new Exception( 'Please fix the test data in your database.' );
		}

		foreach( $items as $item ) {
			$products[$item->getProductCode()] = $item;
		}

		return $products;
	}
}
