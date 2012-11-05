/*!
 * Copyright (c) Metaways Infosystems GmbH, 2011
 * LGPLv3, http://www.arcavias.com/en/license
 * $Id: AttributeItemPickerUi.js 14263 2011-12-11 16:36:17Z nsendetzky $
 */


Ext.ns('MShop.panel.text');

// hook media picker into the text ItemUi
Ext.ux.ItemRegistry.registerItem('MShop.panel.text.ItemUi', {
	xtype : 'MShop.panel.attribute.itempickerui',
	itemConfig : {
		recordName : 'Text_List',
		idProperty : 'text.list.id',
		siteidProperty : 'text.list.siteid',
		listNamePrefix : 'text.list.',
		listTypeIdProperty : 'text.list.type.id',
		listTypeLabelProperty : 'text.list.type.label',
		listTypeControllerName : 'Text_List_Type',
		listTypeCondition : { '&&': [ { '==': { 'text.list.type.domain': 'attribute' } } ] },
		listTypeKey : 'text/list/type/attribute'
	},
	listConfig : {
		domain : [ 'text', 'product' ],
		prefix : 'attribute.'
	}
}, 20);
