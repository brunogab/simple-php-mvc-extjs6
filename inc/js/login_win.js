/**
 * Login Form check and create and open 
 */
function openLoginWindow(){

	/**
	 * form submit/post
	 */
	function postLoginForm(){
		login_Form.getForm().submit({
			url: 'index.php?class=login&function=postLoginForm',
			waitMsg: Ext.String.format('{0}', 'sending..'),
			method:'POST',
			success: function(form, action) { //success = true
				try {
					login_win.close();
					if (action.result.info=='reload'){ 
						window.location.reload();
					}
				}
				catch (ex) {trycatchex(ex);}
			},
			failure: function (form, action) {
				/**
				 * https://docs.sencha.com/extjs/6.2.0/classic/Ext.form.action.Load.html#property-failureType 
				 * only relogin or CLIENT_INVALID or CONNECT_FAILURE or SERVER_INVALID(post) or LOAD_FAILURE(false or nodata) come with success = false 
				*/
				try {
					failure_section_Form(form, action);
				}
				catch (ex) {trycatchex(ex);}
			}
		});
	}
		
	Ext.tip.QuickTipManager.init();

	var login_win = Ext.getCmp('login_win');
	if(!login_win){
			
		/** create form into a variable */
		var login_Form =  Ext.create('Ext.form.Panel', {
			listeners : {
				afterRender : function(){
					/** after render and delay */
					Ext.defer(
						function () {
							if (Ext.getCmp('ucode').getValue() != '') {
								Ext.getCmp('pwd').focus(false, 50);
							}else{
								Ext.getCmp('ucode').focus(false, 50);
							}	
						}	
					, 50);
				}
			},
			frame: false,
			border: false,
			bodyPadding: 10,

			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100,
			},
			items: [{
				xtype: 'textfield',
				id: 'ucode',
				name: 'ucode',
				fieldLabel: Ext.String.format('{0}*', 'Username'),
				allowBlank: false,
				anchor: '100%',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							if (this.getValue() != ''){
								Ext.getCmp('pwd').focus();
							}	
						}
					}
				}
			}, {
				xtype: 'textfield',
				id: 'pwd',
				name: 'pwd',
				fieldLabel: Ext.String.format('{0}*', 'Password'),
				allowBlank: false,
				inputType: 'password',
				padding: '10 0 0 0',
				anchor: '100%',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							if (this.getValue() != ''){
								if (Ext.getCmp('ucode').getValue() != ''){
									postLoginForm(); 
								}else{
									Ext.getCmp('ucode').focus();
								}
							}
						}
					}
				}
			}],

			buttonAlign: 'center',
			buttons: [{
				text: Ext.String.format('{0}', 'Login'),
				formBind: true,
				handler: function(){
					postLoginForm();
				}
			}]

		});
			
		/** create window into a variable */
		login_win = Ext.create('Ext.window.Window', { 
			id:'login_win',
			title: Ext.String.format('{0}', 'Login'),
			style: {borderWidth:'0px'},
			closable: false,
			resizable:false,
			border: false,
			modal:true,
			width: 400,
			items: [login_Form]
		}).show();
					
	}		
		
} //eof function
