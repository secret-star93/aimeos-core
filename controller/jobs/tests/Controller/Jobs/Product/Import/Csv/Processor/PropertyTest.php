<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


class Controller_Jobs_Property_Import_Csv_Processor_PropertyTest extends MW_Unittest_Testcase
{
	private $_context;
	private $_endpoint;


	protected function setUp()
	{
		MShop_Factory::setCache( true );

		$this->_context = TestHelper::getContext();
		$this->_endpoint = new Controller_Jobs_Product_Import_Csv_Processor_Done( $this->_context, array() );
	}


	protected function tearDown()
	{
		MShop_Factory::setCache( false );
		MShop_Factory::clear();
	}


	public function testProcess()
	{
		$mapping = array(
			0 => 'product.property.type',
			1 => 'product.property.value',
			2 => 'product.property.languageid',
			3 => 'product.property.type',
			4 => 'product.property.value',
		);

		$data = array(
			0 => 'package-weight',
			1 => '3.00',
			2 => 'de',
			3 => 'package-width',
			4 => '50',
		);

		$product = $this->_create( 'job_csv_test' );

		$object = new Controller_Jobs_Product_Import_Csv_Processor_Property( $this->_context, $mapping, $this->_endpoint );
		$result = $object->process( $product, $data );

		$product = $this->_get( 'job_csv_test' );
		$items = $this->_getProperties( $product->getId() );
		$this->_delete( $product );


		$pos = 0;
		$expected = array(
			array( 'package-weight', '3.00', 'de' ),
			array( 'package-width', '50', null ),
		);

		$this->assertEquals( 2, count( $items ) );

		foreach( $items as $item )
		{
			$this->assertEquals( $expected[$pos][0], $item->getType() );
			$this->assertEquals( $expected[$pos][1], $item->getValue() );
			$this->assertEquals( $expected[$pos][2], $item->getLanguageId() );
			$pos++;
		}
	}


	public function testProcessUpdate()
	{
		$mapping = array(
			0 => 'product.property.type',
			1 => 'product.property.value',
		);

		$data = array(
			0 => 'package-weight',
			1 => '3.00',
		);

		$dataUpdate = array(
			0 => 'package-height',
			1 => '10',
		);

		$product = $this->_create( 'job_csv_test' );

		$object = new Controller_Jobs_Product_Import_Csv_Processor_Property( $this->_context, $mapping, $this->_endpoint );
		$result = $object->process( $product, $data );

		$product = $this->_get( 'job_csv_test' );

		$result = $object->process( $product, $dataUpdate );

		$product = $this->_get( 'job_csv_test' );
		$items = $this->_getProperties( $product->getId() );
		$this->_delete( $product );


		$item = reset( $items );

		$this->assertEquals( 1, count( $items ) );
		$this->assertInstanceOf( 'MShop_Product_Item_Property_Interface', $item );

		$this->assertEquals( 'package-height', $item->getType() );
		$this->assertEquals( '10', $item->getValue() );
	}


	public function testProcessDelete()
	{
		$mapping = array(
			0 => 'product.property.type',
			1 => 'product.property.value',
		);

		$data = array(
			0 => 'package-weight',
			1 => '3.00',
		);

		$product = $this->_create( 'job_csv_test' );

		$object = new Controller_Jobs_Product_Import_Csv_Processor_Property( $this->_context, $mapping, $this->_endpoint );
		$result = $object->process( $product, $data );

		$product = $this->_get( 'job_csv_test' );

		$object = new Controller_Jobs_Product_Import_Csv_Processor_Property( $this->_context, array(), $this->_endpoint );
		$result = $object->process( $product, array() );

		$product = $this->_get( 'job_csv_test' );
		$items = $this->_getProperties( $product->getId() );
		$this->_delete( $product );


		$this->assertEquals( 0, count( $items ) );
	}


	public function testProcessEmpty()
	{
		$mapping = array(
			0 => 'product.property.type',
			1 => 'product.property.value',
			2 => 'product.property.type',
			3 => 'product.property.value',
		);

		$data = array(
			0 => '',
			1 => '',
			2 => 'package-weight',
			3 => '3.00',
		);

		$product = $this->_create( 'job_csv_test' );

		$object = new Controller_Jobs_Product_Import_Csv_Processor_Property( $this->_context, $mapping, $this->_endpoint );
		$result = $object->process( $product, $data );

		$product = $this->_get( 'job_csv_test' );
		$items = $this->_getProperties( $product->getId() );
		$this->_delete( $product );


		$this->assertEquals( 1, count( $items ) );
	}


	protected function _create( $code )
	{
		$manager = MShop_Product_Manager_Factory::createManager( $this->_context );
		$typeManager = $manager->getSubManager( 'type' );

		$typeSearch = $typeManager->createSearch();
		$typeSearch->setConditions( $typeSearch->compare( '==', 'product.type.code', 'default' ) );
		$typeResult = $typeManager->searchItems( $typeSearch );

		if( ( $typeItem = reset( $typeResult ) ) === false ) {
			throw new Exception( 'No product type "default" found' );
		}

		$item = $manager->createItem();
		$item->setTypeid( $typeItem->getId() );
		$item->setCode( $code );

		$manager->saveItem( $item );

		return $item;
	}


	protected function _delete( MShop_Product_Item_Interface $product )
	{
		$manager = MShop_Product_Manager_Factory::createManager( $this->_context );
		$listManager = $manager->getSubManager( 'list' );

		foreach( $product->getListItems('attribute') as $listItem ) {
			$listManager->deleteItem( $listItem->getId() );
		}

		$manager->deleteItem( $product->getId() );
	}


	protected function _get( $code )
	{
		$manager = MShop_Product_Manager_Factory::createManager( $this->_context );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', $code ) );

		$result = $manager->searchItems( $search, array('attribute') );

		if( ( $item = reset( $result ) ) === false ) {
			throw new Exception( sprintf( 'No product item for code "%1$s"', $code ) );
		}

		return $item;
	}


	protected function _getProperties( $prodid )
	{
		$manager = MShop_Product_Manager_Factory::createManager( $this->_context )->getSubManager( 'property' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.property.parentid', $prodid ) );
		$search->setSortations( array( $search->sort( '+', 'product.property.type.code' ) ) );

		return $manager->searchItems( $search );
	}
}