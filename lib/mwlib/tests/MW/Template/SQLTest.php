<?php

namespace Aimeos\MW\Template;


/**
 * Test class for \Aimeos\MW\Session\CMSLite.
 *
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.gnu.org/licenses/lgpl.html
 */
class SQLTest extends \PHPUnit_Framework_TestCase
{
	private $object;


	protected function setUp()
	{
		$template = 'SELECT * FROM /*-FROM*/table/*FROM-*/';

		$this->object = new \Aimeos\MW\Template\SQL( $template );
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

	public function testToString()
	{
		$template = $this->object->get('FROM');
		$this->assertInstanceOf( '\\Aimeos\\MW\\Template\\Iface', $template );

		$this->assertEquals( 'table', $template->str() );
	}
}
