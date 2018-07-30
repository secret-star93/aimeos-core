<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


return [
	'product/type' => [
		'product/bundle' => ['product.type.domain' => 'product', 'product.type.code' => 'bundle', 'product.type.label' => 'Bundle', 'product.type.status' => 1],
		'product/default' => ['product.type.domain' => 'product', 'product.type.code' => 'default', 'product.type.label' => 'Article', 'product.type.status' => 1],
		'product/select' => ['product.type.domain' => 'product', 'product.type.code' => 'select', 'product.type.label' => 'Selection', 'product.type.status' => 1],
		'product/voucher' => ['product.type.domain' => 'product', 'product.type.code' => 'voucher', 'product.type.label' => 'Voucher', 'product.type.status' => 1],
	],

	'product/lists/type' => [
		'product/default' => ['product.lists.type.domain' => 'product', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'attribute/default' => ['product.lists.type.domain' => 'attribute', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'attribute/config' => ['product.lists.type.domain' => 'attribute', 'product.lists.type.code' => 'config', 'product.lists.type.label' => 'Configurable', 'product.lists.type.status' => 1],
		'attribute/variant' => ['product.lists.type.domain' => 'attribute', 'product.lists.type.code' => 'variant', 'product.lists.type.label' => 'Variant', 'product.lists.type.status' => 1],
		'attribute/hidden' => ['product.lists.type.domain' => 'attribute', 'product.lists.type.code' => 'hidden', 'product.lists.type.label' => 'Hidden', 'product.lists.type.status' => 1],
		'attribute/custom' => ['product.lists.type.domain' => 'attribute', 'product.lists.type.code' => 'custom', 'product.lists.type.label' => 'Custom value', 'product.lists.type.status' => 1],
		'catalog/default' => ['product.lists.type.domain' => 'catalog', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'media/default' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'media/download' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'download', 'product.lists.type.label' => 'Download', 'product.lists.type.status' => 1],
		'price/default' => ['product.lists.type.domain' => 'price', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'service/default' => ['product.lists.type.domain' => 'service', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'supplier/default' => ['product.lists.type.domain' => 'supplier', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'text/default' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'tag/default' => ['product.lists.type.domain' => 'tag', 'product.lists.type.code' => 'default', 'product.lists.type.label' => 'Standard', 'product.lists.type.status' => 1],
		'product/suggestion' => ['product.lists.type.domain' => 'product', 'product.lists.type.code' => 'suggestion', 'product.lists.type.label' => 'Suggestion', 'product.lists.type.status' => 1],
		'product/bought' => ['product.lists.type.domain' => 'product', 'product.lists.type.code' => 'bought-together', 'product.lists.type.label' => 'Bought together', 'product.lists.type.status' => 1],
		//pictures
		'media/unittype1' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype1', 'product.lists.type.label' => 'Unit type 1', 'product.lists.type.status' => 1],
		'media/unittype2' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype2', 'product.lists.type.label' => 'Unit type 2', 'product.lists.type.status' => 1],
		'media/unittype3' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype3', 'product.lists.type.label' => 'Unit type 3', 'product.lists.type.status' => 1],
		'media/unittype4' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype4', 'product.lists.type.label' => 'Unit type 4', 'product.lists.type.status' => 1],
		'media/unittype5' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype5', 'product.lists.type.label' => 'Unit type 5', 'product.lists.type.status' => 1],
		'media/unittype6' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype6', 'product.lists.type.label' => 'Unit type 6', 'product.lists.type.status' => 1],
		'media/unittype7' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype7', 'product.lists.type.label' => 'Unit type 7', 'product.lists.type.status' => 1],
		'media/unittype8' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype8', 'product.lists.type.label' => 'Unit type 8', 'product.lists.type.status' => 1],
		'media/unittype9' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype9', 'product.lists.type.label' => 'Unit type 9', 'product.lists.type.status' => 1],
		'media/unittype10' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype10', 'product.lists.type.label' => 'Unit type 10', 'product.lists.type.status' => 1],
		'media/unittype11' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype11', 'product.lists.type.label' => 'Unit type 11', 'product.lists.type.status' => 1],
		'media/unittype12' => ['product.lists.type.domain' => 'media', 'product.lists.type.code' => 'unittype12', 'product.lists.type.label' => 'Unit type 12', 'product.lists.type.status' => 1],
		//products texts
		'text/unittype13' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype13', 'product.lists.type.label' => 'Unit type 13', 'product.lists.type.status' => 1],
		'text/unittype14' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype14', 'product.lists.type.label' => 'Unit type 14', 'product.lists.type.status' => 1],
		'text/unittype15' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype15', 'product.lists.type.label' => 'Unit type 15', 'product.lists.type.status' => 1],
		'text/unittype16' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype16', 'product.lists.type.label' => 'Unit type 16', 'product.lists.type.status' => 1],
		'text/unittype17' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype17', 'product.lists.type.label' => 'Unit type 17', 'product.lists.type.status' => 1],
		'text/unittype18' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype18', 'product.lists.type.label' => 'Unit type 18', 'product.lists.type.status' => 1],
		'text/unittype19' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype19', 'product.lists.type.label' => 'Unit type 19', 'product.lists.type.status' => 1],
		'text/unittype20' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype20', 'product.lists.type.label' => 'Unit type 20', 'product.lists.type.status' => 1],
		'text/unittype21' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype21', 'product.lists.type.label' => 'Unit type 21', 'product.lists.type.status' => 1],
		'text/unittype22' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype22', 'product.lists.type.label' => 'Unit type 22', 'product.lists.type.status' => 1],
		'text/unittype23' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype23', 'product.lists.type.label' => 'Unit type 23', 'product.lists.type.status' => 1],
		'text/unittype24' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype24', 'product.lists.type.label' => 'Unit type 24', 'product.lists.type.status' => 1],
		'text/unittype25' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype25', 'product.lists.type.label' => 'Unit type 25', 'product.lists.type.status' => 1],
		'text/unittype26' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype26', 'product.lists.type.label' => 'Unit type 26', 'product.lists.type.status' => 1],
		'text/unittype27' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype27', 'product.lists.type.label' => 'Unit type 27', 'product.lists.type.status' => 1],
		'text/unittype28' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype28', 'product.lists.type.label' => 'Unit type 28', 'product.lists.type.status' => 1],
		'text/unittype29' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype29', 'product.lists.type.label' => 'Unit type 29', 'product.lists.type.status' => 1],
		'text/unittype30' => ['product.lists.type.domain' => 'text', 'product.lists.type.code' => 'unittype30', 'product.lists.type.label' => 'Unit type 30', 'product.lists.type.status' => 1],
	],

	'product/property/type' => [
		'product/package-height' => ['product.property.type.domain' => 'product', 'product.property.type.code' => 'package-height', 'product.property.type.label' => 'Package height', 'product.property.type.status' => 1],
		'product/package-length' => ['product.property.type.domain' => 'product', 'product.property.type.code' => 'package-length', 'product.property.type.label' => 'Package length', 'product.property.type.status' => 1],
		'product/package-width' => ['product.property.type.domain' => 'product', 'product.property.type.code' => 'package-width', 'product.property.type.label' => 'Package width', 'product.property.type.status' => 1],
		'product/package-weight' => ['product.property.type.domain' => 'product', 'product.property.type.code' => 'package-weight', 'product.property.type.label' => 'Package Weight', 'product.property.type.status' => 1],
	],

	'product' => [
		'product/ABCD' => [
			'product.typeid' => 'product/default', 'product.label' => '16 discs', 'product.code' => 'ABCD', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xl', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/15.00/1.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/12.00/1.50', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/default', 'product.lists.refid' => 'text/subproduct1', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/EFGH' => [
			'product.typeid' => 'product/default', 'product.label' => '16 discs', 'product.code' => 'EFGH', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xxl', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/25.00/2.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/22.00/2.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/default', 'product.lists.refid' => 'text/subproduct2', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/IJKL' => [
			'product.typeid' => 'product/default', 'product.label' => '16 discs', 'product.code' => 'IJKL', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xl', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/12.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/default', 'product.lists.refid' => 'text/subproduct3', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/MNOP' => [
			'product.typeid' => 'product/default', 'product.label' => '16 discs', 'product.code' => 'MNOP', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/m', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/QRST' => [
			'product.typeid' => 'product/default', 'product.label' => '16 discs', 'product.code' => 'QRST', 'product.status' => 0,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xl', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/50.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/U:MD' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Monetary rebate', 'product.code' => 'U:MD', 'product.status' => 0,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/29.95/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/unittype25', 'product.lists.refid' => 'text/rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype26', 'product.lists.refid' => 'text/money_rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:SD' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Shipping rebate', 'product.code' => 'U:SD', 'product.status' => 0,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/19.95/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'product' => [
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/MNOP', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/unittype27', 'product.lists.refid' => 'text/delivery_rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype28', 'product.lists.refid' => 'text/shipping_rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:PD' => [
			'product.typeid' => 'product/voucher', 'product.label' => 'Unittest: Present rebate', 'product.code' => 'U:PD', 'product.status' => 0,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/199.95/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'product' => [
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/QRST', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/unittype29', 'product.lists.refid' => 'text/gift_rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype30', 'product.lists.refid' => 'text/present_rebate', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:WH' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Present rebate', 'product.code' => 'U:WH', 'product.status' => 0,
		],
		'product/U:CF' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Cheapest free rebate', 'product.code' => 'U:CF', 'product.status' => 0,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/0.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/default', 'product.lists.refid' => 'text/productUT', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/U:TESTSUB01' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test Sub 1', 'product.code' => 'U:TESTSUB01', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/color/white', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/m', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:TESTSUB02' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test Sub 2', 'product.code' => 'U:TESTSUB02', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/length/30', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/width/30', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:TESTSUB03' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test Sub 3', 'product.code' => 'U:TESTSUB03', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/color/blue', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/l', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:TESTSUB04' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test Sub 4', 'product.code' => 'U:TESTSUB04', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/length/32', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/width/30', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/28.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/U:TESTSUB05' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test Sub 5', 'product.code' => 'U:TESTSUB05', 'product.status' => 1,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/28.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/purchase/12.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
		],
		'product/U:TEST' => [
			'product.typeid' => 'product/select', 'product.label' => 'Unittest: Test Selection', 'product.code' => 'U:TEST', 'product.status' => 1,
			'media' => [
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/path/to/folder/example5.jpg', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/path/to/folder/example6.jpg', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
					],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/18.00/1.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'product' => [
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTSUB01', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTSUB02', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTSUB03', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTSUB04', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTSUB05', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
			],
		],
		'product/U:noSel' => [
			'product.typeid' => 'product/select', 'product.label' => 'Unittest: Empty Selection', 'product.code' => 'U:noSel', 'product.status' => 1,
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/12.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/11.00/0.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
					],
		],
		'product/U:TESTPSUB01' => [
			'product.typeid' => 'product/default', 'product.label' => 'Unittest: Test priced Sub 1', 'product.code' => 'U:TESTPSUB01', 'product.status' => 1,
		],
		'product/U:TESTP' => [
			'product.typeid' => 'product/select', 'product.label' => 'Unittest: Test priced Selection', 'product.code' => 'U:TESTP', 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/config', 'product.lists.refid' => 'attribute/product/color/white', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/config', 'product.lists.refid' => 'attribute/product/size/xs', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/hidden', 'product.lists.refid' => 'attribute/product/width/29', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/custom', 'product.lists.refid' => 'attribute/product/date/custom', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/custom', 'product.lists.refid' => 'attribute/product/price/custom', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'product' => [
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/U:TESTPSUB01', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/18.00/1.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
		'product/bdl:zyx' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:zyx', 'product.code' => 'bdl:zyx', 'product.status' => 1,
		],
		'product/bdl:EFG' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:EFG', 'product.code' => 'bdl:EFG', 'product.status' => 1,
		],
		'product/bdl:HIJ' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:HIJ', 'product.code' => 'bdl:HIJ', 'product.status' => 1,
		],
		'product/bdl:hal' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:hal', 'product.code' => 'bdl:hal', 'product.status' => 1,
		],
		'product/bdl:EFX' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:EFX', 'product.code' => 'bdl:EFX', 'product.status' => 1,
		],
		'product/bdl:HKL' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle bdl:HKL', 'product.code' => 'bdl:HKL', 'product.status' => 1,
		],
		'product/CNC' => [
			'product.typeid' => 'product/default', 'product.label' => 'Cafe Noire Cappuccino', 'product.code' => 'CNC', 'product.config' => ['css-class' => 'sale'], 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xs', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
			'media' => [
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/prod_123x103/195_prod_123x103.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/prod_266x221/198_prod_266x221.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype9', 'product.lists.refid' => 'media/prod_114x95/194_prod_114x95.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype10', 'product.lists.refid' => 'media/prod_179x178/196_prod_179x178.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype11', 'product.lists.refid' => 'media/prod_242x416/197_CafeNoire_Colombia_242x416.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype12', 'product.lists.refid' => 'media/prod_97x93/199_prod_97x93.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/600.00/30.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/580.00/20.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'product/property' => [
				['product.property.typeid' => 'product/package-height', 'product.property.languageid' => null, 'product.property.value' => '10.0'],
				['product.property.typeid' => 'product/package-length', 'product.property.languageid' => null, 'product.property.value' => '20.0'],
				['product.property.typeid' => 'product/package-width', 'product.property.languageid' => null, 'product.property.value' => '15.0'],
				['product.property.typeid' => 'product/package-weight', 'product.property.languageid' => null, 'product.property.value' => '1.25'],
			],
			'tag' => [
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/Kaffee', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/Cappuccino', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/mild', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/cremig', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/unittype19', 'product.lists.refid' => 'text/cnc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype20', 'product.lists.refid' => 'text/cnc_short_desc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype21', 'product.lists.refid' => 'text/cnc_long_desc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype22', 'product.lists.refid' => 'text/cnc_metatitle', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype23', 'product.lists.refid' => 'text/cnc_metakey', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype24', 'product.lists.refid' => 'text/cnc_metadesc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
			],
		],
		'product/CNE' => [
			'product.typeid' => 'product/default', 'product.label' => 'Cafe Noire Expresso', 'product.code' => 'CNE', 'product.config' => ['css-class' => 'top', 'size' => 1], 'product.status' => 1,
			'attribute' => [
				['product.lists.typeid' => 'attribute/default', 'product.lists.refid' => 'attribute/product/size/xs', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/length/30', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/variant', 'product.lists.refid' => 'attribute/product/width/29', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/config', 'product.lists.refid' => 'attribute/product/color/white', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/config', 'product.lists.refid' => 'attribute/product/interval/P1Y0M0W0D', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
				['product.lists.typeid' => 'attribute/hidden', 'product.lists.refid' => 'attribute/product/download/testurl', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
			],
			'media' => [
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/prod_123x103/195_prod_123x103.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/default', 'product.lists.refid' => 'media/prod_266x221/198_prod_266x221.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype3', 'product.lists.refid' => 'media/prod_114x95/194_prod_114x95.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2100-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype4', 'product.lists.refid' => 'media/prod_179x178/196_prod_179x178.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype5', 'product.lists.refid' => 'media/prod_242x416/197_CafeNoire_Colombia_242x416.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/unittype6', 'product.lists.refid' => 'media/prod_97x93/199_prod_97x93.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => '2010-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
				['product.lists.typeid' => 'media/download', 'product.lists.refid' => 'media/path/to/folder/example5.jpg', 'product.lists.datestart' => '2000-01-01 00:00:00', 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/18.00/1.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/15.00/1.50', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'product' => [
				['product.lists.typeid' => 'product/bought', 'product.lists.refid' => 'product/CNC', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/suggestion', 'product.lists.refid' => 'product/CNC', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/ABCD', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/EFGH', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/IJKL', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
			],
			'product/property' => [
				['product.property.typeid' => 'product/package-height', 'product.property.languageid' => null, 'product.property.value' => '10.0'],
				['product.property.typeid' => 'product/package-length', 'product.property.languageid' => null, 'product.property.value' => '25.00'],
				['product.property.typeid' => 'product/package-width', 'product.property.languageid' => null, 'product.property.value' => '17.5'],
				['product.property.typeid' => 'product/package-weight', 'product.property.languageid' => null, 'product.property.value' => '1'],
			],
			'tag' => [
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/Expresso', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/Kaffee', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'tag/default', 'product.lists.refid' => 'tag/herb', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
			],
			'text' => [
				['product.lists.typeid' => 'text/default', 'product.lists.refid' => 'text/cne_basket', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne_short_desc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne_long_desc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 2, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne_metatitle', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 3, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne_metakey', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 4, 'product.lists.status' => 1],
				['product.lists.typeid' => 'text/unittype13', 'product.lists.refid' => 'text/cne_metadesc', 'product.lists.datestart' => '2010-01-01 00:00:00', 'product.lists.dateend' => '2022-01-01 00:00:00', 'product.lists.config' => [], 'product.lists.position' => 5, 'product.lists.status' => 1],
			],
		],
		'product/U:BUNDLE' => [
			'product.typeid' => 'product/bundle', 'product.label' => 'Unittest: Bundle', 'product.code' => 'U:BUNDLE', 'product.status' => 1,
			'product' => [
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/CNC', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
				['product.lists.typeid' => 'product/default', 'product.lists.refid' => 'product/CNE', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 1, 'product.lists.status' => 1],
			],
			'price' => [
				['product.lists.typeid' => 'price/default', 'product.lists.refid' => 'price/product/default/600.00/30.00', 'product.lists.datestart' => null, 'product.lists.dateend' => null, 'product.lists.config' => [], 'product.lists.position' => 0, 'product.lists.status' => 1],
			],
		],
	],
];
