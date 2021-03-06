Module.define(
	"Base.Form",

	function () {
		NS("KrujevaBonus.UI");

		KrujevaBonus.UI.LoginForm = Class(Base.Form, {
			template: "KrujevaBonus/LoginForm",

			fields: {
				login: "Номер телефона",
				password: "Пароль"
			},

			rules: {
				edit: {
					login: ['require'],
					password: ['require']
				}
			},

			afterRender: function (options) {
				this.ParentCall();

				if (options && options.login) {

					this.setValue('login', options.login, true);
				}

				//this.fieldElement('login').mask('+7 (999) 999-99-99', {placeholder: "+7"});
			},

			forgotPasswordClick: function () {
				this.parent.open('forgot');
			},

			validate: function () {
				var valid = this.ParentCall();

				var record = this.getRecord();

				if (!record.login) {

					this.setError('login', 'login');

					valid = false;

				}

				return valid;
			},

			loginClick: function () {

				this.setFieldsValue();

				if (!this.validate('edit')) {

					this.render();
					return;

				} else {

					this.hideErrors();
				}

				var record = this.getRecord();

				var source = new KrujevaBonus.Data.Users();

				//@loader
				var loader = Loader.start(this.$name('form-loader'), {showTimeout: 0});

				source.bonuslogin({item: record}, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						if (data && data.context) {

							application.setServerData(data);

							application.openNextPage();
						}

					}.bind(this));

				}.bind(this));

			},


		});
	});