Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.FormValidate",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.Auth4Form = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/Auth4Form",

			fields: {
				'phone': ['require'],
				'city': ['require'],
				'address': ['require']
			},

			afterRender: function () {
				this.listenScroll();

				if (application.registrationData) {

					this.setRecord(application.registrationData);
				}
			},

			afterOpen: function (page, options) {

				if (application.registrationData) {

					var setData = Util.clone(application.registrationData);

					if (setData['phone']) {

						setData['phone'] = setData['phone'].replace('+7', '');
					}

					this.setRecord(setData);

					this.hideError('phone');
					this.hideError('city');
					this.hideError('address');
				}

			},

			backClick: function () {
				application.backOpenPage();
			},

			areasClick: function () {
				application.open('areas', {animate: true});
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {

					return;
				}

				record['phone'] = '+7' + record['phone'];

				application.registrationData = Util.merge(application.registrationData, record, true);

				application.showLoader(this, this.serverSend);
			},

			validateField: function (name, val) {
				var result = this.ParentCall();

				switch (name) {
					case 'phone':

						var value = val ?  val.replace(/( |\(|\)|-|\+)/g, '') : '';

						result = val && Util.isNumber(value) && value.length == 10;

						break;
				}

				return result;
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Users();

				source.registration({item: application.registrationData}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data && data.userid) {

							application.registrationData = Util.merge(application.registrationData, {id: data.userid}, true);

							if (data.password) {

								Alert.success(data.password);
							}

							//@open next page
							application.open('passwordverify', {animate: true});

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

								Alert.error('Не удалось сохранить');
							}

						}

					});

				});

			},

		});
	}
);