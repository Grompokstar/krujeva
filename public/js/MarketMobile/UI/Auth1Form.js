Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.FormValidate",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.Auth1Form = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/Auth1Form",

			fields: {
				'login': ['require'],
				'password': ['require']
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function (page, options) {

				if (options && options.login) {

					this.setRecord({login: options.login});
				}
			},

			backClick: function () {
				application.backOpenPage();
			},

			successClick: function () {
				this.parent.open('provider', {animate: true});
			},

			validateField: function (name, val) {
				var result = this.ParentCall();

				switch (name) {
					case 'login':

						var value = val ? val.replace(/( |\(|\)|-|\+)/g, '') : '';

						result = val && Util.isNumber(value) && value.length == 10;

						break;
				}

				return result;
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {
					return;
				}

				application.showLoader(this, this.serverSend);
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Users();

				var record = this.getRecord();

				record['login'] = '+7' + record['login'];

				source.mobilelogin({item: record}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							application.setServerData(data);

							application.cartForm._clearStorageData();

							//@open next page
							application.open('provider', {animate: true});

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

								Alert.error('Не удалось войти');
							}

						}

					});

				});
			},

			forgotClick: function () {
				var record = this.getRecord();

				application.open('forgot', {animate: true, login: record.login});
			}
		});
	}
);