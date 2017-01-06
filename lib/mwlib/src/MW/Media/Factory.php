<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package MW
 * @subpackage Media
 */


namespace Aimeos\MW\Media;


/**
 * Creates a new media object.
 *
 * @package MW
 * @subpackage Media
 */
class Factory
{
	/**
	 * Creates a new media object.
	 *
	 * Options for the factory are:
	 * - image: Associative list of image related options
	 * - application: Associative list of application related options
	 *
	 * @param resource|string|null $file File resource, path to the file or null for new files
	 * @param array $options Associative list of options for configuring the media class
	 * @return \Aimeos\MW\Media\Iface Media object
	 */
	public static function get( $file, array $options = array() )
	{
		$content = '';
		$mimetype = 'application/octet-stream';
		$finfo = new \finfo( FILEINFO_MIME_TYPE );

		if( is_resource( $file ) && ( $content = stream_get_contents( $file ) ) === false ) {
			throw new \Aimeos\MW\Media\Exception( sprintf( 'Unable to read from stream' ) );
		}

		if( is_string( $file ) && ( $content = @file_get_contents( $file ) ) === false ) {
			throw new \Aimeos\MW\Media\Exception( sprintf( 'Unable to read from file "%1$s"', $file ) );
		}

		if( $content !== '' ) {
			$mimetype = $finfo->buffer( $content );
		}

		$mimeparts = explode( '/', $mimetype );

		switch( $mimeparts[0] )
		{
			case 'image':
				return new \Aimeos\MW\Media\Image\Standard( $content, $mimetype, $options );
		}

		return new \Aimeos\MW\Media\Application\Standard( $content, $mimetype, $options );
	}
}
