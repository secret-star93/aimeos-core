<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\Controller\JsonAdm;


class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->context = \TestHelperJadm::getContext();
		$templatePaths = \TestHelperJadm::getControllerPaths();
		$this->view = $this->context->getView();

		$this->object = new \Aimeos\Controller\JsonAdm\Standard( $this->context, $this->view, $templatePaths, 'product' );
	}


	public function testDelete()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'deleteItem' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$productManagerStub->expects( $this->once() )->method( 'deleteItem' );


		$params = array( 'id' => $this->getProductItem()->getId() );
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->delete( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'data', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testDeleteBulk()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'deleteItems' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$productManagerStub->expects( $this->once() )->method( 'deleteItems' );


		$body = '{"data":[{"type": "product", "id": "-1"},{"type": "product", "id": "-2"}]}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->delete( $body, $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 2, $result['meta']['total'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'data', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testDeleteInvalid()
	{
		$body = '{"data":null}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->delete( $body, $header, $status ), true );

		$this->assertEquals( 400, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 0, $result['meta']['total'] );
		$this->assertArrayHasKey( 'errors', $result );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'data', $result );
	}


	public function testGet()
	{
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 28, $result['meta']['total'] );
		$this->assertEquals( 25, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayHasKey( 'next', $result['links'] );
		$this->assertArrayHasKey( 'last', $result['links'] );
		$this->assertArrayHasKey( 'self', $result['links'] );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetWarehouse()
	{
		$header = array();
		$status = 500;

		$templatePaths = \TestHelperJadm::getControllerPaths();
		$object = new \Aimeos\Controller\JsonAdm\Standard( $this->context, $this->view, $templatePaths, 'product/stock/warehouse' );

		$result = json_decode( $object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 6, $result['meta']['total'] );
		$this->assertEquals( 6, count( $result['data'] ) );
		$this->assertEquals( 'product/stock/warehouse', $result['data'][0]['type'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetInvalid()
	{
		$header = array();
		$status = 500;

		$templatePaths = \TestHelperJadm::getControllerPaths();
		$object = new \Aimeos\Controller\JsonAdm\Standard( $this->context, $this->view, $templatePaths, 'invalid' );

		$result = json_decode( $object->get( '', $header, $status ), true );

		$this->assertEquals( 404, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, count( $result['errors'] ) );
		$this->assertArrayHasKey( 'title', $result['errors'][0] );
		$this->assertArrayHasKey( 'detail', $result['errors'][0] );
		$this->assertArrayNotHasKey( 'data', $result );
		$this->assertArrayNotHasKey( 'indluded', $result );
	}


	public function testGetFilter()
	{
		$params = array(
			'filter' => array(
				'==' => array( 'product.type.code' => 'select' )
			)
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 3, $result['meta']['total'] );
		$this->assertEquals( 3, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetFilterCombine()
	{
		$params = array(
			'filter' => array(
				'&&' => array(
					array( '=~' => array( 'product.label' => 'Unittest: Test' ) ),
					array( '==' => array( 'product.type.code' => 'select' ) ),
				)
			)
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 2, $result['meta']['total'] );
		$this->assertEquals( 2, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetPage()
	{
		$params = array(
			'page' => array(
				'offset' => 25,
				'limit' => 25
			)
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 28, $result['meta']['total'] );
		$this->assertEquals( 3, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayHasKey( 'first', $result['links'] );
		$this->assertArrayHasKey( 'prev', $result['links'] );
		$this->assertArrayHasKey( 'self', $result['links'] );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetSort()
	{
		$params = array(
			'sort' => 'product.label,-product.code'
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 28, $result['meta']['total'] );
		$this->assertEquals( 25, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 'ABCD', $result['data'][0]['attributes']['product.code'] );
		$this->assertEquals( '16 discs', $result['data'][0]['attributes']['product.label'] );
		$this->assertEquals( 0, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetFields()
	{
		$params = array(
			'fields' => array(
				'product' => 'product.id,product.label'
			),
			'sort' => 'product.id',
			'include' => 'product'
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 28, $result['meta']['total'] );
		$this->assertEquals( 25, count( $result['data'] ) );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 2, count( $result['data'][0]['attributes'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPatch()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'getItem', 'saveItem' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$item = $productManagerStub->createItem();
		$item->setLabel( 'test' );
		$item->setId( '-1' );

		$productManagerStub->expects( $this->once() )->method( 'saveItem' );
		$productManagerStub->expects( $this->exactly( 3 ) )->method( 'getItem' ) // 3x due to decorator
			->will( $this->returnValue( $item ) );


		$params = array( 'id' => '-1' );
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$body = '{"data": {"type": "product", "attributes": {"product.label": "test"}}}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->patch( $body, $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( '-1', $result['data']['id'] );
		$this->assertEquals( 'product', $result['data']['type'] );
		$this->assertEquals( 'test', $result['data']['attributes']['product.label'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPatchBulk()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'getItem', 'saveItem' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$item = $productManagerStub->createItem();
		$item->setLabel( 'test' );
		$item->setId( '-1' );

		$productManagerStub->expects( $this->exactly( 2 ) )->method( 'saveItem' );
		$productManagerStub->expects( $this->exactly( 6 ) )->method( 'getItem' ) // 6x due to decorator
			->will( $this->returnValue( $item ) );


		$body = '{"data": [{"id": "-1", "type": "product", "attributes": {"product.label": "test"}}, {"id": "-1", "type": "product", "attributes": {"product.label": "test"}}]}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->patch( $body, $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 2, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( 2, count( $result['data'] ) );
		$this->assertEquals( '-1', $result['data'][0]['id'] );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 'test', $result['data'][0]['attributes']['product.label'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPatchInvalid()
	{
		$body = '{"data":null}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->patch( $body, $header, $status ), true );

		$this->assertEquals( 400, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 0, $result['meta']['total'] );
		$this->assertArrayHasKey( 'errors', $result );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'data', $result );
	}


	public function testPost()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'getItem', 'saveItem' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$item = $productManagerStub->createItem();
		$item->setLabel( 'test' );
		$item->setId( '-1' );

		$productManagerStub->expects( $this->once() )->method( 'saveItem' );
		$productManagerStub->expects( $this->once() )->method( 'getItem' )
			->will( $this->returnValue( $item ) );


		$body = '{"data": {"type": "product", "attributes": {"product.label": "test"}}}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->post( $body, $header, $status ), true );

		$this->assertEquals( 201, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( '-1', $result['data']['id'] );
		$this->assertEquals( 'product', $result['data']['type'] );
		$this->assertEquals( 'test', $result['data']['attributes']['product.label'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPostBulk()
	{
		$name = 'ControllerJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/product/manager/name', $name );

		$productManagerStub = $this->getMockBuilder( '\\Aimeos\\MShop\\Product\\Manager\\Standard' )
			->setMethods( array( 'getItem', 'saveItem' ) )
			->setConstructorArgs( array( $this->context ) )
			->getMock();

		\Aimeos\MShop\Order\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Product\\Manager\\' . $name, $productManagerStub );

		$item = $productManagerStub->createItem();
		$item->setLabel( 'test' );
		$item->setId( '-1' );

		$productManagerStub->expects( $this->exactly( 2 ) )->method( 'saveItem' );
		$productManagerStub->expects( $this->exactly( 2 ) )->method( 'getItem' )
			->will( $this->returnValue( $item ) );


		$body = '{"data": [{"type": "product", "attributes": {"product.label": "test"}}, {"type": "product", "attributes": {"product.label": "test"}}]}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->post( $body, $header, $status ), true );

		$this->assertEquals( 201, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 2, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( 2, count( $result['data'] ) );
		$this->assertEquals( '-1', $result['data'][0]['id'] );
		$this->assertEquals( 'product', $result['data'][0]['type'] );
		$this->assertEquals( 'test', $result['data'][0]['attributes']['product.label'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPostInvalid()
	{
		$body = '{"data":null}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->post( $body, $header, $status ), true );

		$this->assertEquals( 400, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 0, $result['meta']['total'] );
		$this->assertArrayHasKey( 'errors', $result );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'data', $result );
	}


	public function testPut()
	{
		$body = '';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->put( $body, $header, $status ), true );

		$this->assertEquals( 501, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertArrayHasKey( 'errors', $result );
	}


	public function testOptions()
	{
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->options( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 2, count( $header ) );
		$this->assertEquals( 59, count( $result['meta']['resources'] ) );
		$this->assertGreaterThan( 0, count( $result['meta']['attributes'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	protected function getProductItem( $code = 'CNC' )
	{
		$manager = \Aimeos\MShop\Product\Manager\Factory::createManager( $this->context );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', $code ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( sprintf( 'No product item with code "%1$s" found', $code ) );
		}

		return $item;
	}
}