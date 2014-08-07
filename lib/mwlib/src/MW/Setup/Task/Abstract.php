<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.gnu.org/licenses/lgpl.html
 * @package MW
 * @subpackage Setup
 */


/**
 * Creates the required table.
 *
 * @package MW
 * @subpackage Setup
 */
abstract class MW_Setup_Task_Abstract implements MW_Setup_Task_Interface
{
	private $_connections = array();
	private $_schemas = array();
	protected $_additional;

	/** @deprecated Use getSchema() instead */
	protected $_schema;

	/** @deprecated Use getConnection() instead */
	protected $_conn;


	/**
	 * Initializes the task object.
	 *
	 * @param MW_Setup_DBSchema_Interface $schema Database schema object
	 * @param MW_DB_Connection_Interface $conn Database connection
	 * @param mixed $additional Additionally provided information for the setup tasks if required
	 */
	public function __construct( MW_Setup_DBSchema_Interface $schema, MW_DB_Connection_Interface $conn, $additional = null )
	{
		$this->_schema = $schema;
		$this->_conn = $conn;
		$this->_additional = $additional;
	}


	/**
	 * Executes the task for the given database type.
	 *
	 * @param string $dbtype Database type string
	 */
	public function run( $dbtype )
	{
		switch( $dbtype )
		{
			case 'mysql': $this->_mysql( $this->_schema ); break;
			default:
				throw new MW_Setup_Exception( sprintf( 'Unknown database type "%1$s"', $dbtype ) );
		}
	}


	/**
	 * Sets the associative list of connections with the resource name as key.
	 *
	 * @param array $conns Associative list of connections
	 */
	public function setConnections( array $conns )
	{
		$this->_connections = $conns;
	}


	/**
	 * Sets the associative list of schemas with the resource name as key.
	 *
	 * @param array $schemas Associative list of schemas
	 */
	public function setSchemas( array $schemas )
	{
		$this->_schemas = $schemas;
	}


	/**
	 * Executes the task for MySQL databases.
	 */
	protected abstract function _mysql();


	/**
	 * Executes a given SQL statement.
	 *
	 * @param string $sql SQL statement to execute
	 * @param string $name Name from the resource configuration
	 */
	protected function _execute( $sql, $name = 'db' )
	{
		$this->_getConnection( $name )->create( $sql )->execute()->finish();
	}


	/**
	 * Executes a list of given SQL statements.
	 *
	 * @param array $list List of SQL statement to execute
	 * @param string $name Name from the resource configuration
	 */
	protected function _executeList( array $list, $name = 'db' )
	{
		$conn = $this->_getConnection( $name );

		foreach( $list as $sql ) {
			$conn->create( $sql )->execute()->finish();
		}
	}


	/**
	 * Executes a given SQL statement and returns the value of the named column and first row.
	 *
	 * @param string $sql SQL statement to execute
	 * @param string $column Column name to retrieve
	 * @param string $name Name from the resource configuration
	 */
	protected function _getValue( $sql, $column, $name = 'db' )
	{
		$result = $this->_getConnection( $name )->create( $sql )->execute();

		if( ( $row = $result->fetch() ) === false ) {
			throw new MW_Setup_Exception( sprintf( 'No rows found: %1$s', $sql ) );
		}

		if( array_key_exists( $column, $row ) === false ) {
			throw new MW_Setup_Exception( sprintf( 'No column "%1$s" found: %2$s', $column, $sql ) );
		}

		$result->finish();

		return $row[$column];
	}


	/**
	 * Returns the connection specified by the given resource name.
	 *
	 * @param string $name Name from resource configuration
	 * @return MW_DB_Connection_Interface
	 */
	protected function _getConnection( $name )
	{
		if( !isset( $this->_connections[$name] ) ) {
			return $this->_conn;
		}

		return $this->_connections[$name];
	}


	/**
	 * Returns the schemas specified by the given resource name.
	 *
	 * @param string $name Name from resource configuration
	 * @return MW_Setup_DBSchema_Interface
	 */
	protected function _getSchema( $name )
	{
		if( !isset( $this->_schemas[$name] ) ) {
			return $this->_schema;
		}

		return $this->_schemas[$name];
	}


	/**
	 * Prints the message for the current test.
	 *
	 * @param string $msg Current message
	 * @param integer $level Indent level of the message (default: 0 )
	 */
	protected function _msg( $msg, $level = 0 )
	{
		$pre = '';
		for( $i = 0; $i < 2*$level; $i++ ) { $pre .= ' '; }

		echo str_pad( $pre . $msg, 70 );
	}


	/**
	 * Prints the status for the current test.
	 *
	 * @param string $status Current status
	 */
	protected function _status( $status )
	{
		echo $status . PHP_EOL;
	}


	/**
	 * Extracts the table definitions from the given content.
	 *
	 * @param string $content Content of the file to parse
	 * @return array Associative list of table names with table create statements ordered like in the file
	 */
	protected function _getTableDefinitions( $content )
	{
		$defs = array( );
		$matches = array( );

		$regex = '/CREATE TABLE \"?([a-zA-Z0-9_]+)\"? .*(\n\n|$)/sU';
		if ( preg_match_all($regex, $content, $matches, PREG_SET_ORDER) === false ) {
			throw new MW_Setup_Exception('Unable to get table definitions');
		}

		foreach ( $matches as $match ) {
			$defs[$match[1]] = $match[0];
		}

		return $defs;
	}


	/**
	 * Extracts the index definitions from the given content.
	 *
	 * @param string $content Content of the file to parse
	 * @return array Associative list of index names with index create statements ordered like in the file
	 */
	protected function _getIndexDefinitions( $content )
	{
		$defs = array( );
		$matches = array( );

		if ( preg_match_all('/CREATE [a-zA-Z]* ?INDEX \"?([a-zA-Z0-9_]+)\"? ON \"?([a-zA-Z0-9_]+)\"? .+(\n\n|$)/sU', $content, $matches, PREG_SET_ORDER) === false ) {
			throw new MW_Setup_Exception('Unable to get index definitions');
		}

		foreach ( $matches as $match ) {
			$name = $match[2] . '.' . $match[1];
			$defs[$name] = $match[0];
		}

		return $defs;
	}


	/**
	 * Extracts the trigger definitions from the given content.
	 *
	 * @param string $content Content of the file to parse
	 * @return array Associative list of trigger names with trigger create statements ordered like in the file
	 */
	protected function _getTriggerDefinitions( $content )
	{
		$defs = array( );
		$matches = array( );

		$regex = '/CREATE TRIGGER \"?([a-zA-Z0-9_]+)\"? .*(\n\n|$)/sU';
		if ( preg_match_all($regex, $content, $matches, PREG_SET_ORDER) === false ) {
			throw new MW_Setup_Exception('Unable to get trigger definitions');
		}

		foreach ( $matches as $match ) {
			$defs[$match[1]] = $match[0];
		}

		return $defs;
	}
}
