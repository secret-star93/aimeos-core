<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


namespace Aimeos\MW\Setup\Task;


/**
 * Rebuilds the index.
 */
class CatalogRebuildTestIndex extends \Aimeos\MW\Setup\Task\Base
{
	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function getPreDependencies()
	{
		return array( 'MShopSetLocale' );
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
		$this->process();
	}


	/**
	 * Rebuilds the index.
	 */
	protected function process()
	{
		$iface = '\\Aimeos\\MShop\\Context\\Item\\Iface';
		if( !( $this->additional instanceof $iface ) ) {
			throw new \Aimeos\MW\Setup\Exception( sprintf( 'Additionally provided object is not of type "%1$s"', $iface ) );
		}


		$this->msg( 'Rebuilding index for test data', 0 );

		$indexManager = \Aimeos\MShop\Index\Manager\Factory::createManager( $this->additional );

		$indexManager->rebuildIndex();
		$indexManager->optimize();

		$this->status( 'done' );
	}
}
