<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2017
 */


namespace Aimeos\MShop\Coupon\Provider;


class ExampleTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $orderBase;


	protected function setUp()
	{
		$context = \TestHelperMShop::getContext();
		$priceManager = \Aimeos\MShop\Price\Manager\Factory::createManager( $context );
		$item = \Aimeos\MShop\Coupon\Manager\Factory::createManager( $context )->createItem();

		// Don't create order base item by createItem() as this would already register the plugins
		$this->orderBase = new \Aimeos\MShop\Order\Item\Base\Standard( $priceManager->createItem(), $context->getLocale() );
		$this->object = new \Aimeos\MShop\Coupon\Provider\Example( $context, $item, '90AB' );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->orderBase );
	}


	public function testAddCoupon()
	{
		$this->object->addCoupon( $this->orderBase );
	}

	public function testDeleteCoupon()
	{
		$this->object->deleteCoupon( $this->orderBase );
	}

	public function testUpdateCoupon()
	{
		$this->object->updateCoupon( $this->orderBase );
	}

	public function testIsAvailable()
	{
		$this->assertTrue( $this->object->isAvailable( $this->orderBase ) );
	}

	public function testIsAvailableFalse()
	{
		$context = \TestHelperMShop::getContext();
		$item = \Aimeos\MShop\Coupon\Manager\Factory::createManager( $context )->createItem();
		$object = new \Aimeos\MShop\Coupon\Provider\Example( $context, $item, '5678' );

		$this->assertFalse( $object->isAvailable( $this->orderBase ) );
	}

	public function testSetObject()
	{
		$this->object->setObject( $this->object );
	}
}
