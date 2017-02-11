Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.LoginForm = Class(Base.Form, {
			template: "KrujevaDict/LoginForm",

			fields: {
				login: "Логин",
				password: "Пароль"
			},

			rules: {
				edit: {
					login: ['require'],
					password: ['require']
				}
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

				var source = new KrujevaDict.Data.Users();

				//@loader
				var loader = Loader.customHtml("KrujevaDict/Loader", true, {name: 'main-loader'}, {
					description: 'Пытаемся войти'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				source.login({item: record}, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						if (data && data.user) {

							application.setServerData({context: data});

							application.openNextPage();
						}

					}.bind(this));

				}.bind(this));

			},

			refreshClick: function () {
				this.passwordClick();
			},

			getHintText: function () {

				if (this.getValue('step') != 2) {
					return '';
				}

				if (!this.hintSeconds || this.hintSeconds < 0) {
					return '<button class="transparent" on-click="refreshClick">Отправить пароль снова</button>'
				}

				return 'Отправить пароль повторно, можно будет через '+ this.hintSeconds +' сек.';
			},

			updatePasswordHint: function () {

				this.hintSeconds--;

				if (this.hintSeconds < 0) {
					this.endHint();
				}

				this.$name('password-hint').html(this.getHintText());
			},

			endHint: function () {
				if (this.intervalHint) {
					clearInterval(this.intervalHint);
					this.intervalHint = null;
				}
			},

			startHint: function () {
				this.endHint();

				this.hintSeconds = 30;

				this.updatePasswordHint();

				this.intervalHint = setInterval(this.updatePasswordHint.bind(this), 1000);
			},
		});
	});