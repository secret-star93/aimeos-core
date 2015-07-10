<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Test class for MShop_Order_Item_Base_Service_Default.
 * Generated by Thorsten Stark on 2010-04-09.
 */
class MShop_Order_Item_Base_Service_DefaultTest extends MW_Unittest_Testcase
{
	private $_object;
	private $_values;
	private $_price;
	private $_attribute = array();


	protected function setUp()
	{
		$this->_price = MShop_Price_Manager_Factory::createManager( TestHelper::getContext() )->createItem();

		$attrValues = array(
			'id' => 3,
			'siteid'=>99,
			'ordservid' => 42,
			'name' => 'UnitName',
			'type' => 'default',
			'code' => 'UnitCode',
			'value' => 'UnitValue',
			'mtime' => '2020-12-31 23:59:59',
			'ctime' => '2011-01-01 00:00:01',
			'editor' => 'unitTestUser'
		);

		$this->_attribute = array( 'UnitCode' => new MShop_Order_Item_Base_Service_Attribute_Default( $attrValues ) );

		$this->_values = array(
			'id' => 1,
			'siteid'=>99,
			'servid' => 'ServiceID',
			'baseid' => 42,
			'code' => 'UnitCode',
			'name' => 'UnitName',
			'mediaurl' => 'Url for test',
			'type' => 'payment',
			'mtime' => -99,
			'ctime' => '2011-01-01 00:00:01',
			'editor' => 'unitTestUser'
		);

		$this->_object = new MShop_Order_Item_Base_Service_Default( $this->_price, $this->_values, $this->_attribute );
	}

	protected function tearDown()
	{
		unset($this->_object);
	}

	public function testGetId()
	{
		$this->assertEquals( $this->_values['id'], $this->_object->getId() );
	}

	public function testSetId()
	{
		$this->_object->setId( null );
		$this->assertEquals( null, $this->_object->getId() );
		$this->assertTrue( $this->_object->isModified() );

		$this->_object->setId( 5 );
		$this->assertEquals( 5, $this->_object->getId() );
		$this->assertFalse( $this->_object->isModified() );

		$this->setExpectedException( 'MShop_Exception' );
		$this->_object->setId( 6 );
	}

	public function testSetId2()
	{
		$this->setExpectedException( 'MShop_Exception' );
		$this->_object->setId( 'test' );
	}

	public function testGetSiteId()
	{
		$this->assertEquals( 99, $this->_object->getSiteId() );
	}

	public function testGetBaseId()
	{
		$this->assertEquals( 42, $this->_object->getBaseId() );
	}

	public function testSetBaseId()
	{
		$this->_object->setBaseId( 111 );
		$this->assertEquals( 111, $this->_object->getBaseId() );
		$this->assertTrue( $this->_object->isModified() );
	}

	public function testSetBaseIdReset()
	{
		$this->_object->setBaseId( null );
		$this->assertEquals( null, $this->_object->getBaseId() );
		$this->assertTrue( $this->_object->isModified() );
	}

	public function testGetServiceId()
	{
		$this->assertEquals( $this->_values['servid'], $this->_object->getServiceId() );
	}

	public function testSetServiceId()
	{
		$this->_object->setServiceId( 'testServiceID' );
		$this->assertEquals( 'testServiceID', $this->_object->getServiceId() );
	}

	public function testGetCode()
	{
		$this->assertEquals( $this->_values['code'], $this->_object->getCode() );
	}

	public function testSetCode()
	{
		$this->_object->setCode( 'testCode' );
		$this->assertEquals( 'testCode', $this->_object->getCode() );
	}

	public function testGetName()
	{
		$this->assertEquals( $this->_values['name'], $this->_object->getName() );
	}

	public function testSetName()
	{
		$this->_object->setName( 'testName' );
		$this->assertEquals( 'testName', $this->_object->getName() );
		$this->assertTrue( $this->_object->isModified() );
	}

	public function testGetMediaUrl()
	{
		$this->assertEquals($this->_values['mediaurl'], $this->_object->getMediaUrl());
	}

	public function testSetMediaUrl()
	{
		$this->_object->setMediaUrl('testUrl');
		$this->assertEquals('testUrl', $this->_object->getMediaUrl());
		$this->assertTrue($this->_object->isModified());
	}

	public function testGetType()
	{
		$this->assertEquals( $this->_values['type'], $this->_object->getType() );
	}

