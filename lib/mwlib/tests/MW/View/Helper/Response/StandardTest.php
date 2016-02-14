<?php

namespace Aimeos\MW\View\Helper\Response;


/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $response;


	protected function setUp()
	{
		$view = new \Aimeos\MW\View\Standard();
		$this->response = $this->getMock( '\Psr\Http\Message\ResponseInterface' );
		$this->object = new \Aimeos\MW\View\Helper\Response\Standard( $view, $this->response );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->response );
	}


	public function testTransform()
	{
		$this->assertInstanceOf( '\Aimeos\MW\View\Helper\Response\Iface', $this->object->transform() );
	}


	public function testCreateStream()
	{
		if( !class_exists( '\Zend\Diactoros\Stream' ) )
		{
			$this->setExpectedException( '\Aimeos\MW\Exception' );
			$this->object->createStream( 'test' );
		}

		$this->assertInstanceOf( '\Psr\Http\Message\StreamInterface', $this->object->createStream( __FILE__ ) );
	}


	public function testGetProtocolVersion()
	{
		$this->response->expects( $this->once() )->method( 'getProtocolVersion' )
			->will( $this->returnValue( '1.0' ) );

		$this->assertEquals( '1.0', $this->object->getProtocolVersion() );
	}


	public function testWithProtocolVersion()
	{
		$this->response->expects( $this->once() )->method( 'withProtocolVersion' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withProtocolVersion( '1.0' ) );
	}


	public function testGetHeaders()
	{
		$this->response->expects( $this->once() )->method( 'getHeaders' )
			->will( $this->returnValue( array() ) );

		$this->assertEquals( array(), $this->object->getHeaders() );
	}


	public function testHasHeader()
	{
		$this->response->expects( $this->once() )->method( 'hasHeader' )
			->will( $this->returnValue( true ) );

		$this->assertEquals( true, $this->object->hasHeader( 'test' ) );
	}


	public function testGetHeader()
	{
		$this->response->expects( $this->once() )->method( 'getHeader' )
			->will( $this->returnValue( 'value' ) );

		$this->assertEquals( 'value', $this->object->getHeader( 'test' ) );
	}


	public function testGetHeaderLine()
	{
		$this->response->expects( $this->once() )->method( 'getHeaderLine' )
			->will( $this->returnValue( 'value' ) );

		$this->assertEquals( 'value', $this->object->getHeaderLine( 'test' ) );
	}


	public function testWithHeader()
	{
		$this->response->expects( $this->once() )->method( 'withHeader' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withHeader( 'test', 'value' ) );
	}


	public function testWithAddedHeader()
	{
		$this->response->expects( $this->once() )->method( 'withAddedHeader' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withAddedHeader( 'test', 'value' ) );
	}


	public function testWithoutHeader()
	{
		$this->response->expects( $this->once() )->method( 'withoutHeader' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withoutHeader( 'test' ) );
	}


	public function testGetBody()
	{
		$stream = $this->getMock( '\Psr\Http\Message\StreamInterface' );

		$this->response->expects( $this->once() )->method( 'getBody' )
			->will( $this->returnValue( $stream ) );

		$this->assertEquals( $stream, $this->object->getBody() );
	}


	public function testWithBody()
	{
		$stream = $this->getMock( '\Psr\Http\Message\StreamInterface' );

		$this->response->expects( $this->once() )->method( 'withBody' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withBody( $stream ) );
	}


	public function testGetStatusCode()
	{
		$this->response->expects( $this->once() )->method( 'getStatusCode' )
			->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->getStatusCode() );
	}


	public function testWithStatus()
	{
		$this->response->expects( $this->once() )->method( 'withStatus' )
			->will( $this->returnValue( $this->response ) );

		$this->assertEquals( $this->object, $this->object->withStatus( 'test', 'phrase' ) );
	}


	public function testGetReasonPhrase()
	{
		$this->response->expects( $this->once() )->method( 'getReasonPhrase' )
			->will( $this->returnValue( 'test' ) );

		$this->assertEquals( 'test', $this->object->getReasonPhrase() );
	}
}
