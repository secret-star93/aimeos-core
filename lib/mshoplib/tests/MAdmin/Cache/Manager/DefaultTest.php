<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

class MAdmin_Cache_Manager_DefaultTest extends PHPUnit_Framework_TestCase
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
		$this->object = new MAdmin_Cache_Manager_Default( $this->context );
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


	public function testCleanup()
	{
		$this->object->cleanup( array( -1 ) );
	}


	public function testCreateItem()
	{
		$this->assertInstanceOf( 'MAdmin_Cache_Item_Interface', $this->object->createItem() );
	}


	public function testGetSearchAttributes()
	{
		foreach( $this->object->getSearchAttributes() as $attr ) {
			$this->assertInstanceOf( 'MW_Common_Criteria_Attribute_Interface', $attr );
		}
	}


	public function testGetSubManager()
	{
		$this->setExpectedException( 'MAdmin_Exception' );
		$this->object->getSubManager( 'unknown' );
	}


	public function testSearchItems()
	{
		$siteid = $this->context->getLocale()->getSiteId();

		$search = $this->object->createSearch();
		$search->setConditions( $search->compare( '==', 'cache.id', 'unittest' ) );
		$results = $this->object->searchItems( $search );

		$this->assertEquals( 1, count( $results ) );

		foreach( $results as $id => $item )
		{
			$this->assertEquals( 'unittest', $id );
			$this->assertEquals( 'unittest', $item->getId() );
			$this->assertEquals( $siteid, $item->getSiteId() );
			$this->assertEquals( 'unit test value', $item->getValue() );
		}
	}


	public function testGetItem()
	{
		$siteid = $this->context->getLocale()->getSiteId();
		$item = $this->object->getItem( 'unittest' );

		$this->assertEquals( 'unittest', $item->getId() );
		$this->assertEquals( $siteid, $item->getSiteId() );
		$this->assertEquals( 'unit test value', $item->getValue() );
	}


	public function testSaveUpdateDeleteItem()
	{
		$item = $this->object->createItem();
		$item->setId( 'unittest2' );
		$item->setValue( 'test2' );
		$this->object->saveItem( $item );
		$itemSaved = $this->object->getItem( $item->getId() );

		$itemExp = clone $itemSaved;
		$itemExp->setValue( 'test3' );
		$this->object->saveItem( $itemExp );
		$itemUpd = $this->object->getItem( $item->getId() );

		$this->object->deleteItem( $item->getId() );

		$this->assertEquals( 'unittest2', $item->getId() );
		$this->assertEquals( $item->getId(), $itemSaved->getId() );
		$this->assertEquals( $item->getSiteid(), $itemSaved->getSiteId() );
		$this->assertEquals( $item->getTimeExpire(), $itemSaved->getTimeExpire() );
		$this->assertEquals( $item->getValue(), $itemSaved->getValue() );
		$this->assertEquals( $item->getTags(), $itemSaved->getTags() );

		$this->assertEquals( $itemExp->getId(), $itemSaved->getId() );
		$this->assertEquals( $itemExp->getSiteid(), $itemSaved->getSiteId() );
		$this->assertEquals( $itemExp->getTimeExpire(), $itemUpd->getTimeExpire() );
		$this->assertEquals( $itemExp->getValue(), $itemUpd->getValue() );
		$this->assertEquals( $itemExp->getTags(), $itemUpd->getTags() );

		$this->setExpectedException( 'MAdmin_Cache_Exception' );
		$this->object->getItem( $item->getId() );
	}
}
