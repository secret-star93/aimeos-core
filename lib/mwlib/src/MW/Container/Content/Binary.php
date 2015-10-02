<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MW
 * @subpackage Container
 */


/**
 * Implementation of the binary content object.
 *
 * @package MW
 * @subpackage Container
 */
class MW_Container_Content_Binary
	extends MW_Container_Content_Abstract
	implements MW_Container_Content_Interface
{
	private $fh;
	private $data;
	private $position = 0;
	private $size;


	/**
	 * Initializes the text content object.
	 *
	 * Supported options are:
	 * - bin-maxsize (default: 1MB)
	 *
	 * @param string $resource Path to the actual file
	 * @param string $name Name of the file
	 * @param array $options Associative list of key/value pairs for configuration
	 */
	public function __construct( $resource, $name, array $options = array() )
	{
		if( ( $this->fh = @fopen( $resource, 'a+' ) ) === false
			&& ( $this->fh = fopen( $resource, 'r' ) ) === false
		) {
			throw new MW_Container_Exception( sprintf( 'Unable to open file "%1$s"', $resource ) );
		}

		parent::__construct( $resource, $name, $options );

		$this->size = $this->getOption( 'bin-maxsize', 0x100000 );
		$this->data = $this->getData();
	}


	/**
	 * Closes the text file so it's written to disk.
	 *
	 * @throws MW_Container_Exception If the file handle couldn't be flushed or closed
	 */
	public function close()
	{
		if( fflush( $this->fh ) === false ) {
			throw new MW_Container_Exception( sprintf( 'Unable to flush file "%1$s"', $this->getResource() ) );
		}

		if( fclose( $this->fh ) === false ) {
			throw new MW_Container_Exception( sprintf( 'Unable to close file "%1$s"', $this->getResource() ) );
		}
	}


	/**
	 * Adds row to the content object.
	 *
	 * @param string[] $data Data to add
	 */
	public function add( $data )
	{
		if( fwrite( $this->fh, $data ) === false ) {
			throw new MW_Container_Exception( sprintf( 'Unable to add content to file "%1$s"', $this->getName() ) );
		}
	}


	/**
	 * Return the current element.
	 *
	 * @return string Content line ending with
	 */
	function current()
	{
		return $this->data;
	}


	/**
	 * Returns the key of the current element.
	 *
	 * @return integer|null Position within the text file or null if end of file is reached
	 */
	function key()
	{
		if( $this->data !== null ) {
			return $this->position;
		}

		return null;
	}


	/**
	 * Moves forward to next element.
	 */
	function next()
	{
		$this->position++;
		$this->data = $this->getData();
	}


	/**
	 * Rewinds the file pointer to the beginning.
	 */
	function rewind()
	{
		if( rewind( $this->fh ) === 0 ) {
			throw new MW_Container_Exception( sprintf( 'Rewind file handle for %1$s failed', $this->getResource() ) );
		}

		$this->position = 0;
		$this->data = $this->getData();
	}


	/**
	 * Checks if the current position is valid.
	 *
	 * @return boolean True on success or false on failure
	 */
	function valid()
	{
		return ( $this->data === null ? !feof( $this->fh ) : true );
	}


	/**
	 * Reads the next chunk from the file.
	 *
	 * @return string Data
	 */
	protected function getData()
	{
		if( ( $data = fgets( $this->fh, $this->size ) ) === false ) {
			return null;
		}

		return $data;
	}
}
