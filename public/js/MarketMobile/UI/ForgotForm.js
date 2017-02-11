Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.FormValidate",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.ForgotForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/ForgotForm",

			fields: {
				'login': ['require']
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
				application.showLoader(this, this.serverSend, {
					text: 'Отправляем смс...',
				});
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Users();

				var record = this.getRecord();

				var recordSend = this.getRecord();

				recordSend['login'] = '+7' + recordSend['login'];

				source.forgotpassword({item: recordSend}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							if (data.password) {

								Alert.success(data.password);

							} else {

								Alert.success('Смс с паролем отправлено');

							}

							//@open next page
							application.open('auth1', {animate: true, login: record.login});

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

								Alert.error('Не удалось отправить смс');
							}

						}

					});

				});
			},

			forgotClick: function () {
				application.open('forgot', {animate: true});
			}
		});
	}
);