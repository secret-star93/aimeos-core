<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */


return array(
	'manager' => array(
		'standard' => array(
			'delete' => array(
				'ansi' => '
					DELETE FROM "madmin_cache" WHERE :cond
				',
			),
			'deletebytag' => array(
				'ansi' => '
					DELETE FROM "madmin_cache" WHERE id IN (
						SELECT "tid" FROM "madmin_cache_tag" WHERE :cond
					)
				',
			),
			'get' => array(
				'ansi' => '
					SELECT "id", "value", "expire" FROM "madmin_cache"
					WHERE :cond
				',
			),
			'set' => array(
				'ansi' => '
					INSERT INTO "madmin_cache" (
						"id", "expire", "value"
					) VALUES (
						?, ?, ?
					)
				',
			),
			'settag' => array(
				'ansi' => '
					INSERT INTO "madmin_cache_tag" (
						"tid", "tname"
					) VALUES (
						?, ?
					)
				',
			),
			'search' => array(
				'ansi' => '
					SELECT "id", "value", "expire" FROM "madmin_cache"
					WHERE :cond
					LIMIT :size OFFSET :start
				',
			),
			'count' => array(
				'ansi' => '
					SELECT COUNT(*) AS "count"
					FROM(
						SELECT DISTINCT "id"
						FROM "madmin_cache"
						WHERE :cond
						LIMIT 10000 OFFSET 0
					) AS list
				',
			),
		),
	),
);
