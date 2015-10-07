<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


namespace Aimeos\MAdmin\Log\Manager;


/**
 * Test class for \Aimeos\MAdmin\Log\Manager\Factory.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
	public function testCreateManager()
	{
		$manager = \Aimeos\MAdmin\Log\Manager\Factory::createManager( \TestHelper::getContext() );
		$this->assertInstanceOf( '\\Aimeos\\MShop\\Common\\Manager\\Iface', $manager );
	}


	public function testCreateManagerName()
	{
		$manager = \Aimeos\MAdmin\Log\Manager\Factory::createManager( \TestHelper::getContext(), 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\MShop\\Common\\Manager\\Iface', $manager );
	}


	public function testCreateManagerInvalidName()
	{
		$this->setExpectedException( '\\Aimeos\\MAdmin\\Log\\Exception' );
		\Aimeos\MAdmin\Log\Manager\Factory::createManager( \TestHelper::getContext(), '%^' );
	}


	public function testCreateManagerNotExisting()
	{
		$this->setExpectedException( '\\Aimeos\\MShop\\Exception' );
		\Aimeos\MAdmin\Log\Manager\Factory::createManager( \TestHelper::getContext(), 'unknown' );
	}

}