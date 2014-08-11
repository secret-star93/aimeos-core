<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/en/license
 */


/**
 * Test class for MShop_Coupon_Provider_FreeShipping.
 * Generated by PHPUnit on 2008-08-06 at 13:07:41.
 */
class MShop_Coupon_Provider_FreeShippingTest extends MW_Unittest_Testcase
{
	private $_object;
	private $_orderBase;


	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	public static function main()
	{
		require_once 'PHPUnit/TextUI/TestRunner.php';

		$suite  = new PHPUnit_Framework_TestSuite('MShop_Coupon_Provider_FreeShippingTest');
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = TestHelper::getContext();


		$couponManager = MShop_Coupon_Manager_Factory::createManager( $context );
		$search = $couponManager->createSearch();
		$search->setConditions( $search->compare( '==', 'coupon.code.code', 'CDEF') );
		$results = $couponManager->searchItems( $search );

		if( ( $couponItem = reset( $results ) ) === false ) {
			throw new Exception( 'No coupon item found' );
		}

		$this->_object = new MShop_Coupon_Provider_FreeShipping( $context, $couponItem, 'CDEF' );


		$orderManager = MShop_Order_Manager_Factory::createManager( $context );
		$orderBaseManager = $orderManager->getSubManager('base');
		$orderProductManager = $orderBaseManager->getSubManager( 'product' );


		$productManager = MShop_Product_Manager_Factory::createManager( $context );
		$search = $productManager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', array( 'CNC' ) ) );
		$products = $productManager->searchItems( $search, array('price') );

		$priceIds = $priceMap = array();

		foreach( $products as $product )
		{
			foreach ( $product->getListItems( 'price' ) AS $listItem )
			{
				$priceIds[] = $listItem->getRefId();
				$priceMap[ $listItem->getRefId() ] = $product->getCode();
			}

			$orderProduct = $orderProductManager->createItem();
			$orderProduct->setName( $product->getName() );
			$orderProduct->setProductCode( $product->getCode() );
			$orderProduct->setQuantity( 1 );

			$this->orderProducts[ $product->getCode() ] = $orderProduct;
		}

		$priceManager = MShop_Price_Manager_Factory::createManager( $context );
		$search = $priceManager->createSearch();
		$expr = array(
			$search->compare( '==', 'price.id', $priceIds ),
			$search->compare( '==', 'price.quantity', 1 ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		foreach( $priceManager->searchItems( $search ) as $priceItem )
		{
			$productCode = $priceMap[ $priceItem->getId() ];
			$this->orderProducts[ $productCode ]->setPrice( $priceItem );
		}


		$delPrice = MShop_Price_Manager_Factory::createManager( $context )->createItem();
		$delPrice->setCosts('5.00');
		$delPrice->setCurrencyId('EUR');

		$orderBaseServiceManager = $orderBaseManager->getSubManager('service');
		$delivery = $orderBaseServiceManager->createItem();
		$delivery->setCode('73');
		$delivery->setType('delivery');
		$delivery->setPrice($delPrice);


		// Don't create order base item by createItem() as this would already register the plugins
		$this->_orderBase = new MShop_Order_Item_Base_Default( $priceManager->createItem(), $context->getLocale() );
		$this->_orderBase->setService( $delivery, 'delivery' );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->_object );
	}


	public function testAddCoupon()
	{
		$this->_orderBase->addProduct( $this->orderProducts['CNC'] );

		$this->_object->addCoupon( $this->_orderBase );
		$coupons = $this->_orderBase->getCoupons();

		if( ( $product = reset( $coupons['CDEF'] ) ) === false ) {
			throw new Exception( 'No coupon available' );
		}

		$delivery = $this->_orderBase->getService( 'delivery' );
		$this->assertEquals( 2, count( $this->_orderBase->getProducts() ) );
		$this->assertEquals( '-5.00', $product->getPrice()->getCosts() );
		$this->assertEquals( '5.00', $product->getPrice()->getRebate() );
		$this->assertEquals( 'unitSupplier', $product->getSupplierCode() );
		$this->assertEquals( 'U:SD', $product->getProductCode() );
		$this->assertNotEquals( '', $product->getProductId() );
		$this->assertEquals( '', $product->getMediaUrl() );
		$this->assertEquals( 'Versandkosten Nachlass', $product->getName() );
	}


	public function testDeleteCoupon()
	{
		$this->_orderBase->addProduct( $this->orderProducts['CNC'] );

		$this->_object->addCoupon( $this->_orderBase );
		$this->_object->deleteCoupon($this->_orderBase);

		$products = $this->_orderBase->getProducts();
		$coupons = $this->_orderBase->getCoupons();

		$this->assertEquals( 1, count( $products ) );
		$this->assertArrayNotHasKey( 'CDEF', $coupons );
	}


	public function testAddCouponInvalidConfig()
	{
		$context = TestHelper::getContext();
		$this->manager = MShop_Coupon_Manager_Factory::createManager( TestHelper::getContext() );
		$couponItem=$this->manager->createItem();

		$outer = null;
		$this->manager = new MShop_Coupon_Provider_FreeShipping( $context, $couponItem, '5678', $outer );

		$this->setExpectedException('MShop_Coupon_Exception');
		$this->manager->addCoupon($this->_orderBase);
	}

	public function testIsAvailable()
	{
		$this->assertTrue( $this->_object->isAvailable( $this->_orderBase ) );
	}

}
