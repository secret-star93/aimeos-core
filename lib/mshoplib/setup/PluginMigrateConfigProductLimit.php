<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


namespace Aimeos\MW\Setup\Task;


/**
 * Migrates product limit plugin configuration.
 */
class PluginMigrateConfigProductLimit extends \Aimeos\MW\Setup\Task\Base
{
	private $mysql = array(
		'select' => 'SELECT COUNT(*) AS "cnt" FROM "mshop_plugin" WHERE "config" LIKE \'%"limit"%\'',
		'update' => 'UPDATE "mshop_plugin" SET "config" = REPLACE("config", \'"limit"\', \'"single-number-max"\') WHERE "config" LIKE \'%"limit"%\'',
	);

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
		$this->process( $this->mysql );
	}


	/**
	 * Add column to table if the column doesn't exist.
	 *
	 * @param array $stmts List of SQL statements to execute for adding columns
	 */
	protected function process( array $stmts )
	{
		$this->msg( 'Migrating configuration of "ProductLimit" plugin', 0 );

		if( $this->schema->columnExists( 'mshop_plugin', 'config' ) === true )
		{
			if( $this->getValue( $stmts['select'], 'cnt' ) > 0 )
			{
				$this->execute( $stmts['update'] );
				$this->status( 'migrated' );
				return;
			}
		}

		$this->status( 'OK' );
	}

}