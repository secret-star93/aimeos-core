<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Adds mediaurl column to table mshop_order_base_product.
 */
class MW_Setup_Task_OrderBaseProdAddMediaurl extends MW_Setup_Task_Abstract
{
	private $_mysql = array(
		'mediaurl' => 'ALTER TABLE "mshop_order_base_product" ADD "mediaurl" VARCHAR(255) NOT NULL AFTER "name"',
	);


	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function getPreDependencies()
	{
		return array( 'OrderBaseProductRenameLabelToName', 'OrderRenameTables' );
	}


	/**
	 * Returns the list of task names which depends on this task.
	 *
	 * @return string[] List of task names
	 */
	public function getPostDependencies()
	{
		return array( 'TablesCreateMShop' );
	}


	/**
	 * Executes the task for MySQL databases.
	 */
	protected function _mysql()
	{
		$this->_process( $this->_mysql );
	}


	/**
	 * Add column to table if the column doesn't exist.
	 *
	 * @param array $stmts List of SQL statements to execute for adding columns
	 */
	protected function _process( array $stmts )
	{
		$table = "mshop_order_base_product";
		$this->_msg( sprintf( 'Adding columns to table "%1$s"', $table ), 0 );
		$this->_status( '' );

		foreach( $stmts as $column => $stmt ) {
			$this->_msg( sprintf( 'Checking column "%1$s": ', $column ), 1 );

			if( $this->_schema->tableExists( $table ) === true &&
				$this->_schema->columnExists( $table, $column ) === false ) {
				$this->_execute( $stmt );
				$this->_status( 'added' );
			} else {
				$this->_status( 'OK' );
			}
		}
	}

}