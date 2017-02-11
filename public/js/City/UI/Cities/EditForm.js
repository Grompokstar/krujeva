Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Cities");

		City.UI.Cities.EditForm = Class(Base.Form, {
			template: "City/Cities/EditForm",

			fields: {
				id: 'id',
				name: 'Название города',
				timezone: 'Часовой пояс',
				countryid: 'Страна',
				countryname: 'countryname',
			},

			rules: {
				edit: {
					name: ['require'],
					timezone: ['require'],
					countryid: ['require']
				}
			},

			render: function (options) {

				if (options && options.item) {
					this.setRecord(options.item);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				if (!this.getValue('timezone')) {
					this.setValue('timezone', '+3', true);
				}
			},

			backClick: function () {
				this.parent.open("list");
			},

			saveClick: function () {
				this.setFieldsValue();

				if (!this.validate('edit')) {
					this.render();
					return;
				} else {
					this.hideErrors();
				}

				var record = this.getRecord();

				var source = new City.Data.Cities();

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Сохраняем город'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				var method = record.id ? 'update' : 'insert';

				source[method](record, function (data, result) {

					Loader.end(loader, function () {

						if (!data) {

							if (result && result.message) {
								Alert.error(result.message);
							}

							return;
						}

						Alert.success("Город сохранен");

						this.parent.open("list");

					}.bind(this));

				}.bind(this));

			},

			getTimezoneOptions: function () {
				var options = [];

				for (var i = -14; i <= 0; i++) {
					options.push({label: i, value: i});
				}

				for (i = 1; i <= 11; i++) {
					options.push({label: '+' + i, value: '+' + i});
				}

				return options;
			}

		});
	});