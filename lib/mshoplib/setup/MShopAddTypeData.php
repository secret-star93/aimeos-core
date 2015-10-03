<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Adds default records to tables.
 */
class MW_Setup_Task_MShopAddTypeData extends MW_Setup_Task_Base
{
	private $editor = '';
	private $domainManagers = array();


	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function getPreDependencies()
	{
		return array( 'TablesCreateMShop' );
	}


	/**
	 * Returns the list of task names which depends on this task.
	 *
	 * @return array List of task names
	 */
	public function getPostDependencies()
	{
		return array();
	}


	/**
	 * Executes the task for MySQL databases.
	 */
	protected function mysql()
	{
		// executed by tasks in sub-directories for specific sites
		// $this->process();
	}


	/**
	 * Adds locale data.
	 */
	protected function process()
	{
		$iface = 'MShop_Context_Item_Interface';
		if( !( $this->additional instanceof $iface ) ) {
			throw new MW_Setup_Exception( sprintf( 'Additionally provided object is not of type "%1$s"', $iface ) );
		}

		$sitecode = $this->additional->getLocale()->getSite()->getCode();
		$this->msg( sprintf( 'Adding MShop type data for site "%1$s"', $sitecode ), 0 );
		$this->status( '' );


		$ds = DIRECTORY_SEPARATOR;
		$filename = dirname( __FILE__ ) . $ds . 'default' . $ds . 'data' . $ds . 'type.php';

		if( ( $testdata = include( $filename ) ) == false ) {
			throw new MShop_Exception( sprintf( 'No type file found in "%1$s"', $filename ) );
		}

		$this->processFile( $testdata );
	}


	protected function processFile( array $testdata )
	{
		$editor = $this->additional->getEditor();
		$this->additional->setEditor( $this->editor );


		$this->txBegin();

		foreach( $testdata as $domain => $datasets )
		{
			$this->msg( sprintf( 'Checking "%1$s" type data', $domain ), 1 );

			$domainManager = $this->getDomainManager( $domain );
			$type = $domainManager->createItem();
			$num = $total = 0;

			foreach( $datasets as $dataset )
			{
				$total++;

				$type->setId( null );
				$type->setCode( $dataset['code'] );
				$type->setDomain( $dataset['domain'] );
				$type->setLabel( $dataset['label'] );
				$type->setStatus( $dataset['status'] );

				try {
					$domainManager->saveItem( $type );
					$num++;
				} catch( Exception $e ) {; } // if type was already available
			}

			$this->status( $num > 0 ? $num . '/' . $total : 'OK' );
		}

		$this->txCommit();

		$this->additional->setEditor( $editor );
	}


	/**
	 * Returns the manager for the given domain and sub-domains.
	 *
	 * @param string $domain String of domain and sub-domains, e.g. "product" or "order/base/service"
	 * @throws Controller_Frontend_Exception If domain string is invalid or no manager can be instantiated
	 */
	protected function getDomainManager( $domain )
	{
		$domain = strtolower( trim( $domain, "/ \n\t\r\0\x0B" ) );

		if( strlen( $domain ) === 0 ) {
			throw new Exception( 'An empty domain is invalid' );
		}

		if( !isset( $this->domainManagers[$domain] ) )
		{
			$parts = explode( '/', $domain );

			foreach( $parts as $part )
			{
				if( ctype_alnum( $part ) === false ) {
					throw new Exception( sprintf( 'Invalid domain "%1$s"', $domain ) );
				}
			}

			if( ( $domainname = array_shift( $parts ) ) === null ) {
				throw new Exception( 'An empty domain is invalid' );
			}


			if( !isset( $this->domainManagers[$domainname] ) )
			{
				$iface = 'MShop_Common_Manager_Interface';
				$factory = 'MShop_' . ucwords( $domainname ) . '_Manager_Factory';
				$manager = call_user_func_array( $factory . '::createManager', array( $this->additional ) );

				if( !( $manager instanceof $iface ) ) {
					throw new Exception( sprintf( 'No factory "%1$s" found', $factory ) );
				}

				$this->domainManagers[$domainname] = $manager;
			}


			foreach( $parts as $part )
			{
				$tmpname = $domainname . '/' . $part;

				if( !isset( $this->domainManagers[$tmpname] ) ) {
					$this->domainManagers[$tmpname] = $this->domainManagers[$domainname]->getSubManager( $part );
				}

				$domainname = $tmpname;
			}
		}

		return $this->domainManagers[$domain];
	}


	protected function txBegin()
	{
		$dbm = $this->additional->getDatabaseManager();

		$conn = $dbm->acquire();
		$conn->begin();
		$dbm->release( $conn );
	}


	protected function txCommit()
	{
		$dbm = $this->additional->getDatabaseManager();

		$conn = $dbm->acquire();
		$conn->commit();
		$dbm->release( $conn );
	}
}