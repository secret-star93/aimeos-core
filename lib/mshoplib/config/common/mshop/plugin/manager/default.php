<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

return array(
	'item' => array(
		'delete' => '
			DELETE FROM "mshop_plugin"
			WHERE :cond AND siteid = ?
		',
		'insert' => '
			INSERT INTO "mshop_plugin"(
				"siteid", "typeid", "label", "provider", "config", "pos",
				"status", "mtime", "editor", "ctime"
			) VALUES (
				?, ?, ?, ?, ?, ?, ?, ?, ?, ?
			)
		',
		'update' => '
			UPDATE "mshop_plugin"
			SET "siteid" = ?, "typeid" = ?, "label" = ?, "provider" = ?,
				"config" = ?, "pos" = ?, "status" = ?, "mtime" = ?,
				"editor" = ?
			WHERE "id" = ?
		',
		'search' => '
			SELECT DISTINCT mplu."id", mplu."siteid", mplu."typeid",
				mplu."label", mplu."provider", mplu."config", mplu."pos",
				mplu."status", mplu."mtime", mplu."editor", mplu."ctime"
			FROM "mshop_plugin" mplu
			:joins
			WHERE :cond
			/*-orderby*/ ORDER BY :order /*orderby-*/
			LIMIT :size OFFSET :start
		',
		'count' => '
			SELECT COUNT(*) AS "count"
			FROM (
				SELECT DISTINCT mplu."id"
				FROM "mshop_plugin" mplu
				:joins
				WHERE :cond
				LIMIT 10000 OFFSET 0
			) AS list
		',
	)
);
