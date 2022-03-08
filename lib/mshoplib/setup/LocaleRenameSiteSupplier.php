<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */


namespace Aimeos\Upscheme\Task;


class LocaleRenameSiteSupplier extends Base
{
	public function before() : array
	{
		return ['Locale'];
	}


	public function up()
	{
		$db = $this->db( 'db-locale' );

		if( !$db->hasColumn( 'mshop_locale_site', 'supplierid' ) ) {
			return;
		}

		$this->info( 'Rename "supplierid" column in "mshop_locale_site"', 'v' );

		$db->renameColumn( 'mshop_locale_site', 'supplierid', 'refid' );
	}
}
