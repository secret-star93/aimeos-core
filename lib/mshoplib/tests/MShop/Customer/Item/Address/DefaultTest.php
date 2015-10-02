<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


/**
 * Test class for MShop_Customer_Item_Address_Default.
 */
class MShop_Customer_Item_Address_DefaultTest extends PHPUnit_Framework_TestCase
{
	private $_object;
	private $_values;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->_values = array(
			'id' => 23,
			'siteid' => 12,
			'refid' => 'referenceid',
			'company' => 'unitCompany',
			'vatid' => 'DE999999999',
			'salutation' => MShop_Common_Item_Address_Abstract::SALUTATION_MR,
			'title' => 'Herr',
			'firstname' => 'firstunit',
			'lastname' => 'lastunit',
			'address1' => 'unit str.',
			'address2' => ' 166',
			'address3' => '4.OG',
			'postal' => '22769',
			'city' => 'Hamburg',
			'state' => 'Hamburg',
			'countryid' => 'DE',
			'langid' => 'de',
			'telephone' => '05554433221',
			'email' => 'test@example.com',
			'telefax' => '05554433222',
			'website' => 'www.example.com',
			'pos' => 1,
			'flag' => 2,
			'mtime' => '2011-01-01 00:00:02',
			'ctime' => '2011-01-01 00:00:01',
			'editor' => 'unitTestUser',
		);