	public function testSetType()
	{
		$this->_object->setType( 'delivery' );
		$this->assertEquals( 'delivery', $this->_object->getType() );
		$this->assertTrue( $this->_object->isModified() );
	}

	public function testGetPrice()
	{
		$this->assertSame( $this->_price, $this->_object->getPrice() );
	}

	public function testSetPrice()
	{
		$this->_price->setCosts( '5.00' );
		$this->_object->setPrice( $this->_price );
		$this->assertFalse( $this->_object->isModified() );
		$this->assertSame($this->_price, $this->_object->getPrice());
	}

	public function testGetAttribute()
	{
		$manager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$attManager = $manager->getSubManager( 'base' )->getSubManager( 'service' )->getSubManager( 'attribute' );

		$attrItem001 = $attManager->createItem();
		$attrItem001->setCode( 'code_001' );
		$attrItem001->setValue( 'value_001' );

		$attrItem002 = $attManager->createItem();
		$attrItem002->setCode( 'code_002' );
		$attrItem002->setType( 'test_002' );
		$attrItem002->setValue( 'value_002' );

		$this->_object->setAttributes( array( $attrItem001, $attrItem002 ) );

		$result = $this->_object->getAttribute( 'code_001' );
		$this->assertEquals( 'value_001', $result );

		$result = $this->_object->getAttribute( 'code_002', 'test_002' );
		$this->assertEquals( 'value_002', $result );

		$result = $this->_object->getAttribute( 'code_002' );
		$this->assertEquals( null, $result );

		$result = $this->_object->getAttribute( 'code_003' );
		$this->assertEquals( null, $result );

		$this->_object->setAttributes( array() );

		$result = $this->_object->getAttribute( 'code_001' );
		$this->assertEquals( null, $result );
	}

	public function testGetAttributeItem()
	{
		$manager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$attManager = $manager->getSubManager( 'base' )->getSubManager( 'service' )->getSubManager( 'attribute' );

		$attrItem001 = $attManager->createItem();
		$attrItem001->setCode( 'code_001');
		$attrItem001->setValue( 'value_001');

		$attrItem002 = $attManager->createItem();
		$attrItem002->setCode( 'code_002');
		$attrItem002->setType( 'test_002' );
		$attrItem002->setValue( 'value_002');

		$this->_object->setAttributes( array( $attrItem001, $attrItem002 ) );

		$result = $this->_object->getAttributeItem( 'code_001' );
		$this->assertEquals( 'value_001', $result->getValue() );

		$result = $this->_object->getAttributeItem( 'code_002', 'test_002' );
		$this->assertEquals( 'value_002', $result->getValue() );

		$result = $this->_object->getAttributeItem( 'code_002' );
		$this->assertEquals( null, $result );

		$result = $this->_object->getAttributeItem( 'code_003' );
		$this->assertEquals( null, $result );

		$this->_object->setAttributes( array() );

		$result = $this->_object->getAttributeItem( 'code_001' );
		$this->assertEquals( null, $result );
	}

	public function testGetAttributes()
	{
		$this->assertEquals( $this->_attribute, $this->_object->getAttributes() );
	}

	public function testGetAttributesByType()
	{
		$this->assertEquals( $this->_attribute, $this->_object->getAttributes( 'default' ) );
	}

	public function testGetAttributesInvalidType()
	{
		$this->assertEquals( array(), $this->_object->getAttributes( 'invalid' ) );
	}

	public function testSetAttributeItem()
	{
		$manager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$attManager = $manager->getSubManager( 'base' )->getSubManager( 'service' )->getSubManager( 'attribute' );

		$item = $attManager->createItem();
		$item->setCode( 'test_code' );
		$item->setType( 'test_type' );
		$item->setValue( 'test_value' );

		$this->_object->setAttributeItem( $item );

		$this->assertEquals( true, $this->_object->isModified() );
		$this->assertEquals( 'test_value', $this->_object->getAttributeItem( 'test_code', 'test_type' )->getValue() );

		$item = $attManager->createItem();
		$item->setCode( 'test_code' );
		$item->setType( 'test_type' );
		$item->setValue( 'test_value2' );

		$this->_object->setAttributeItem( $item );

		$this->assertEquals( 'test_value2', $this->_object->getAttributeItem( 'test_code', 'test_type' )->getValue() );
	}

