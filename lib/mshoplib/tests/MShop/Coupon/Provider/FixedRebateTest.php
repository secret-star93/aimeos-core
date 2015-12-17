<?php

/**
 * @copyright Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\MShop\Coupon\Provider;


/**
 * Test class for \Aimeos\MShop\Coupon\Provider\FixedRebate.
 * Generated by PHPUnit on 2008-08-06 at 13:07:24.
 */
class FixedRebateTest extends \PHPUnit_Framework_TestCase
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
		$context = \TestHelperMShop::getContext();

		$priceManager = \Aimeos\MShop\Price\Manager\Factory::createManager( $context );
		$couponItem = \Aimeos\MShop\Coupon\Manager\Factory::createManager( $context )->createItem();
		$couponItem->setConfig( array( 'fixedrebate.productcode' => 'U:MD', 'fixedrebate.rebate' => '2.50' ) );

		// Don't create order base item by createItem() as this would already register the plugins
		$this->orderBase = new \Aimeos\MShop\Order\Item\Base\Standard( $priceManager->createItem(), $context->getLocale() );
		$this->object = new \Aimeos\MShop\Coupon\Provider\FixedRebate( $context, $couponItem, 'zyxw' );
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
		$this->object->addCoupon( $this->orderBase );

		$coupons = $this->orderBase->getCoupons();
		$products = $this->orderBase->getProducts();

		if( ( $product = reset( $coupons['zyxw'] ) ) === false ) {
			throw new \Exception( 'No coupon available' );
		}

		$this->assertEquals( 1, count( $products ) );
		$this->assertEquals( '-2.50', $product->getPrice()->getValue() );
		$this->assertEquals( '2.50', $product->getPrice()->getRebate() );
		$this->assertEquals( 'U:MD', $product->getProductCode() );
		$this->assertNotEquals( '', $product->getProductId() );
		$this->assertEquals( '', $product->getSupplierCode() );
		$this->assertEquals( '', $product->getMediaUrl() );
		$this->assertEquals( 'Geldwerter Nachlass', $product->getName() );
	}


	public function testAddCouponMultipleCurrencies()
	{
		$context = \TestHelperMShop::getContext();
		$config = array(
			'fixedrebate.productcode' => 'U:MD',
			'fixedrebate.rebate' => array(
				'EUR' => '1.25',
				'USD' => '1.50',
			),
		);

		$couponItem = \Aimeos\MShop\Coupon\Manager\Factory::createManager( $context )->createItem();
		$couponItem->setConfig( $config );

		$object = new \Aimeos\MShop\Coupon\Provider\FixedRebate( $context, $couponItem, 'zyxw' );

		$object->addCoupon( $this->orderBase );

		$coupons = $this->orderBase->getCoupons();
		$products = $this->orderBase->getProducts();

		if( ( $product = reset( $coupons['zyxw'] ) ) === false ) {
			throw new \Exception( 'No coupon available' );
		}

		$this->assertEquals( 1, count( $products ) );
		$this->assertEquals( '-1.25', $product->getPrice()->getValue() );
		$this->assertEquals( '1.25', $product->getPrice()->getRebate() );
		$this->assertEquals( 'U:MD', $product->getProductCode() );
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

		$context = \TestHelperMShop::getContext();
		$config = array(
			'fixedrebate.productcode' => 'U:MD',
			'fixedrebate.rebate' => array(
				'EUR' => '50.00',
			),
		);

		$couponItem = \Aimeos\MShop\Coupon\Manager\Factory::createManager( $context )->createItem();
		$couponItem->setConfig( $config );

		$object = new \Aimeos\MShop\Coupon\Provider\FixedRebate( $context, $couponItem, 'zyxw' );

		$object->addCoupon( $this->orderBase );

		$coupons = $this->orderBase->getCoupons();
		$products = $this->orderBase->getProducts();

		if( ( $couponProduct20 = reset( $coupons['zyxw'] ) ) === false ) {
			throw new \Exception( 'No coupon available' );
		}

		if( ( $couponProduct10 = end( $coupons['zyxw'] ) ) === false ) {
			throw new \Exception( 'No coupon available' );
		}

		$this->assertEquals( 4, count( $products ) );
		$this->assertEquals( '-37.00', $couponProduct20->getPrice()->getValue() );
		$this->assertEquals( '37.00', $couponProduct20->getPrice()->getRebate() );
		$this->assertEquals( '-13.00', $couponProduct10->getPrice()->getValue() );
		$this->assertEquals( '13.00', $couponProduct10->getPrice()->getRebate() );
	}


	public function testDeleteCoupon()
	{
		$this->object->addCoupon( $this->orderBase );
		$this->object->deleteCoupon( $this->orderBase );

		$products = $this->orderBase->getProducts();
		$coupons = $this->orderBase->getCoupons();

		$this->assertEquals( 0, count( $products ) );
		$this->assertArrayNotHasKey( 'zyxw', $coupons );
	}


	public function testAddCouponInvalidConfig()
	{
		$context = \TestHelperMShop::getContext();
		$couponItem = \Aimeos\MShop\Coupon\Manager\Factory::createManager( \TestHelperMShop::getContext() )->createItem();
		$couponItem->setConfig( array( 'fixedrebate.rebate' => '2.50' ) );

		$object = new \Aimeos\MShop\Coupon\Provider\FixedRebate( $context, $couponItem, 'zyxw' );

		$this->setExpectedException( '\\Aimeos\\MShop\\Coupon\\Exception' );
		$object->addCoupon( $this->orderBase );
	}


	public function testIsAvailable()
	{
		$this->assertTrue( $this->object->isAvailable( $this->orderBase ) );
	}


	protected function getOrderProducts()
	{
		$products = array();
		$manager = \Aimeos\MShop\Factory::createManager( \TestHelperMShop::getContext(), 'order/base/product' );

		$search = $manager->createSearch();
		$search->setConditions( $search->combine( '&&', array(
			$search->compare( '==', 'order.base.product.prodcode', array( 'CNE', 'CNC' ) ),
			$search->compare( '==', 'order.base.product.price', array( '600.00', '36.00' ) )
		) ) );
		$items = $manager->searchItems( $search );

		if( count( $items ) < 2 ) {
			throw new \Exception( 'Please fix the test data in your database.' );
		}

		foreach( $items as $item ) {
			$products[$item->getProductCode()] = $item;
		}

		return $products;
	}
}
