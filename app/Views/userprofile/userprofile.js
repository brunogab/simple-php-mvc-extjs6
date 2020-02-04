	/**
	 * form
	 */
	var userprofil_Form = Ext.create('Ext.form.Panel', {
		listeners : {
			afterRender : function(){
				userprofilLoadData();
			}
		},
		title: Ext.String.format('{0}', 'Userprofile'),
		trackResetOnLoad: true,
		frame: false,
		border: false,
		bodyPadding: 10,
		defaultType: 'textfield',
		defaults: {
			width: 450
		},
		items: [{
			name		: 'id',
			hidden		: true
		},{
			allowBlank	: false,
			fieldLabel	: Ext.String.format('{0}', 'Code'),
			name		: 'ucode',
			readOnly	: true,
			fieldStyle	: 'opacity: 0.4;'
		},{
			allowBlank	: false,
			fieldLabel	: Ext.String.format('{0}', 'Username'),
			name		: 'uname'
		},{
			allowBlank	: false,
			fieldLabel	: Ext.String.format('{0}', 'Email'),
			name		: 'email'
		},{
			allowBlank	: true,
			fieldLabel	: Ext.String.format('{0}', 'Password'),
			name		: 'pwd',
			inputType	: 'password'
		},{
			xtype		: 'fieldcontainer',
			fieldLabel	: Ext.String.format('{0}', 'Create'),

			layout		: 'hbox',
			defaultType	: 'textfield',	
			items: [{
				flex		: 2,
				name		: 'createdate',
				readOnly	: true,
				fieldStyle	: 'opacity: 0.4;'
			},{
				flex		: 3,
				name		: 'createucode',
				readOnly	: true,
				fieldStyle	: 'opacity: 0.4;',
				margin		: '0 0 0 5'
			}]

		},{
			xtype		: 'fieldcontainer',
			fieldLabel	: Ext.String.format('{0}', 'Update'),

			layout		: 'hbox',
			defaultType	: 'textfield',
			items: [{
				flex		: 2,
				name		: 'updatedate',
				readOnly	: true,
				fieldStyle	: 'opacity: 0.4;'
			},{
				flex		: 3,
				name		: 'updateucode',
				readOnly	: true,
				fieldStyle	: 'opacity: 0.4;',
				margin		: '0 0 0 5'
			}]
		}],
		dockedItems: [{
			xtype: 'toolbar',
			items: [{
				text: '',
				iconCls: 'icon-reload',
				handler: function(){
					userprofilLoadData();
				}
			}, '-', {
				text: Ext.String.format('{0}', 'Save'),
				iconCls: 'icon-save',
				formBind: true,
				handler: function(){
					userprofilSaveData();
				}
			}]
		}] //docked
	});


	/*
	* simple grid
	*/
	Ext.define('uprv_Model', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'id', 			type: 'int',},
			{name: 'ucode',			type: 'string'},
			{name: 'pname',			type: 'string'},
			{name: 'pvalue',		type: 'string'}
		],
		proxy: {
			type: 'ajax',
			actionMethods: {
				read: 'POST'
			},
			api: {
				read:		'index.php?class=userprofile&function=ListingUserPrivileges',
				create: 	'',
				update:		'',
				destroy: 	''
			},
			reader: {
				type: 'json',totalProperty: 'total',successProperty: 'success',messageProperty: 'message',root: 'results'
			},
			writer: {
				type: 'json',writeAllFields: true,allowSingle: false,root: 'data'
			},
			afterRequest : function(request, success) {
					try {
						if (request._action == 'read') {
							if ((uprv_Store.getCount() < 1)&&(Ext.getCmp('uprv_searchfield').getValue()!='')) {
								extToast(Ext.String.format('{0}', text_title_warning), Ext.String.format('{0}', text_search_empty), 'warning');
							}
						}
						if ( (request._operation.success) && (request._action == 'destroy') ) {
						}
					}
					catch (ex) {trycatchex(ex);}
			},
			listeners: { //ERROR or http failure or relogin or nopriv come with success = false
				exception: function(proxy, response, options) {
						try {
							failure_section_Grid(proxy, response, options);
						}
						catch (ex) {trycatchex(ex);}
				}
			}
		}
	});
	uprv_Store = new Ext.data.Store({
		autoLoad: true,autoSync: true,type: 'json',model: 'uprv_Model',pageSize: 100,remoteSort: true,
		sorters: {
			property: 'pname',direction: 'ASC'
		}
	});
	var uprv_Col = [
		{xtype: 'rownumberer',text:'Pos.',width: 40,sortable: false,locked: false},
		{ dataIndex: 'id', text: '#', width: 45 },
		{ dataIndex: 'pname', header: Ext.String.format('{0}', 'Name'), width: 150 },
		{ dataIndex: 'pvalue', header: Ext.String.format('{0}', 'Value'), width: 150 }
	];
	var uprv_Table = Ext.create('Ext.grid.Panel', {
		listeners : {
			afterRender : function(){
			}
		},
		collapsible: false,multiSelect: true,trackMouseOver: false,frame: false,border: false,
		viewConfig: {
			stripeRows: true,enableTextSelection: true
		},
		selType: 'checkboxmodel',

		columns: uprv_Col,
		store: uprv_Store,

		bbar: new Ext.create('Ext.toolbar.Paging',{
			store: uprv_Store,
			displayInfo: true,
		}),
		dockedItems: [{
			xtype: 'toolbar',
			items: [{
				text: '',
				iconCls: 'icon-reload',
				handler: function(){
					uprv_Store.reload();
				}
			}, '-', {
				id: 'uprv_searchfield',
				width: 350,
				fieldLabel: Ext.String.format('{0}', 'Search'),
				labelWidth: 50,
				xtype: 'searchfield',
				store: uprv_Store
			}]
		}] //docked
	});


	var userprofil_ = {
		layout: 'border',
		frame: false,
		border: false,
		defaults: {
			collapsible	: false,
			split		: true,
			border		: false,
			layout		: 'fit'
		},
		items: [{
			region		: 'north',
			height		: 265,
			minHeight	: 100,
			items		: userprofil_Form
		},{
			region		: 'center',
			items		: Ext.create('Ext.tab.Panel', {
				frame : false,
				border : false,
				defaults : {
					autoScroll: true,
					layout	: 'fit',
					border	: false
				},
				items: [{
					title	: Ext.String.format('{0}', 'Privileges'),
					items	: [uprv_Table]
				}]
			})
		}]
	};

/**
 * OnReady
 *
 * =====  container.Viewport =====
 */
Ext.onReady(function() {

	Ext.tip.QuickTipManager.init();

	Ext.create('Ext.container.Viewport', {
		renderTo: Ext.getBody(),
		layout	: 'border',
		border	: false,
	   
		defaults: {
			collapsible	: true,
			split		: true,
			bodyPadding	: 0,
			border		: false,
			layout		: 'fit'
		},
		items: [
			menu_item /** from \inc\js\menu_item.js */
		,{
			region	:'center',
			collapsible	: false,
			items	: [userprofil_]
		},{
			title		: '',
			region		: 'east',
			width		: menu_width_east,
			minWidth	: menu_minWidth_east,
			maxWidth	: menu_maxWidth_east,
			collapsed	: true,
			defaults: {
				collapsible	: false,
				split		: true,
				bodyPadding	: 0,
				border		: false
			},
			items	: [ { html: ['<pre>east empty</pre>'].join() }]
		}]
	});

	hideMask();

 });
