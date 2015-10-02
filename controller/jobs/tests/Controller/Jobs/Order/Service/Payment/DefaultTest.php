<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


class Controller_Jobs_Order_Service_Payment_DefaultTest extends PHPUnit_Framework_TestCase
{
	private $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = TestHelper::getContext();
		$aimeos = TestHelper::getAimeos();

		$this->object = new Controller_Jobs_Order_Service_Payment_Default( $context, $aimeos );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		$this->object = null;
	}


	public function testGetName()
	{
		$this->assertEquals( 'Capture authorized payments', $this->object->getName() );
	}


	public function testGetDescription()
	{
		$text = 'Authorized payments of orders will be captured after dispatching or after a configurable amount of time';
		$this->assertEquals( $text, $this->object->getDescription() );
	}


	public function testRun()
	{
		$context = TestHelper::getContext();
		$aimeos = TestHelper::getAimeos();


		$name = 'ControllerJobsServicePaymentProcessDefaultRun';
		$context->getConfig()->set( 'classes/service/manager/name', $name );
		$context->getConfig()->set( 'classes/order/manager/name', $name );


		$serviceManagerStub = $this->getMockBuilder( 'MShop_Service_Manager_Default' )
			->setMethods( array( 'getProvider', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		$orderManagerStub = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setMethods( array( 'saveItem', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		MShop_Service_Manager_Factory::injectManager( 'MShop_Service_Manager_' . $name, $serviceManagerStub );
		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_' . $name, $orderManagerStub );


		$serviceItem = $serviceManagerStub->createItem();
		$orderItem = $orderManagerStub->createItem();

		$serviceProviderStub = $this->getMockBuilder( 'MShop_Service_Provider_Payment_PrePay' )
			->setMethods( array( 'isImplemented', 'capture' ) )
			->setConstructorArgs( array( $context, $serviceItem ) )
			->getMock();


		$serviceManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->onConsecutiveCalls( array( $serviceItem ), array() ) );

		$serviceManagerStub->expects( $this->once() )->method( 'getProvider' )
			->will( $this->returnValue( $serviceProviderStub ) );

		$orderManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->onConsecutiveCalls( array( $orderItem ), array() ) );

		$serviceProviderStub->expects( $this->once() )->method( 'isImplemented' )
			->will( $this->returnValue( true ) );

		$serviceProviderStub->expects( $this->once() )->method( 'capture' );


		$object = new Controller_Jobs_Order_Service_Payment_Default( $context, $aimeos );
		$object->run();
	}


	public function testRunExceptionProcess()
	{
		$context = TestHelper::getContext();
		$aimeos = TestHelper::getAimeos();


		$name = 'ControllerJobsServicePaymentProcessDefaultRun';
		$context->getConfig()->set( 'classes/service/manager/name', $name );
		$context->getConfig()->set( 'classes/order/manager/name', $name );


		$orderManagerStub = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setMethods( array( 'saveItem', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		$serviceManagerStub = $this->getMockBuilder( 'MShop_Service_Manager_Default' )
			->setMethods( array( 'getProvider', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		MShop_Service_Manager_Factory::injectManager( 'MShop_Service_Manager_' . $name, $serviceManagerStub );
		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_' . $name, $orderManagerStub );


		$serviceItem = $serviceManagerStub->createItem();
		$orderItem = $orderManagerStub->createItem();

		$serviceProviderStub = $this->getMockBuilder( 'MShop_Service_Provider_Payment_PrePay' )
			->setMethods( array( 'isImplemented', 'capture' ) )
			->setConstructorArgs( array( $context, $serviceItem ) )
			->getMock();


		$serviceManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->onConsecutiveCalls( array( $serviceItem ), array() ) );

		$serviceManagerStub->expects( $this->once() )->method( 'getProvider' )
			->will( $this->returnValue( $serviceProviderStub ) );

		$orderManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->onConsecutiveCalls( array( $orderItem ), array() ) );

		$serviceProviderStub->expects( $this->once() )->method( 'isImplemented' )
			->will( $this->returnValue( true ) );

		$serviceProviderStub->expects( $this->once() )->method( 'capture' )
			->will( $this->throwException( new MShop_Service_Exception( 'test oder service payment: capture' ) ) );

		$orderManagerStub->expects( $this->never() )->method( 'saveItem' );


		$object = new Controller_Jobs_Order_Service_Payment_Default( $context, $aimeos );
		$object->run();
	}


	public function testRunExceptionProvider()
	{
		$context = TestHelper::getContext();
		$aimeos = TestHelper::getAimeos();


		$name = 'ControllerJobsServicePaymentProcessDefaultRun';
		$context->getConfig()->set( 'classes/service/manager/name', $name );
		$context->getConfig()->set( 'classes/order/manager/name', $name );


		$orderManagerStub = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setMethods( array( 'saveItem', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		$serviceManagerStub = $this->getMockBuilder( 'MShop_Service_Manager_Default' )
			->setMethods( array( 'getProvider', 'searchItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		MShop_Service_Manager_Factory::injectManager( 'MShop_Service_Manager_' . $name, $serviceManagerStub );
		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_' . $name, $orderManagerStub );


		$serviceItem = $serviceManagerStub->createItem();

		$serviceManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->onConsecutiveCalls( array( $serviceItem ), array() ) );

		$serviceManagerStub->expects( $this->once() )->method( 'getProvider' )
			->will( $this->throwException( new MShop_Service_Exception( 'test service delivery process: getProvider' ) ) );

		$orderManagerStub->expects( $this->never() )->method( 'searchItems' );


		$object = new Controller_Jobs_Order_Service_Payment_Default( $context, $aimeos );
		$object->run();
	}
}
