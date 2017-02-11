Module.define(
	"Base.Form",

	function () {
		NS("City.UI");

		City.UI.LoginForm = Class(Base.Form, {
			template: "City/LoginForm",

			fields: {
				login: "login",
				password: "password",
				step: "step"
			},

			rules: {
				password: {
					login: ['require']
				},

				edit: {
					login: ['require'],
					password: ['require']
				}
			},

			hide: function (options, callback) {
				var parentMethod = this.ParentMethod();

				if (options && options.animate) {

					//@container
					this.$element.transition({
						opacity: 0,
						scale: 0.4
					}, 250, function () {

						this.CallMethod(parentMethod, options);

						if (Util.isFunction(callback)) {
							callback();
						}

					}.bind(this));


				} else {

					this.CallMethod(parentMethod, options);

					if (Util.isFunction(callback)) {
						callback();
					}

				}
			},

			intervalHint: null,
			hintSeconds: null,

			render: function () {

				if (!this.getValue('step')) {
					this.setValue('step', 1);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('login').mask('+7 (999) 999-99-99', {placeholder: "XXXXXXXXXX"});
			},

			passwordClick: function () {
				this.setFieldsValue();

				if (!this.validate('password')) {
					this.render();
					return;
				} else {
					this.hideErrors();
				}

				var record = this.getRecord();

				var source = new City.Data.Users();

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Отправляем пароль'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				source.password({item: record}, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						if (data && data.status) {

							this.fieldElement('password').removeAttr('disabled');

							this.$name('password-block').hide();

							this.$name('enter-block').show();

							this.setValue('step', 2, true);

							this.startHint();

							if (data.password) {
								Alert.success(data.password);
							}
						}

					}.bind(this));

				}.bind(this));
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

				var source = new City.Data.Users();

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
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

						if (data && data.status) {

							LocalStorage.set('sid', data.data.sid);

							application.setServerData(data.data);

							application.openNextPage({animate: true});
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