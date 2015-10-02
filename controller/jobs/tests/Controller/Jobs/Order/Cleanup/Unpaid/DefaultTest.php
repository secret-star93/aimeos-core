<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


class Controller_Jobs_Order_Cleanup_Unpaid_DefaultTest
	extends PHPUnit_Framework_TestCase
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

		$this->object = new Controller_Jobs_Order_Cleanup_Unpaid_Default( $context, $aimeos );
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
		$this->assertEquals( 'Removes unpaid orders', $this->object->getName() );
	}


	public function testGetDescription()
	{
		$text = 'Deletes unpaid orders to keep the database clean';
		$this->assertEquals( $text, $this->object->getDescription() );
	}


	public function testRun()
	{
		$context = TestHelper::getContext();
		$aimeos = TestHelper::getAimeos();


		$name = 'ControllerJobsOrderCleanupUnpaidDefaultRun';
		$context->getConfig()->set( 'classes/order/manager/name', $name );
		$context->getConfig()->set( 'classes/controller/common/order/name', $name );


		$orderManagerStub = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setMethods( array( 'searchItems', 'getSubManager' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		$orderBaseManagerStub = $this->getMockBuilder( 'MShop_Order_Manager_Base_Default' )
			->setMethods( array( 'deleteItems' ) )
			->setConstructorArgs( array( $context ) )
			->getMock();

		$orderCntlStub = $this->getMockBuilder( 'Controller_Common_Order_Default' )
		->setMethods( array( 'unblock' ) )
		->setConstructorArgs( array( $context ) )
		->getMock();


		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_' . $name, $orderManagerStub );
		Controller_Common_Order_Factory::injectController( 'Controller_Common_Order_' . $name, $orderCntlStub );


		$orderItem = $orderManagerStub->createItem();
		$orderItem->setBaseId( 1 );
		$orderItem->setId( 2 );


		$orderManagerStub->expects( $this->once() )->method( 'getSubManager' )
			->will( $this->returnValue( $orderBaseManagerStub ) );

		$orderManagerStub->expects( $this->once() )->method( 'searchItems' )
			->will( $this->returnValue( array( $orderItem->getId() => $orderItem ) ) );

		$orderBaseManagerStub->expects( $this->once() )->method( 'deleteItems' );

		$orderCntlStub->expects( $this->once() )->method( 'unblock' );


		$object = new Controller_Jobs_Order_Cleanup_Unpaid_Default( $context, $aimeos );
		$object->run();
	}
}