		$this->_object = new MShop_Common_Item_Address_Default( 'customer.address.', $this->_values );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		$this->_object = null;
	}

	public function testGetId()
	{
		$this->assertEquals( 23, $this->_object->getId() );
	}

	public function testSetId()
	{
		$this->_object->setId( null );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertNull( $this->_object->getId() );
	}

	public function testGetRefId()
	{
		$this->assertEquals( 'referenceid', $this->_object->getRefId() );
	}

	public function testSetRefId()
	{
		$this->_object->setRefId( 'unitreference' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'unitreference', $this->_object->getRefId() );
	}

	public function testGetCompany()
	{
		$this->assertEquals( 'unitCompany', $this->_object->getCompany() );
	}

	public function testSetCompany()
	{
		$this->_object->setCompany( 'company' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'company', $this->_object->getCompany() );
	}

	public function testGetVatID()
	{
		$this->assertEquals( 'DE999999999', $this->_object->getVatID() );
	}

	public function testSetVatID()
	{
		$this->_object->setVatID( 'vatid' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'vatid', $this->_object->getVatID() );
	}

	public function testGetSalutation()
	{
		$this->assertEquals( MShop_Common_Item_Address_Abstract::SALUTATION_MR, $this->_object->getSalutation() );
	}

	public function testSetSalutation()
	{
		$this->_object->setSalutation( MShop_Common_Item_Address_Abstract::SALUTATION_COMPANY );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( MShop_Common_Item_Address_Abstract::SALUTATION_COMPANY, $this->_object->getSalutation() );
	}

	public function testGetTitle()
	{
		$this->assertEquals( 'Herr', $this->_object->getTitle() );
	}

	public function testSetTitle()
	{
		$this->_object->setTitle( 'Dr.' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'Dr.', $this->_object->getTitle() );
	}

	public function testGetFirstname()
	{
		$this->assertEquals( 'firstunit', $this->_object->getFirstname() );
	}

	public function testSetFirstname()
	{
		$this->_object->setFirstname( 'hans' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'hans', $this->_object->getFirstname() );
	}

	public function testGetLastname()
	{
		$this->assertEquals( 'lastunit', $this->_object->getLastname() );
	}

	public function testSetLastname()
	{
		$this->_object->setLastname( 'im Glueck' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'im Glueck', $this->_object->getLastname() );
	}

	public function testGetAddress1()
	{
		$this->assertEquals( 'unit str.', $this->_object->getAddress1() );
	}

	public function testSetAddress1()
	{
		$this->_object->setAddress1( 'unitallee' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'unitallee', $this->_object->getAddress1() );
	}

	public function testGetAddress2()
	{
		$this->assertEquals( '166', $this->_object->getAddress2() );
	}

	public function testSetAddress2()
	{
		$this->_object->setAddress2( '12' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( '12', $this->_object->getAddress2() );
	}

	public function testGetAddress3()
	{
		$this->assertEquals( '4.OG', $this->_object->getAddress3() );
	}

	public function testSetAddress3()
	{
		$this->_object->setAddress3( 'EG' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'EG', $this->_object->getAddress3() );
	}

	public function testGetPostal()
	{
		$this->assertEquals( '22769', $this->_object->getPostal() );
	}

	public function testSetPostal()
	{
		$this->_object->setPostal( '11111' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( '11111', $this->_object->getPostal() );
	}

	public function testGetCity()
	{
		$this->assertEquals( 'Hamburg', $this->_object->getCity() );
	}

	public function testSetCity()
	{
		$this->_object->setCity( 'unitCity' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'unitCity', $this->_object->getCity() );
	}

	public function testGetState()
	{
		$this->assertEquals( 'Hamburg', $this->_object->getState() );
	}

	public function testSetState()
	{
		$this->_object->setState( 'unitState' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'unitState', $this->_object->getState() );
	}

	public function testGetCountryId()
	{
		$this->assertEquals( 'DE', $this->_object->getCountryId() );
	}

	public function testSetCountryId()
	{
		$this->_object->setCountryId( 'uk' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'UK', $this->_object->getCountryId() );
	}

	public function testGetLanguageId()
	{
		$this->assertEquals( 'de', $this->_object->getLanguageId() );
	}

	public function testSetLanguageId()
	{
		$this->_object->setLanguageId( 'en' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'en', $this->_object->getLanguageId() );
	}

	public function testGetTelephone()
	{
		$this->assertEquals( '05554433221', $this->_object->getTelephone() );
	}

	public function testSetTelephone()
	{
		$this->_object->setTelephone( '55512345' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( '55512345', $this->_object->getTelephone() );
	}

	public function testGetEmail()
	{
		$this->assertEquals( 'test@example.com', $this->_object->getEmail() );
	}

	public function testSetEmail()
	{
		$this->_object->setEmail( 'unit@test.de' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'unit@test.de', $this->_object->getEmail() );

		$this->setExpectedException( 'MShop_Exception' );
		$this->_object->setEmail( 'unittest.de' );
	}

	public function testGetTelefax()
	{
		$this->assertEquals( '05554433222', $this->_object->getTelefax() );
	}

	public function testSetTelefax()
	{
		$this->_object->setTelefax( '55512345' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( '55512345', $this->_object->getTelefax() );
	}

	public function testGetWebsite()
	{
		$this->assertEquals( 'www.example.com', $this->_object->getWebsite() );
	}

	public function testSetWebsite()
	{
		$this->_object->setWebsite( 'www.test.de' );
		$this->assertTrue( $this->_object->isModified() );
		$this->assertEquals( 'www.test.de', $this->_object->getWebsite() );

		$this->_object->setWebsite( 'http://xn--ses-5ka8l.de' );
		$this->_object->setWebsite( 'http://www.test.de:443' );
		$this->_object->setWebsite( 'https://www.test.de:8080/abc?123' );

		$this->setExpectedException( 'MShop_Exception' );
		$this->_object->setWebsite( '_test:de' );
	}

	public function testSetWebsiteHostException()
	{
		$this->setExpectedException( 'MShop_Exception' );
		$this->_object->setWebsite( 'localhost' );
	}

	public function testGetPosition()
	{
		$this->assertEquals( 1, $this->_object->getPosition() );
	}

	public function testSetPosition()
	{
		$this->_object->setPosition( 555 );
		$this->assertEquals( 555, $this->_object->getPosition() );
	}

	public function testGetFlag()
	{
		$this->assertEquals( 2, $this->_object->getFlag() );
	}

	public function testSetFlag()
	{
		$this->_object->setFlag( 5 );
		$this->assertEquals( 5, $this->_object->getFlag() );
	}

	public function testGetTimeModified()
	{
		$this->assertEquals( '2011-01-01 00:00:02', $this->_object->getTimeModified() );
	}

	public function testGetTimeCreated()
	{
		$this->assertEquals( '2011-01-01 00:00:01', $this->_object->getTimeCreated() );
	}

	public function testGetEditor()
	{
		$this->assertEquals( 'unitTestUser', $this->_object->getEditor() );
	}

	public function testCopyFrom()
	{
		$object = new MShop_Common_Item_Address_Default( 'customer.address.' );
		$address = new MShop_Order_Item_Base_Address_Default( $this->_values );
		$object->copyFrom( $address );

		$this->assertNull( $object->getId() );
		$this->assertEquals( $this->_values['salutation'], $object->getSalutation() );
		$this->assertEquals( $this->_values['company'], $object->getCompany() );
		$this->assertEquals( $this->_values['vatid'], $object->getVatID() );
		$this->assertEquals( $this->_values['title'], $object->getTitle() );
		$this->assertEquals( $this->_values['firstname'], $object->getFirstname() );
		$this->assertEquals( $this->_values['lastname'], $object->getLastname() );
		$this->assertEquals( $this->_values['address1'], $object->getAddress1() );
		$this->assertEquals( $this->_values['address2'], $object->getAddress2() );
		$this->assertEquals( $this->_values['address3'], $object->getAddress3() );
		$this->assertEquals( $this->_values['postal'], $object->getPostal() );
		$this->assertEquals( $this->_values['city'], $object->getCity() );
		$this->assertEquals( $this->_values['state'], $object->getState() );
		$this->assertEquals( $this->_values['countryid'], $object->getCountryId() );
		$this->assertEquals( $this->_values['langid'], $object->getLanguageId() );
		$this->assertEquals( $this->_values['telephone'], $object->getTelephone() );
		$this->assertEquals( $this->_values['telefax'], $object->getTelefax() );
		$this->assertEquals( $this->_values['email'], $object->getEmail() );
		$this->assertEquals( $this->_values['website'], $object->getWebsite() );
		$this->assertEquals( $this->_values['flag'], $object->getFlag() );
	}

	public function testFromArray()
	{
		$list = array(
			'customer.address.id' => 1,
			'customer.address.refid' => 2,
			'customer.address.salutation' => 'mr',
			'customer.address.company' => 'mw',
			'customer.address.vatid' => 'vatnumber',
			'customer.address.title' => 'dr',
			'customer.address.firstname' => 'first',
			'customer.address.lastname' => 'last',
			'customer.address.address1' => 'street',
			'customer.address.address2' => 'no',
			'customer.address.address3' => 'flat',
			'customer.address.postal' => '12345',
			'customer.address.city' => 'city',
			'customer.address.state' => 'state',
			'customer.address.countryid' => 'DE',
			'customer.address.languageid' => 'de',
			'customer.address.telephone' => '01234',
			'customer.address.telefax' => '02345',
			'customer.address.email' => 'a@b',
			'customer.address.website' => 'example.com',
			'customer.address.flag' => 3,
			'customer.address.position' => 4,
		);

		$object = new MShop_Common_Item_Address_Default( 'customer.address.' );
		$unknown = $object->fromArray( $list );

		$this->assertEquals( array(), $unknown );

		$this->assertEquals( $list['customer.address.id'], $object->getId() );
		$this->assertEquals( $list['customer.address.refid'], $object->getRefId() );
		$this->assertEquals( $list['customer.address.salutation'], $object->getSalutation() );
		$this->assertEquals( $list['customer.address.company'], $object->getCompany() );
		$this->assertEquals( $list['customer.address.vatid'], $object->getVatID() );
		$this->assertEquals( $list['customer.address.title'], $object->getTitle() );
		$this->assertEquals( $list['customer.address.firstname'], $object->getFirstname() );
		$this->assertEquals( $list['customer.address.lastname'], $object->getLastname() );
		$this->assertEquals( $list['customer.address.address1'], $object->getAddress1() );
		$this->assertEquals( $list['customer.address.address2'], $object->getAddress2() );
		$this->assertEquals( $list['customer.address.address3'], $object->getAddress3() );
		$this->assertEquals( $list['customer.address.postal'], $object->getPostal() );
		$this->assertEquals( $list['customer.address.city'], $object->getCity() );
		$this->assertEquals( $list['customer.address.state'], $object->getState() );
		$this->assertEquals( $list['customer.address.countryid'], $object->getCountryId() );
		$this->assertEquals( $list['customer.address.languageid'], $object->getLanguageId() );
		$this->assertEquals( $list['customer.address.telephone'], $object->getTelephone() );
		$this->assertEquals( $list['customer.address.telefax'], $object->getTelefax() );
		$this->assertEquals( $list['customer.address.email'], $object->getEmail() );
		$this->assertEquals( $list['customer.address.website'], $object->getWebsite() );
		$this->assertEquals( $list['customer.address.flag'], $object->getFlag() );
		$this->assertEquals( $list['customer.address.position'], $object->getPosition() );
	}

	public function testToArray()
	{
		$arrayObject = $this->_object->toArray();
		$this->assertEquals( count( $this->_values ), count( $arrayObject ) );

		$this->assertEquals( $this->_object->getId(), $arrayObject['customer.address.id'] );
		$this->assertEquals( $this->_object->getSiteId(), $arrayObject['customer.address.siteid'] );
		$this->assertEquals( $this->_object->getRefID(), $arrayObject['customer.address.refid'] );
		$this->assertEquals( $this->_object->getPosition(), $arrayObject['customer.address.position'] );
		$this->assertEquals( $this->_object->getCompany(), $arrayObject['customer.address.company'] );
		$this->assertEquals( $this->_object->getVatID(), $arrayObject['customer.address.vatid'] );
		$this->assertEquals( $this->_object->getSalutation(), $arrayObject['customer.address.salutation'] );
		$this->assertEquals( $this->_object->getTitle(), $arrayObject['customer.address.title'] );
		$this->assertEquals( $this->_object->getFirstname(), $arrayObject['customer.address.firstname'] );
		$this->assertEquals( $this->_object->getLastname(), $arrayObject['customer.address.lastname'] );
		$this->assertEquals( $this->_object->getAddress1(), $arrayObject['customer.address.address1'] );
		$this->assertEquals( $this->_object->getAddress2(), $arrayObject['customer.address.address2'] );
		$this->assertEquals( $this->_object->getAddress3(), $arrayObject['customer.address.address3'] );
		$this->assertEquals( $this->_object->getPostal(), $arrayObject['customer.address.postal'] );
		$this->assertEquals( $this->_object->getCity(), $arrayObject['customer.address.city'] );
		$this->assertEquals( $this->_object->getState(), $arrayObject['customer.address.state'] );
		$this->assertEquals( $this->_object->getCountryId(), $arrayObject['customer.address.countryid'] );
		$this->assertEquals( $this->_object->getTelephone(), $arrayObject['customer.address.telephone'] );
		$this->assertEquals( $this->_object->getEmail(), $arrayObject['customer.address.email'] );
		$this->assertEquals( $this->_object->getTelefax(), $arrayObject['customer.address.telefax'] );
		$this->assertEquals( $this->_object->getWebsite(), $arrayObject['customer.address.website'] );
		$this->assertEquals( $this->_object->getLanguageId(), $arrayObject['customer.address.languageid'] );
		$this->assertEquals( $this->_object->getFlag(), $arrayObject['customer.address.flag'] );
		$this->assertEquals( $this->_object->getTimeCreated(), $arrayObject['customer.address.ctime'] );
		$this->assertEquals( $this->_object->getTimeModified(), $arrayObject['customer.address.mtime'] );
		$this->assertEquals( $this->_object->getEditor(), $arrayObject['customer.address.editor'] );
	}

	public function testIsModified()
	{
		$this->assertFalse( $this->_object->isModified() );
	}
}
