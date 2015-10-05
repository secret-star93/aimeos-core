<?php

namespace Aimeos\MW\Common\Criteria\Attribute;


/**
 * Test class for \Aimeos\MW\Common\Criteria\Attribute\Standard.
 *
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.gnu.org/licenses/lgpl.html
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Aimeos\MW\Common\Criteria\Attribute\Standard
	 * @access protected
	 */
	private $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$values = array(
			'type' => 'attribute_type',
			'internaltype' => 'internaltype',
			'code' => 'attribute_code',
			'internalcode' => 'internalcode',
			'internaldeps' => array( 'test' ),
			'label' => 'labelname',
			'default' => 'default value',
			'public' => false,
			'required' => false,
		);

		$this->object = new \Aimeos\MW\Common\Criteria\Attribute\Standard($values);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
	}


	public function testGetType()
	{
		$this->assertEquals('attribute_type', $this->object->getType());
	}


	public function testGetInternalType()
	{
		$this->assertEquals('internaltype', $this->object->getInternalType());
	}

	public function testGetCode()
	{
		$this->assertEquals('attribute_code', $this->object->getCode());
	}

	public function testGetInternalCode()
	{
		$this->assertEquals('internalcode', $this->object->getInternalCode());
	}

	public function testGetInternalDeps()
	{
		$this->assertEquals(array( 'test' ), $this->object->getInternalDeps());
	}

	public function testGetLabel()
	{
		$this->assertEquals('labelname', $this->object->getLabel());
	}

	public function testGetDefault()
	{
		$this->assertEquals('default value', $this->object->getDefault());
	}

	public function testIsPublic()
	{
		$this->assertEquals(false, $this->object->isPublic());
	}

	public function testIsRequired()
	{
		$this->assertEquals(false, $this->object->isRequired());
	}
}
