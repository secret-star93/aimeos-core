<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

class Client_Html_Basket_Mini_DefaultTest extends PHPUnit_Framework_TestCase
{
	private $object;
	private $context;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->context = TestHelper::getContext();

		$paths = TestHelper::getHtmlTemplatePaths();
		$this->object = new Client_Html_Basket_Mini_Default( $this->context, $paths );
		$this->object->setView( TestHelper::getView() );
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
	}


	public function testGetHeader()
	{
		$output = $this->object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetBody()
	{
		$output = $this->object->getBody();
		$miniBasket = $this->object->getView()->miniBasket;
		$this->assertTrue( $miniBasket instanceof MShop_Order_Item_Base_Iface );
		$this->assertStringStartsWith( '<section class="aimeos basket-mini">', $output );
	}


	public function testGetSubClient()
	{
		$client = $this->object->getSubClient( 'main', 'Default' );
		$this->assertInstanceOf( 'Client_HTML_Iface', $client );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->object->getSubClient( '$$$', '$$$' );
	}


	public function testProcess()
	{
		$this->object->process();
	}
}