/**
 * Common Functions 
 */

/**
 * Remove the class property, animate
 */
function ExtHideMask()
{
	Ext.fly('center').animate({
		opacity:0,
		remove:true
	});
	return;
}

/**
 * Remove the class=loadlogo property, extjs defer
 */
function hideMask()
{
	Ext.defer(ExtHideMask, 50);
	return;
}

/**
 * Show an Error Messagebox witd a cached exception message
 * @param {string} ex - try catch exception
 */
function trycatchex(ex)
{ 
	Ext.MessageBox.show({title: 'ERROR Exception (trycatchex)', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: 'Exception Error description: ' + ex.toString()});
	return;
}

/**
 * open login window (modal)
 */
function reLogin()
{
	openLoginWindow();
	return;
}

/**
 * Show an Error Messagebox witd returned action.failureType, open loginWindow if action.result = relogin
 * @param {object} form
 * @param {object} action
 */
function failure_section_Form(form, action)
{
	/**
	 * if we have action.result.relogin and it is not undefined 
	 * indexcontroller say {"success": false, "info": "relogin"}
	*/
	if ( typeof action.result.info !== 'undefined' ) {
		if (action.result.info == 'relogin'){
			reLogin();
			return;
		}
	}
	/** https://docs.sencha.com/extjs/6.2.0/classic/Ext.form.action.Load.html#property-failureType  */
	/**
	 * CLIENT_INVALID : String
	 * Failure type returned when client side validation of the Form fails thus aborting a submit action. Client side validation is performed unless clientValidation is explicitly set to false.
	 */
	if (action.failureType === Ext.form.action.Action.CLIENT_INVALID) { 
		Ext.MessageBox.show({title: 'CLIENT_INVALID', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: 'Something has been missed. Please check and try again.'});
		return;
	}
	/**
	 * CONNECT_FAILURE : String
	 * Failure type returned when a communication error happens when attempting to send a request to the remote server. The response may be examined to provide further information.
	 */
	if (action.failureType === Ext.form.action.Action.CONNECT_FAILURE) { 
		Ext.MessageBox.show({title: 'CONNECT_FAILURE', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: action.response.status + ': ' + action.response.statusText});
		return;
	}
	/**
	 * LOAD_FAILURE : String
	 * Failure type returned when the response's success property is set to false, or no field values are returned in the response's data property.
	 */
	if(action.failureType === Ext.form.action.Action.LOAD_FAILURE){ 
		Ext.MessageBox.show({title: 'LOAD_FAILURE', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: action.result.info});
		return;
	}
	/**
	 * SERVER_INVALID : String
	 * Failure type returned when server side processing fails and the result's success property is set to false.
	 * In the case of a form submission, field-specific error messages may be returned in the result's errors property.
	 */
	if (action.failureType === Ext.form.action.Action.SERVER_INVALID) { 
		//extToast(Ext.String.format('{0}', 'SERVER_INVALID'), Ext.String.format('{0}', action.result.info), 'error');
		Ext.MessageBox.show({title: 'SERVER_INVALID', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: action.result.info});
		return;
	}
	/**
	 * if we have action.result but it is undefined
	 */
	if ( typeof action.result.info === 'undefined' ) {
		Ext.MessageBox.show({title: 'STATUS', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: 'action.result.info = undefined'});
		return;
	}
}

function failure_section_Grid(proxy, response, options)
{
	if (response.statusText != 'OK'){ //timeout or Communication failed
		Ext.MessageBox.show({title: 'CONNECT_FAILURE', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: response.status + ': ' + response.statusText});
	}else{
		/**
		 * if we have response.responseText and it is not undefined
		 * indexcontroller say {"success": false, "info": "relogin"}
		 */
		if ( typeof response.responseText !== 'undefined' ) {
			var resp = Ext.decode(response.responseText);
			if ( typeof resp.info !== 'undefined' ) {

				if (resp.info == 'relogin'){
					reLogin();
					return;
				}

				Ext.MessageBox.show({title: 'SERVER_INVALID', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: resp.info});
		
			}else{
				Ext.MessageBox.show({title: 'SERVER_INVALID', cls: 'msgBox', buttons: Ext.Msg.OK, icon : Ext.Msg.ERROR, msg: 'resp.info is undefined'});
			}
		}
	}
	return;
}

/**
 * Show an ExtJs Toast
 * @param {string} title
 * @param {string} text
 * @param {string} msgType
 */
function extToast(title,text,msgType)
{
	var title;
	var text;
	var msgType;

	Ext.toast({autoCloseDelay: 6000, align: 't', bodyPadding:0, border:false, minWidth: 400, style:'padding:0; border-width:0;', slideInDuration: 400,
		html: '<div class="msg-' + msgType + '"><h3>' + title + '</h3><p>' + text + '</p></div>'});    
	return;
}

function noDirty()
{
	extToast(Ext.String.format('{0}', 'Info'), Ext.String.format('{0}', 'Data is not dirty.'), 'info');
	return;
}
function noValid()
{
	extToast(Ext.String.format('{0}', 'Info'), Ext.String.format('{0}', 'Data is not valid'), 'info');
	return;
}