	public function testSetAttributes()
	{
		$manager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$attManager = $manager->getSubManager( 'base' )->getSubManager( 'service' )->getSubManager( 'attribute' );

		$list = array(
			$attManager->createItem(),
			$attManager->createItem(),
		);

		$this->_object->setAttributes( $list );

		$this->assertEquals( true, $this->_object->isModified() );
		$this->assertEquals( $list, $this->_object->getAttributes() );
	}

	public function testGetTimeModified()
	{
		$this->assertEquals( $this->_values['mtime'], $this->_object->getTimeModified() );
	}

	public function testGetTimeCreated()
	{
		$this->assertEquals( '2011-01-01 00:00:01', $this->_object->getTimeCreated() );
	}

	public function testGetEditor()
	{
		$this->assertEquals( 'unitTestUser', $this->_object->getEditor() );
	}


	public function testFromArray()
	{
		$item = new MShop_Order_Item_Base_Service_Default(new MShop_Price_Item_Default());

		$list = array(
			'order.base.service.id' => 1,
			'order.base.service.baseid' => 2,
			'order.base.service.serviceid' => 3,
			'order.base.service.code' => 'test',
			'order.base.service.name' => 'test item',
			'order.base.service.type' => 'delivery',
		);

		$unknown = $item->fromArray($list);

		$this->assertEquals(array(), $unknown);

		$this->assertEquals($list['order.base.service.id'], $item->getId());
		$this->assertEquals($list['order.base.service.baseid'], $item->getBaseId());
		$this->assertEquals($list['order.base.service.serviceid'], $item->getServiceId());
		$this->assertEquals($list['order.base.service.code'], $item->getCode());
		$this->assertEquals($list['order.base.service.name'], $item->getName());
		$this->assertEquals($list['order.base.service.type'], $item->getType());
	}


	public function testToArray()
	{
		$arrayObject = $this->_object->toArray();
		$this->assertEquals( count( $this->_values ) + 4, count( $arrayObject ) );

		$this->assertEquals( $this->_object->getId(), $arrayObject['order.base.service.id'] );
		$this->assertEquals( $this->_object->getBaseId(), $arrayObject['order.base.service.baseid'] );
		$this->assertEquals( $this->_object->getServiceId(), $arrayObject['order.base.service.serviceid'] );
		$this->assertEquals( $this->_object->getCode(), $arrayObject['order.base.service.code'] );
		$this->assertEquals( $this->_object->getName(), $arrayObject['order.base.service.name'] );
		$this->assertEquals( $this->_object->getType(), $arrayObject['order.base.service.type'] );
		$this->assertEquals( $this->_object->getTimeModified(), $arrayObject['order.base.service.mtime'] );
		$this->assertEquals( $this->_object->getTimeCreated(), $arrayObject['order.base.service.ctime'] );
		$this->assertEquals( $this->_object->getTimeModified(), $arrayObject['order.base.service.mtime'] );
		$this->assertEquals( $this->_object->getEditor(), $arrayObject['order.base.service.editor'] );

		$price = $this->_object->getPrice();
		$this->assertEquals( $price->getValue(), $arrayObject['order.base.service.price'] );
		$this->assertEquals( $price->getCosts(), $arrayObject['order.base.service.costs'] );
		$this->assertEquals( $price->getRebate(), $arrayObject['order.base.service.rebate'] );
		$this->assertEquals( $price->getTaxRate(), $arrayObject['order.base.service.taxrate'] );
	}

	public function testIsModified()
	{
		$this->assertFalse($this->_object->isModified());
	}


	public function testCopyFrom()
	{
		$serviceCopy = new MShop_Order_Item_Base_Service_Default( $this->_price );

		$manager = MShop_Service_Manager_Factory::createManager( TestHelper::getContext() );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'service.provider', 'default') );
		$services = $manager->searchItems( $search );

		if( ( $service = reset( $services ) ) !== false ) {
			$serviceCopy->copyFrom( $service );
		}

		$this->assertEquals( 'unitcode', $serviceCopy->getCode() );
		$this->assertEquals( 'unitlabel', $serviceCopy->getName() );
		$this->assertEquals( 'delivery', $serviceCopy->getType() );
		$this->assertEquals( '', $serviceCopy->getMediaUrl() );

		$this->assertTrue( $serviceCopy->isModified() );
	}
}
