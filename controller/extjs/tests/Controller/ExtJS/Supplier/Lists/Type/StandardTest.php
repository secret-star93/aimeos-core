<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


class Controller_ExtJS_Supplier_Lists_Type_StandardTest extends PHPUnit_Framework_TestCase
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
		$this->object = new Controller_ExtJS_Supplier_Lists_Type_Standard( TestHelper::getContext() );
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


	public function testSearchItems()
	{
		$params = (object) array(
			'site' => 'unittest',
			'condition' => (object) array( '&&' => array( 0 => (object) array( '==' => (object) array( 'supplier.lists.type.code' => 'default' ) ) ) ),
			'sort' => 'supplier.lists.type.code',
			'dir' => 'ASC',
			'start' => 0,
			'limit' => 1,
		);

		$result = $this->object->searchItems( $params );

		$this->assertEquals( 1, count( $result['items'] ) );
		$this->assertEquals( 1, $result['total'] );
		$this->assertEquals( 'default', $result['items'][0]->{'supplier.lists.type.code'} );
	}


	public function testSaveDeleteItem()
	{
		$saveParams = (object) array(
			'site' => 'unittest',
			'items' =>  (object) array(
				'supplier.lists.type.code' => 'test',
				'supplier.lists.type.label' => 'testLabel',
				'supplier.lists.type.domain' => 'supplier',
				'supplier.lists.type.status' => 1,
			),
		);

		$searchParams = (object) array(
			'site' => 'unittest',
			'condition' => (object) array( '&&' => array( 0 => (object) array( '==' => (object) array( 'supplier.lists.type.code' => 'test' ) ) ) )
		);


		$saved = $this->object->saveItems( $saveParams );
		$searched = $this->object->searchItems( $searchParams );

		$params = (object) array( 'site' => 'unittest', 'items' => $saved['items']->{'supplier.lists.type.id'} );
		$this->object->deleteItems( $params );
		$result = $this->object->searchItems( $searchParams );

		$this->assertInternalType( 'object', $saved['items'] );
		$this->assertNotNull( $saved['items']->{'supplier.lists.type.id'} );
		$this->assertEquals( $saved['items']->{'supplier.lists.type.id'}, $searched['items'][0]->{'supplier.lists.type.id'} );
		$this->assertEquals( $saved['items']->{'supplier.lists.type.code'}, $searched['items'][0]->{'supplier.lists.type.code'} );
		$this->assertEquals( $saved['items']->{'supplier.lists.type.domain'}, $searched['items'][0]->{'supplier.lists.type.domain'} );
		$this->assertEquals( $saved['items']->{'supplier.lists.type.label'}, $searched['items'][0]->{'supplier.lists.type.label'} );
		$this->assertEquals( $saved['items']->{'supplier.lists.type.status'}, $searched['items'][0]->{'supplier.lists.type.status'} );
		$this->assertEquals( 1, count( $searched['items'] ) );
		$this->assertEquals( 0, count( $result['items'] ) );
	}
}
