/**
 * load user data form
 */
function userprofilLoadData() {
	userprofil_Form.getForm().load({
		waitMsg: Ext.String.format('{0}', 'sending..'),
		method: 'POST',
		url: 'index.php?class=userprofile&function=LISTING',
		success: function(form, action) {
			/**server responded with success = true */
		},
		failure: function(form, action) {
			/**
			 * https://docs.sencha.com/extjs/6.2.0/classic/Ext.form.action.Load.html#property-failureType
			 * only relogin or CLIENT_INVALID or CONNECT_FAILURE or SERVER_INVALID(post) or LOAD_FAILURE(false or nodata) come with success = false
			 */
			try {
				failure_section_Form(form, action);
			} catch (ex) {
				trycatchex(ex);
			}
		}
	});
}

/**
 * save user data form
 */
function userprofilSaveData() {
	if (!userprofil_Form.isDirty()) {
		noDirty();
		return;
	}

	if (!userprofil_Form.isValid()) {
		noValid();
		return;
	}

	userprofil_Form.getForm().submit({
		waitMsg: Ext.String.format('{0}', 'Save'),
		method: 'POST',
		url: 'index.php?class=userprofile&function=SAVE',
		clientValidation: true,
		success: function(form, action) {
			//success = true
			try {
				userprofilLoadData();
				if (action.result.info != '') {
					extToast(
						Ext.String.format('{0}', 'Success'),
						Ext.String.format('{0}', action.result.info),
						'success'
					);
				}
				extToast(
					Ext.String.format('{0}', 'Success'),
					Ext.String.format('{0}', 'Data has been saved.'),
					'success'
				);
			} catch (ex) {
				trycatchex(ex);
			}
		},
		failure: function(form, action) {
			/**
			 * https://docs.sencha.com/extjs/6.2.0/classic/Ext.form.action.Load.html#property-failureType
			 * only relogin or CLIENT_INVALID or CONNECT_FAILURE or SERVER_INVALID(post) or LOAD_FAILURE(false or nodata) come with success = false
			 */
			try {
				failure_section_Form(form, action);
			} catch (ex) {
				trycatchex(ex);
			}
		}
	});
}
