<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2017
 */


namespace Aimeos\MShop\Common\Item\ListRef;


class Test extends \Aimeos\MShop\Common\Item\ListRef\Base
{
	public function getLabel()
	{
		return 'test label';
	}

	public function getResourceType()
	{
		return '';
	}
}



class BaseTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $textItem1;
	private $textItem2;
	private $listItem1;
	private $listItem2;


	protected function setUp()
	{
		$values = ['languageid' => null, 'text.status' => 1];

		$this->textItem1 = new \Aimeos\MShop\Text\Item\Standard( array( 'text.type' => 'name' ) + $values );
		$this->textItem1->setContent( 'test name' );
		$this->textItem1->setStatus( 1 );
		$this->textItem1->setId( 1 );

		$this->textItem2 = new \Aimeos\MShop\Text\Item\Standard( array( 'text.type' => 'short' ) + $values );
		$this->textItem2->setContent( 'default name' );
		$this->textItem2->setStatus( 1 );
		$this->textItem2->setId( 2 );

		$this->listItem1 = new \Aimeos\MShop\Common\Item\Lists\Standard( 'text.lists.', array( 'text.lists.type' => 'test' ) + $values );
		$this->listItem1->setRefId( $this->textItem1->getId() );
		$this->listItem1->setDomain( 'text' );
		$this->listItem1->setPosition( 1 );
		$this->listItem1->setStatus( 1 );
		$this->listItem1->setId( 11 );

		$this->listItem2 = new \Aimeos\MShop\Common\Item\Lists\Standard( 'text.lists.', array( 'text.lists.type' => 'default' ) + $values );
		$this->listItem2->setRefId( $this->textItem2->getId() );
		$this->listItem2->setDomain( 'text' );
		$this->listItem2->setPosition( 0 );
		$this->listItem2->setStatus( 1 );
		$this->listItem2->setId( 10 );

		$listItems = array( 'text' => array(
			$this->listItem1->getId() => $this->listItem1,
			$this->listItem2->getId() => $this->listItem2,
		) );

		$refItems = array( 'text' => array(
			$this->textItem1->getId() => $this->textItem1,
			$this->textItem2->getId() => $this->textItem2,
		) );

		$this->object = new \Aimeos\MShop\Common\Item\ListRef\Test( 'text.', [], $listItems, $refItems );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetName()
	{
		$object = new \Aimeos\MShop\Common\Item\ListRef\Test( '' );

		$this->assertEquals( 'test label', $object->getName() );
		$this->assertEquals( 'test name', $this->object->getName() );
	}


	public function testGetListItem()
	{
		$result = $this->object->getListItem( 2, 'text', 'default' );

		$this->assertInstanceof( '\\Aimeos\\MShop\\Common\\Item\\Lists\\Iface', $result );
	}


	public function testGetListItems()
	{
		$result = $this->object->getListItems();
		$expected = array(
			$this->listItem2->getId() => $this->listItem2,
			$this->listItem1->getId() => $this->listItem1,
		);

		$this->assertEquals( $expected, $result );

		foreach( $result as $listItem ) {
			$this->assertInstanceof( '\\Aimeos\\MShop\\Common\\Item\\Lists\\Iface', $listItem );
		}
	}


	public function testGetListItemsWithDomain()
	{
		$result = $this->object->getListItems( 'text' );
		$expected = array(
			$this->listItem2->getId() => $this->listItem2,
			$this->listItem1->getId() => $this->listItem1,
		);

		$this->assertEquals( $expected, $result );

		foreach( $result as $listItem ) {
			$this->assertInstanceof( '\\Aimeos\\MShop\\Common\\Item\\Lists\\Iface', $listItem );
		}

		$this->assertEquals( [], $this->object->getListItems( 'undefined' ) );
	}


	public function testGetListItemsWithListtype()
	{
		$result = $this->object->getListItems( 'text', 'test' );
		$expected = array( $this->listItem1->getId() => $this->listItem1 );

		$this->assertEquals( $expected, $result );
	}


	public function testGetListItemsWithListtypes()
	{
		$result = $this->object->getListItems( 'text', array( 'test' ) );
		$expected = array( $this->listItem1->getId() => $this->listItem1 );

		$this->assertEquals( $expected, $result );
	}


	public function testGetListItemsWithType()
	{
		$result = $this->object->getListItems( 'text', null, 'name' );
		$expected = array( $this->listItem1->getId() => $this->listItem1 );

		$this->assertEquals( $expected, $result );
	}


	public function testGetListItemsWithTypes()
	{
		$result = $this->object->getListItems( 'text', null, array( 'name', 'short' ) );
		$expected = array(
			$this->listItem1->getId() => $this->listItem1,
			$this->listItem2->getId() => $this->listItem2,
		);

		$this->assertEquals( $expected, $result );
	}


	public function testGetListItemsWithRefItems()
	{
		$result = $this->object->getListItems( 'text' );
		$expected = array(
			$this->textItem2->getId() => $this->textItem2,
			$this->textItem1->getId() => $this->textItem1,
		);

		foreach( $result as $listItem )
		{
			$this->assertInstanceof( '\\Aimeos\\MShop\\Text\\Item\\Iface', $listItem->getRefItem() );
			$this->assertSame( $expected[$listItem->getRefId()], $listItem->getRefItem() );
		}
	}


	public function testGetRefItems()
	{
		$result = $this->object->getRefItems();
		$expected = array(
			'text' => array(
				$this->textItem2->getId() => $this->textItem2,
				$this->textItem1->getId() => $this->textItem1,
			)
		);

		$this->assertEquals( $expected, $result );

		foreach( $result as $domain => $list )
		{
			foreach( $list as $item ) {
				$this->assertInstanceof( '\\Aimeos\\MShop\\Common\\Item\\Iface', $item );
			}
		}
	}


	public function testGetRefItemsWithDomain()
	{
		$result = $this->object->getRefItems( 'text' );
		$expected = array(
			$this->textItem2->getId() => $this->textItem2,
			$this->textItem1->getId() => $this->textItem1,
		);

		$this->assertEquals( $expected, $result );

		foreach( $result as $item ) {
			$this->assertInstanceof( '\\Aimeos\\MShop\\Common\\Item\\Iface', $item );
		}

		$this->assertEquals( [], $this->object->getRefItems( 'undefined' ) );
	}


	public function testGetRefItemsWithType()
	{
		$result = $this->object->getRefItems( 'text', 'name' );
		$expected = array( $this->textItem1->getId() => $this->textItem1 );

		$this->assertEquals( $expected, $result );
		$this->assertEquals( [], $this->object->getRefItems( 'text', 'undefined' ) );
	}


	public function testGetRefItemsWithTypes()
	{
		$result = $this->object->getRefItems( 'text', array( 'short', 'name' ) );
		$expected = array(
			$this->textItem2->getId() => $this->textItem2,
			$this->textItem1->getId() => $this->textItem1,
		);

		$this->assertEquals( $expected, $result );
		$this->assertEquals( [], $this->object->getRefItems( 'text', 'undefined' ) );
	}


	public function testGetRefItemsWithTypeAndListtype()
	{
		$result = $this->object->getRefItems( 'text', 'name', 'test' );
		$expected = array( $this->textItem1->getId() => $this->textItem1 );

		$this->assertEquals( $expected, $result );
		$this->assertEquals( [], $this->object->getRefItems( 'text', 'name', 'undefined' ) );
	}
}
