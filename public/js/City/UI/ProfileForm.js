Module.define(
	"Base.Form",
	"City.UI.AnimateForm",

	function () {
		NS("City.UI");

		City.UI.ProfileForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/ProfileForm",

			form: null,
			form2: null,
			item: {},

			destroy: function () {

				if (this.form) {
					this.form.destroy();
					this.form = null;
				}

				if (this.form2) {
					this.form2.destroy();
					this.form2 = null;
				}

				this.item = {};

				this.ParentCall();
			},

			render: function (options) {
				this.ParentCall();

				if (!options || !options.item) {

					this.getProfile();

				} else if (options && options.item) {

					this.form = new City.UI.Franchisees.ViewForm();

					this.form.render({item: options.item, isProfile: true});

					this.$element.empty();

					this.form.$element.appendTo(this.$element);
				}

				return this;
			},

			getProfile: function () {

				var source = new City.Data.Users();

				source.profile({}, function (res) {

					if (res) {

						this.item = res;

						this.render({item: res});
					}

				}.bind(this));

			},

			backClick: function () {

				if (this.form) {
					this.form.destroy();
					this.form = null;
				}

				this.form = new City.UI.Franchisees.ViewForm();

				this.form.render({item: this.item, isProfile: true});

				this.$element.empty();

				this.form.$element.appendTo(this.$element);
			},

			editClick: function () {

				if (this.form) {
					this.form.destroy();
					this.form = null;
				}

				this.form = new City.UI.Franchisees.EditForm();

				this.form.render({item: this.item, isProfile: true});

				this.$element.empty();

				this.form.$element.appendTo(this.$element);
			},

			saveClick: function (caller, ev) {
				this.setFieldsValue();

				if (!this.form.validate('edit')) {
					this.form.render();
					return;
				} else {
					this.form.hideErrors();
				}

				var record = this.form.getRecord();

				var files = this.form.newFile ? [
					{name: 'photo', file: this.form.newFile}
				] : [];

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Сохраняем франшизу'
				});

				this.form.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.form.$name('main-loader'));

				Xhr.upload({
					url: "/City/Web/Franchisees/profileupdate",
					files: files,
					data: {item: JSON.stringify(record)}
				}, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						if (data && data.data) {
							application.security.init(data.data);

							application.emit('Context.Update');
						}

						Alert.success("Профиль сохранен");

						application.destroyPage('profile');
						application.open('profile');

					}.bind(this));

				}.bind(this));
			}
		});
	});