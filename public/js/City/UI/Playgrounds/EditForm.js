Module.define(
	"Base.Form",
	"City.UI.Playgrounds.PhonesEditForm",

	function () {
		NS("City.UI.Playgrounds");

		City.UI.Playgrounds.EditForm = Class(Base.Form, {
			template: "City/Playgrounds/EditForm",

			fields: {
				id: 'id',
				name: 'Название места размещения',
				cityid: 'Город',
				countryid: 'Страна',
				franchiseid: 'Владелец франшизы',
				address: 'Адрес',
				phone: 'Телефон',
				fromtime: 'Время открытия',
				totime: 'Время закрытия',
				vestcount: 'Количество жилеток на площадке',

				parthourchildrenprice: '30 минут',
				onehourchildrenprice: '1 час',
				twohourchildrenprice: '2 часа',

				pathtimeenteramount: 'За выход - пол смены',
				fulltimeenteramount: 'За выход - вся смена смены',
				fulltimepersents: 'fulltimepersents',
				pathtimepersents: 'pathtimepersents',

				franchisename: 'Владелец франшизы',
				countryname: 'countryname',
				cityname: 'cityname'
			},

			blocks: {
				phones: "City.UI.Playgrounds.PhonesEditForm",
			},

			rules: {
				edit: {
					name: ['require'],
					cityid: ['require'],
					franchiseid: ['require'],
					fromtime: ['require'],
					totime: ['require'],
					parthourchildrenprice: ['require'],
					onehourchildrenprice: ['require'],
					twohourchildrenprice: ['require'],
					vestcount: ['require'],
					pathtimeenteramount: ['require'],
					fulltimeenteramount: ['require'],
				}
			},

			render: function (options) {

				if (options && options.item) {
					this.setRecord(options.item);
				}

				if (!this.phones) {
					this.addBlock('phones', {}, []);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				this.fieldElement('phone').mask('+7 (999) 999-99-99');
				this.fieldElement('fromtime').timepicker();
				this.fieldElement('totime').timepicker();
			},

			addPhoneClick: function () {
				this.addBlock('phones', {}, [], {render: true});
			},

			backClick: function () {
				this.parent.open("list");
			},

			setFieldsValue: function () {

				//@fulltimepersents
				var fulltimepersents = [];

				this.$name('full-persent-amount').find('.path-pl-item').each(function () {

					var amount = $(this).find('[name=amount]').val();
					var persent = $(this).find('[name=persent]').val();

					if (amount && persent) {
						fulltimepersents.push({amount: amount, persent: persent});
					}
				});

				//@pathtimepersents
				var pathtimepersents = [];

				this.$name('path-persent-amount').find('.path-pl-item').each(function () {

					var amount = $(this).find('[name=amount]').val();
					var persent = $(this).find('[name=persent]').val();

					if (amount && persent) {
						pathtimepersents.push({amount: amount, persent: persent});
					}
				});

				this.setValue('fulltimepersents', fulltimepersents, true);
				this.setValue('pathtimepersents', pathtimepersents, true);
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

				var source = new City.Data.Playgrounds();

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Сохраняем площадку'
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

						Alert.success("Площадка сохранена");

						this.parent.open("list");

					}.bind(this));

				}.bind(this));

			},

			addConditionPath: function () {
				var html = this.renderPathItem();
				this.$name('path-persent-amount').append(html);
			},

			addConditionFull: function () {
				var html = this.renderPathItem();
				this.$name('full-persent-amount').append(html);
			},

			closePathClick: function (caller) {
				$(caller).parent().parent().remove();
			},

			renderPathItem: function (data) {
				return UI.Template.render("City/Playgrounds/PathItem", {data: data});

			}

		});
	});