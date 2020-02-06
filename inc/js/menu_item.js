/**
 * menu as variable
 */
var menu_item = {
	title: Ext.String.format('{0}', 'Menu'),
	region: 'west',
	width: menu_width_west,
	minWidth: menu_minWidth_west,
	maxWidth: menu_maxWidth_west,
	loader: {
		autoLoad: { url: menu_autoload_href, method: 'POST' }
	},
	dockedItems: [
		{
			xtype: 'toolbar',
			items: [
				{
					width: 65,
					iconCls: 'icon-house',
					text: Ext.String.format('{0}', ''),
					handler: function() {
						location.href = 'index.php';
					}
				}
			]
		}
	]
};
