<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


namespace Aimeos\MW\Setup\Task;


/**
 * Adds customer property test data.
 */
class CustomerAddPropertyTestData extends \Aimeos\MW\Setup\Task\Base
{

	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function getPreDependencies()
	{
		return array( 'MShopSetLocale', 'CustomerAddTestData' );
	}


	/**
	 * Returns the list of task names which depends on this task.
	 *
	 * @return string[] List of task names
	 */
	public function getPostDependencies()
	{
		return array( 'CatalogRebuildTestIndex' );
	}


	/**
	 * Adds customer test data.
	 */
	public function migrate()
	{
		$iface = '\\Aimeos\\MShop\\Context\\Item\\Iface';
		if( !( $this->additional instanceof $iface ) ) {
			throw new \Aimeos\MW\Setup\Exception( sprintf( 'Additionally provided object is not of type "%1$s"', $iface ) );
		}

		$this->msg( 'Adding customer property test data', 0 );
		$this->additional->setEditor( 'core:unittest' );

		$ds = DIRECTORY_SEPARATOR;
		$path = __DIR__ . $ds . 'data' . $ds . 'customer-property.php';

		if( ( $testdata = include( $path ) ) == false ) {
			throw new \Aimeos\MShop\Exception( sprintf( 'No file "%1$s" found for customer domain', $path ) );
		}

		$this->addCustomerPropertyData( $testdata );

		$this->status( 'done' );
	}

	/**
	 * Adds the customer property test data.
	 *
	 * @param array $testdata Associative list of key/list pairs
	 * @throws \Aimeos\MW\Setup\Exception If no type ID is found
	 */
	private function addCustomerPropertyData( array $testdata )
	{
		$customerManager = \Aimeos\MShop\Customer\Manager\Factory::createManager( $this->additional, 'Standard' );
		$customerPropertyManager = $customerManager->getSubManager( 'property', 'Standard' );
		$customerPropertyTypeManager = $customerPropertyManager->getSubManager( 'type', 'Standard' );

		$typeIds = [];
		$type = $customerPropertyTypeManager->createItem();
		$custIds = $this->getCustomerIds( $customerManager );

		$this->conn->begin();

		foreach( $testdata['customer/property/type'] as $key => $dataset )
		{
			$type->setId( null );
			$type->setCode( $dataset['code'] );
			$type->setDomain( $dataset['domain'] );
			$type->setLabel( $dataset['label'] );
			$type->setStatus( $dataset['status'] );

			$customerPropertyTypeManager->saveItem( $type );
			$typeIds[ $key ] = $type->getId();
		}

		$custProperty = $customerPropertyManager->createItem();
		foreach( $testdata['customer/property'] as $key => $dataset )
		{
			if( !isset( $typeIds[ $dataset['typeid'] ] ) ) {
				throw new \Aimeos\MW\Setup\Exception( sprintf( 'No customer property type ID found for "%1$s"', $dataset['typeid'] ) );
			}

			$custProperty->setId( null );
			$custProperty->setParentId( $custIds[ $dataset['parentid'] ] );
			$custProperty->setTypeId( $typeIds[ $dataset['typeid'] ] );
			$custProperty->setLanguageId( $dataset['langid'] );
			$custProperty->setValue( $dataset['value'] );

			$customerPropertyManager->saveItem( $custProperty, false );
		}

		$this->conn->commit();
	}


	/**
	 * Retrieves the customer IDs for the used codes
	 *
	 * @param \Aimeos\MShop\Common\Manager\Iface $customerManager Customer manager object
	 * @return array Associative list of customer codes as key (e.g. customer/CNC) and IDs as value
	 */
	protected function getCustomerIds( \Aimeos\MShop\Common\Manager\Iface $customerManager )
	{
		$entry = [];
		$search = $customerManager->createSearch();

		foreach( $customerManager->searchItems( $search ) as $id => $item ) {
			$entry[ 'customer/' . $item->getCode() ] = $id;
		}

		return $entry;

	}
}