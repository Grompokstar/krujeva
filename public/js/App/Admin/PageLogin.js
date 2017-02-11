Module.define(

	function () {
		NS("App.Admin");

		App.Admin.PageLogin = Class(App.BaseForm, {

			initialize: function () {
				this.$container = $('body');
				this.initInteractionEvents();
			},

			enterClick: function () {

				var login = this.$name('login').val();
				var password = this.$name('password').val();

				Xhr.call('/Admin/login', {login:login, password: password}, function (res) {

					if (res.success) {

						window.location = '/admin';

					} else {
						Alert.error(res.message);
					}

				});
			}


		});
	});

