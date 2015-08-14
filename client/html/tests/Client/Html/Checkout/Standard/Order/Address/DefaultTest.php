<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

class Client_Html_Checkout_Standard_Order_Address_DefaultTest extends PHPUnit_Framework_TestCase
{
	private $_object;
	private $_context;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->_context = TestHelper::getContext();

		$paths = TestHelper::getHtmlTemplatePaths();
		$this->_object = new Client_Html_Checkout_Standard_Order_Address_Default( $this->_context, $paths );
		$this->_object->setView( TestHelper::getView() );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		Controller_Frontend_Basket_Factory::createController( $this->_context )->clear();
		unset( $this->_object );
	}


	public function testGetHeader()
	{
		$output = $this->_object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetBody()
	{
		$output = $this->_object->getBody();
		$this->assertNotNull( $output );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->_object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->_object->getSubClient( '$$$', '$$$' );
	}


	public function testProcess()
	{
		$type = MShop_Order_Item_Base_Address_Abstract::TYPE_DELIVERY;
		$manager = MShop_Customer_Manager_Factory::createManager( $this->_context );
		$addrManager = $manager->getSubManager( 'address' );

		$search = $manager->createSearch();
		$search->setSlice( 0, 1 );
		$result = $manager->searchItems( $search );

		if( ( $customerItem = reset( $result ) ) === false ) {
			throw new Exception( 'No customer item found' );
		}

		$addrItem = $customerItem->getPaymentAddress();
		$addrItem->setId( null );

		$basketCntl = Controller_Frontend_Basket_Factory::createController( $this->_context );
		$basketCntl->setAddress( $type, $addrItem );

		$view = TestHelper::getView();
		$view->orderBasket = $basketCntl->get();
		$view->orderBasket->setCustomerId( $customerItem->getId() );
		$this->_object->setView( $view );

		$this->_object->process();

		$orderAddress = $view->orderBasket->getAddress( $type );
		$actual = $addrManager->getItem( $orderAddress->getAddressId() );
		$addrManager->deleteItem( $actual->getId() );

		$this->assertEquals( $addrItem->getFirstname(), $actual->getFirstname() );
		$this->assertEquals( $addrItem->getLastname(), $actual->getLastname() );
		$this->assertEquals( $addrItem->getPostal(), $actual->getPostal() );
		$this->assertEquals( $addrItem->getTelephone(), $actual->getTelephone() );
		$this->assertEquals( $addrItem->getTelefax(), $actual->getTelefax() );
	}
}
