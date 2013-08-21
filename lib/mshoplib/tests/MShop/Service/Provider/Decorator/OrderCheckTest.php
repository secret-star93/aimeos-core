<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://www.arcavias.com/en/license
 */


/**
 * Test class for MShop_Service_Provider_Decorator_OrderCheck.
 */
class MShop_Service_Provider_Decorator_OrderCheckTest extends MW_Unittest_Testcase
{
	private $_object;
	private $_basket;
	private $_context;
	private $_servItem;
	private $_mockProvider;


	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	public static function main()
	{
		require_once 'PHPUnit/TextUI/TestRunner.php';

		$suite  = new PHPUnit_Framework_TestSuite('MShop_Service_Provider_Decorator_OrderCheckTest');
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	protected function setUp()
	{
		$this->_context = TestHelper::getContext();

		$servManager = MShop_Service_Manager_Factory::createManager( $this->_context );
		$this->_servItem = $servManager->createItem();

		$this->_servItem->setConfig( array( 'ordercheck.total-number-min' => 1 ) );

		$this->_mockProvider = $this->getMockBuilder( 'MShop_Service_Provider_Decorator_OrderCheck' )
			->disableOriginalConstructor()->getMock();

		$this->_basket = MShop_Order_Manager_Factory::createManager( $this->_context )
			->getSubManager( 'base' )->createItem();

		$this->_object = new MShop_Service_Provider_Decorator_OrderCheck( $this->_context, $this->_servItem, $this->_mockProvider );
	}


	public function testGetConfigBE()
	{
		$this->assertArrayHasKey( 'ordercheck.total-number-min', $this->_object->getConfigBE() );
	}


	public function testCheckConfigBEWithZero()
	{
		$this->_mockProvider->expects( $this->once() )
			->method( 'checkConfigBE' )
			->will( $this->returnValue( array() ) );

		$attributes = array( 'ordercheck.total-number-min' => '0' );
		$result = $this->_object->checkConfigBE( $attributes );

		$this->assertEquals( 1, count( $result ) );
		$this->assertInternalType( 'null', $result['ordercheck.total-number-min'] );
	}


	public function testCheckConfigBEWithOne()
	{
		$this->_mockProvider->expects( $this->once() )
			->method( 'checkConfigBE' )
			->will( $this->returnValue( array() ) );

		$attributes = array( 'ordercheck.total-number-min' => '1' );
		$result = $this->_object->checkConfigBE( $attributes );

		$this->assertEquals( 1, count( $result ) );
		$this->assertInternalType( 'null', $result['ordercheck.total-number-min'] );
	}


	public function testCheckConfigBEWithString()
	{
		$this->_mockProvider->expects( $this->once() )
			->method( 'checkConfigBE' )
			->will( $this->returnValue( array() ) );

		$attributes = array( 'ordercheck.total-number-min' => 'nope' );
		$result = $this->_object->checkConfigBE( $attributes );

		$this->assertEquals( 1, count( $result ) );
		$this->assertInternalType( 'string', $result['ordercheck.total-number-min'] );
	}


	public function testIsAvailableNoUserId()
	{
		$this->assertFalse( $this->_object->isAvailable( $this->_basket ) );
	}


	public function testIsAvailableNoConfig()
	{
		$this->_context->setUserId( 1 );
		$this->_servItem->setConfig( array() );

		$this->_mockProvider->expects( $this->once() )
			->method( 'isAvailable' )
			->will( $this->returnValue( true ) );

		$this->assertTrue( $this->_object->isAvailable( $this->_basket ) );
	}


	public function testIsAvailableNotEnough()
	{
		$this->_context->setUserId( 1 );

		$mock = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setConstructorArgs( array( $this->_context ) )
			->setMethods( array( 'searchItems' ) )
			->getMock();

		$mock->expects( $this->once() )
			->method( 'searchItems' )
			->will( $this->returnValue( array() ) );

		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_Default', $mock );

		$this->assertFalse( $this->_object->isAvailable( $this->_basket ) );
	}


	public function testIsAvailableOK()
	{
		$this->_context->setUserId( 1 );

		$mock = $this->getMockBuilder( 'MShop_Order_Manager_Default' )
			->setConstructorArgs( array( $this->_context ) )
			->setMethods( array( 'searchItems' ) )
			->getMock();

		$mock->expects( $this->once() )
			->method( 'searchItems' )
			->will( $this->returnValue( array( $mock->createItem() ) ) );

		MShop_Order_Manager_Factory::injectManager( 'MShop_Order_Manager_Default', $mock );

		$this->_mockProvider->expects( $this->once() )
			->method( 'isAvailable' )
			->will( $this->returnValue( true ) );

		$this->assertTrue( $this->_object->isAvailable( $this->_basket ) );
	}
}