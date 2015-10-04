<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MShop
 */


/**
 * Factory which can create all MAdmin managers.
 *
 * @package MAdmin
 */
class MAdmin_Factory
{
	static private $cache = true;
	static private $managers = array();


	/**
	 * Removes all manager objects from the cache.
	 *
	 * If neither a context ID nor a path is given, the complete cache will be pruned.
	 *
	 * @param integer $id Context ID the objects have been created with (string of MShop_Context_Item_Iface)
	 * @param string $path Path describing the manager to clear, e.g. "product/lists/type"
	 */
	static public function clear( $id = null, $path = null )
	{
		if( $id !== null )
		{
			if( $path !== null ) {
				self::$managers[$id][$path] = null;
			} else {
				self::$managers[$id] = array();
			}

			return;
		}

		self::$managers = array();
	}


	/**
	 * Creates the required manager specified by the given path of manager names.
	 *
	 * Domain managers are created by providing only the domain name, e.g.
	 * "product" for the MAdmin_Log_Manager_Standard or a path of names to
	 * retrieve a specific sub-manager.
	 * Please note, that only the default managers can be created. If you need
	 * a specific implementation, you need to use the factory class of the
	 * domain or the getSubManager() method to hand over specifc implementation
	 * names.
	 *
	 * @param MShop_Context_Item_Iface $context Context object required by managers
	 * @param string $path Name of the domain (and sub-managers) separated by slashes, e.g "log"
	 * @throws MAdmin_Exception If the given path is invalid or the manager wasn't found
	 */
	static public function createManager( MShop_Context_Item_Iface $context, $path )
	{
		if( empty( $path ) ) {
			throw new MAdmin_Exception( sprintf( 'Manager path is empty' ) );
		}

		$id = (string) $context;

		if( !isset( self::$managers[$id][$path] ) )
		{
			$parts = explode( '/', $path );

			foreach( $parts as $part )
			{
				if( ctype_alnum( $part ) === false ) {
					throw new MAdmin_Exception( sprintf( 'Invalid characters in manager name "%1$s" in "%2$s"', $part, $path ) );
				}
			}

			if( ( $name = array_shift( $parts ) ) === null ) {
				throw new MAdmin_Exception( sprintf( 'Manager path "%1$s" is invalid', $path ) );
			}


			if( !isset( self::$managers[$id][$name] ) )
			{
				$factory = 'MAdmin_' . ucwords( $name ) . '_Manager_Factory';

				if( class_exists( $factory ) === false ) {
					throw new MAdmin_Exception( sprintf( 'Class "%1$s" not available', $factory ) );
				}

				$manager = @call_user_func_array( array( $factory, 'createManager' ), array( $context ) );

				if( $manager === false ) {
					throw new MAdmin_Exception( sprintf( 'Invalid factory "%1$s"', $factory ) );
				}

				self::$managers[$id][$name] = $manager;
			}


			foreach( $parts as $part )
			{
				$tmpname = $name . '/' . $part;

				if( !isset( self::$managers[$id][$tmpname] ) ) {
					self::$managers[$id][$tmpname] = self::$managers[$id][$name]->getSubManager( $part );
				}

				$name = $tmpname;
			}
		}

		return self::$managers[$id][$path];
	}


	/**
	 * Enables or disables caching of class instances.
	 *
	 * @param boolean $value True to enable caching, false to disable it.
	 * @return boolean Previous cache setting
	 */
	static public function setCache( $value )
	{
		$old = self::$cache;
		self::$cache = (boolean) $value;

		return $old;
	}
}
