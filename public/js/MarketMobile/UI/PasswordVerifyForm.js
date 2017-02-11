Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.PasswordVerifyForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, KrujevaMobile.FormValidate, {
			template: "KrujevaMobile/PasswordVerifyForm",

			fields: {
				'password': ['require']
			},

			initialize: function () {
				this.ParentCall();

				var self = this;

				this.onResizeHandler = function () {
					self.onWindowResize();
				};

				$(window).on('resize', this.onResizeHandler);
			},

			destroy: function () {
				$(window).off('resize', this.onResizeHandler);

				this.ParentCall();
			},

			afterRender: function () {
				this.listenScroll();
			},

			render: function () {
				this.ParentCall();

				this.onWindowResize();

				return this;
			},

			onWindowResize: function () {
				var h = application.mainHeight * 0.4;

				//@set Height
				this.$name('password-v-img-bg').attr('style', 'height: ' + h + 'px');

				this.refreshIscroll();
			},

			backClick: function () {
				application.backOpenPage();
			},

			nextClick: function () {
				var record = this.getRecord();

				if (!this.validate()) {
					return;
				}

				application.registrationData = Util.merge(application.registrationData, record, true);

				application.showLoader(this, this.serverSend, {
					bgcolor: 'rgba(69, 131, 177, .94)',
					textcolor: '#fff',
					text: 'Проверяем пароль...'
				});
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Users();

				var self = this;

				source.passwordverify({item: Util.clone(application.registrationData)}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							application.setServerData(data);

							//@open next page
							application.open('successreg', {animate: true});

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

								Alert.error('Не удалось проверить');
							}

						}

					});

				});

			},

		});
	}
);