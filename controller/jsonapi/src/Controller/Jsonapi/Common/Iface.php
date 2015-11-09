<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Controller
 * @subpackage Jsonapi
 */


namespace Aimeos\Controller\Jsonapi\Common;


/**
 * JSON API controller interface
 *
 * @package Controller
 * @subpackage Jsonapi
 */
interface Iface
	extends \Aimeos\Controller\Jsonapi\Iface
{
	/**
	 * Initializes the controller
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context MShop context object
	 * @param array $templatePaths List of file system paths where the templates are stored
	 * @param string $path Name of the controller separated by slashes, e.g "product/stock"
	 * @return void
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context, array $templatePaths, $path );
}
