Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDealer.UI");

		KrujevaDealer.UI.ForgotForm = Class(Base.Form, {
			template: "KrujevaDealer/ForgotForm",

			fields: {
				login: "Номер телефона",
			},

			rules: {
				edit: {
					login: ['require'],
				}
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('login').mask('+7 (999) 999-99-99', {placeholder: "+7"});
			},

			validate: function () {
				this.ParentCall();

				var record = this.getRecord();

				if (!record.login) {
					this.setError('login', 'login');
					return false;
				}

				var phone = record.login.replace(/\D+/g, "");

				if (phone.length == 11) {
					return true;
				}

				this.setError('login', 'login');

				return false;
			},

			sendClick: function () {

				this.setFieldsValue();

				if (!this.validate('edit')) {
					this.render();
					return;
				} else {
					this.hideErrors();
				}

				var record = this.getRecord();

				var source = new KrujevaDealer.Data.Users();

				//@loader
				var loader = Loader.start(this.$name('form-loader'));

				source.forgotpassword({item: record}, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						if (data.password) {

							Alert.success(data.password);

						} else if (data.ok) {

							Alert.success('Пароль отправлен по смс');
						}

						this.parent.open('login', {login: record.login});

					}.bind(this));

				}.bind(this));
			},
		});
	});