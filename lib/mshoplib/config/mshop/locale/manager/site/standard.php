<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

return array(
	'delete' => array(
		'ansi' => '
			DELETE FROM "mshop_locale_site"
			WHERE :cond
		'
	),
	'insert' => array(
		'ansi' => '
			INSERT INTO "mshop_locale_site" (
				"code", "label", "config", "status", "parentid", "editor",
				"mtime", "ctime", "level", "nleft", "nright"
			)
			SELECT ?, ?, ?, ?, ?, ?, ?, ?, 0, COALESCE( MAX("nright"), 0 ) + 1,
				COALESCE( MAX("nright"), 0 ) + 2
			FROM "mshop_locale_site"
		'
	),
	'update' => array(
		'ansi' => '
			UPDATE "mshop_locale_site"
			SET "code" = ?, "label" = ?, "config" = ?, "status" = ?,
				"editor" = ?, "mtime" = ?
			WHERE id = ?
		'
	),
	'search' => array(
		'ansi' => '
			SELECT DISTINCT mlocsi."id", mlocsi."parentid", mlocsi."code",
				mlocsi."label", mlocsi."config", mlocsi."status",
				mlocsi."editor", mlocsi."mtime", mlocsi."ctime"
			FROM "mshop_locale_site" AS mlocsi
			WHERE :cond
			ORDER BY :order
			LIMIT :size OFFSET :start
		'
	),
	'count' => array(
		'ansi' => '
			SELECT COUNT(*) AS "count"
			FROM (
				SELECT DISTINCT mlocsi."id"
				FROM "mshop_locale_site" AS mlocsi
				WHERE :cond
				LIMIT 10000 OFFSET 0
			) AS list
		'
	),
	'newid' => array(
		'mysql' => 'SELECT LAST_INSERT_ID()'
	),
);

