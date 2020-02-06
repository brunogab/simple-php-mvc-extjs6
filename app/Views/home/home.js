/**
 * OnReady
 *
 * =====  container.Viewport =====
 * https://docs.sencha.com/extjs/6.2.0/guides/core_concepts/components.html
 */
Ext.onReady(function() {
	Ext.tip.QuickTipManager.init();

	Ext.create('Ext.container.Viewport', {
		renderTo: Ext.getBody(),
		layout: 'border',
		border: false,

		defaults: {
			collapsible: true,
			split: true,
			bodyPadding: 0,
			border: false,
			layout: 'fit'
		},
		items: [
			menu_item /** from \inc\js\menu_item.js */,
			{
				title: Ext.String.format('{0}', 'Home'),
				region: 'center',
				collapsible: false,
				loader: {
					autoLoad: { url: home_autoload_href, method: 'POST' } // { url: home_autoload_href, method: 'POST' , params: {paramname:'paramvalue'} }
				},
				dockedItems: [
					{
						xtype: 'toolbar',
						items: [
							{
								text: Ext.String.format('{0}', 'some'),
								iconCls: 'icon-pdf',
								handler: function() {
									location.href = 'index.php?class=pdf&docname=test.pdf';
								}
							},
							'-',
							{
								xtype: 'button',
								iconCls: 'icon-down',
								text: Ext.String.format('{0}', 'repeatly'),
								menu: {
									border: false,
									items: [
										{
											text: Ext.String.format('{0}', 'needed'),
											iconCls: 'icon-excel',
											handler: function() {
												location.href = 'index.php?class=doc&docname=test.xlsx';
												/** if public we can use: */
												//location.href = 'inc/downloads/test.xlsx';
											}
										},
										{
											text: Ext.String.format('{0}', 'documents (with prompt)'),
											iconCls: 'icon-pdf',
											handler: function() {
												Ext.MessageBox.prompt(
													'Enter',
													'type some text:',
													function(btn, text) {
														if (btn == 'ok') {
															if (text == '') {
																Ext.MessageBox.show({
																	title: Ext.String.format('{0}', 'text_error'),
																	msg: Ext.String.format(
																		'{0}',
																		'it was empty!'
																	),
																	buttons: Ext.Msg.OK,
																	icon: Ext.MessageBox.ERROR
																});
															} else {
																location.href = ''; //'href' + text;
															}
														}
													}
												);
											}
										},
										{
											text: Ext.String.format('{0}', 'for everyone'),
											iconCls: 'icon-pdf',
											handler: function() {
												location.href = '';
											}
										}
									]
								}
							},
							'->',
							{
								text: Ext.String.format('{0}', 'right align button'),
								iconCls: 'icon-pdf',
								handler: function() {
									location.href = '';
								}
							}
						]
					}
				]
			},
			{
				title: '',
				region: 'east',
				width: '40%',
				collapsed: true,
				layout: 'border',
				defaults: {
					collapsible: false,
					split: true,
					bodyPadding: 0,
					border: false
				},
				items: [
					{
						region: 'north',
						layout: 'fit',
						height: '50%',
						html: 'north - chart'
						//items		: chart_rollout
					},
					{
						region: 'center',
						layout: 'fit',
						html: 'center - chart'
						//items		: chart_rollback
					}
				]
			}
		]
	});

	hideMask();
});
