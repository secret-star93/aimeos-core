<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2016
 */


class TestHelperCntl
{
	private static $aimeos;
	private static $context;


	/**
	 * Initializes the environment
	 */
	public static function bootstrap()
	{
		static::getAimeos();
		\Aimeos\MShop\Factory::setCache( false );
	}


	/**
	 * Returns the context object
	 *
	 * @param string $site Site code
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function getContext( $site = 'unittest' )
	{
		if( !isset( static::$context[$site] ) ) {
			static::$context[$site] = static::createContext( $site );
		}

		return clone static::$context[$site];
	}


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap Aimeos bootstrap object
	 */
	public static function getAimeos()
	{
		if( !isset( static::$aimeos ) )
		{
			require_once dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR . 'Bootstrap.php';

			static::$aimeos = new \Aimeos\Bootstrap( array(), false );
		}

		return static::$aimeos;
	}


	/**
	 * Creates a new context object
	 *
	 * @param string $site Site code
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	private static function createContext( $site )
	{
		$ctx = new \Aimeos\MShop\Context\Item\Standard();
		$aimeos = static::getAimeos();


		$paths = $aimeos->getConfigPaths();
		$paths[] = __DIR__ . DIRECTORY_SEPARATOR . 'config';
		$file = __DIR__ . DIRECTORY_SEPARATOR . 'confdoc.ser';

		$conf = new \Aimeos\MW\Config\PHPArray( array(), $paths );
		$conf = new \Aimeos\MW\Config\Decorator\Memory( $conf );
		$conf = new \Aimeos\MW\Config\Decorator\Documentor( $conf, $file );
		$ctx->setConfig( $conf );


		$dbm = new \Aimeos\MW\DB\Manager\PDO( $conf );
		$ctx->setDatabaseManager( $dbm );


		$logger = new \Aimeos\MW\Logger\File( 'unittest.log', \Aimeos\MW\Logger\Base::DEBUG );
		$ctx->setLogger( $logger );


		$session = new \Aimeos\MW\Session\None();
		$ctx->setSession( $session );


		$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $ctx );
		$locale = $localeManager->bootstrap( $site, '', '', false );
		$ctx->setLocale( $locale );


		$ctx->setEditor( 'core:controller/common' );

		return $ctx;
	}
}
