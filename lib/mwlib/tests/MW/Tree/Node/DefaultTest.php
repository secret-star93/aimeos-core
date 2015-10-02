<?php

/**
 * Test class for MW_Tree_Node_Default.
 *
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.gnu.org/licenses/lgpl.html
 */
class MW_Tree_Node_DefaultTest extends PHPUnit_Framework_TestCase
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
		$child1 = new MW_Tree_Node_Default( array( 'id' => null, 'label' => 'child1', 'status' => '0', 'custom' => 'test' ) );
		$child2 = new MW_Tree_Node_Default( array( 'id' => null, 'label' => 'child2', 'status' => '1', 'custom' => 'test' ) );

		$this->object = new MW_Tree_Node_Default( array( 'id' => 1, 'label' => 'parent', 'status' => '1', 'custom' => 'test' ), array( $child1, $child2 ) );
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

	public function testMagicMethods1()
	{
		$this->assertEquals( 'test', $this->object->custom );
		$this->assertTrue( isset( $this->object->custom ) );

		unset( $this->object->custom );
		$this->assertFalse( isset( $this->object->custom ) );
		$this->assertTrue( $this->object->isModified() );
	}

	public function testMagicMethods2()
	{
		$this->object->custom = 'test2';
		$this->assertEquals( 'test2', $this->object->custom );
		$this->assertTrue( $this->object->isModified() );
	}

	public function testGetId()
	{
		$this->assertEquals( 1, $this->object->getId() );
	}

	public function testLabel()
	{
		$this->assertEquals( 'parent', $this->object->getLabel() );

		$this->object->setLabel( 'ancestor' );
		$this->assertTrue( $this->object->isModified() );
		$this->assertEquals( 'ancestor', $this->object->getLabel() );
	}

	public function testStatus()
	{
		$this->assertEquals( 1, $this->object->getStatus() );

		$this->object->setStatus( 0 );
		$this->assertTrue( $this->object->isModified() );
		$this->assertEquals( 0, $this->object->getStatus() );
	}

	public function testChildren()
	{
		$this->assertEquals( 'child2', $this->object->getChild( 1 )->getLabel() );
		$this->assertEquals( 2, count( $this->object->getChildren() ) );
	}

	public function testHasChildren()
	{
		$this->assertEquals( true, $this->object->hasChildren() );
	}

	public function testAddChild()
	{
		$this->object->addChild( new MW_Tree_Node_Default( array( 'id' => null, 'label' => 'child3' ) ) );
		$this->assertEquals( 'child1', $this->object->getChild( 0 )->getLabel() );
		$this->assertEquals( 'child2', $this->object->getChild( 1 )->getLabel() );
		$this->assertEquals( 'child3', $this->object->getChild( 2 )->getLabel() );
	}

	public function testToArray()
	{
		$values = $this->object->toArray();

		$this->assertEquals( 1, $values['id'] );
		$this->assertEquals( 'parent', $values['label'] );
	}

	public function testIsModified()
	{
		$this->assertFalse( $this->object->isModified() );
	}

	public function testCount()
	{
		$this->assertEquals( 2, count( $this->object ) );

		$this->object->addChild( new MW_Tree_Node_Default( array( 'id' => null, 'label' => 'child3' ) ) );
		$this->assertEquals( 3, count( $this->object ) );
	}
}
