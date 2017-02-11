Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.DealerRegions",
	"KrujevaDict.UI.Dealers.DealerUser",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.EditForm = Class(Base.Form, {
			template: "KrujevaDict/Dealers/EditForm",

			//@ базовые поля
			fields : {
				id: 'id',
				name: 'Название организации',
				areaid: 'Регион (юр. адрес)',
				cityid: 'Населенный пункт (юр. адрес)',
				address: 'Юридический адрес организации (улица, дом)',
				phone: 'Телефон организации',
				director: 'ФИО генерального директора',
				inn: 'ИНН',
				kpp: 'КПП',
				checkaccount: 'Расчетный счет',
				bankname: 'Название банка',
				koraccount: 'Кор. счет',
				bik: 'БИК',
				okpo: 'ОКПО',
				factaddress: 'Фактический адрес',
				ogrn: 'ОГРН',

				login: 'Мобильный телефон',
			},

			blocks: {
				dealerregions: "KrujevaDict.UI.Dealers.DealerRegions",
				user: "KrujevaDict.UI.Dealers.DealerUser"
			},

			rules: {
				edit: {
					name: ['require'],
					cityid: ['require']
				}
			},

			render: function () {

				if (this.getArgumentString() && !this.isAppended()) {

					var id = this.getArgumentString();

					this.getProductPack(id);

				} else if (!this.isAppended()) {

					this.addBlock('user', (this.user ? this.user.getRecord() : {}), {});
				}

				this.ParentCall();

				return this;
			},

			afterRender: function (options) {
				this.ParentCall();

				if (options && options.item) {

					this.getProductPack(options.item['id']);
				}
			},

			getProductPack: function (id) {

				var loader = Loader.start(this.$name('form-loader'));

				var source = new KrujevaDict.Data.Dealers();

				var self = this;

				source.pack({id: id}, function (result) {

					self.setRecord(result, false);

					self.render();

					self.setArgumentString(id);

					self.addBlock('user', (self.user ? self.user.getRecord() : {}), {}, {render: true});
				});

			},

			onChangeArea: function () {
				this.setFieldsValue();

				//@refresh cityid fields
				this.setValue('cityid', null, true);

				this.getChoosenSelect('cityid').chosenSelect();
			},

			addRegionClick: function () {
				this.addBlock('dealerregions', {}, [], {render: true});
			},

			validate: function () {
				var valid = this.ParentCall();

				if (!this.dealerregions) {
					valid = false;
					this.setError('dealerregions','Укажите регион');
				}

				return valid;
			},

			saveClick: function () {
				this.setFieldsValue();

				if (!this.validate('edit')) {

					this.render();

					return;

				} else {

					this.hideErrors();
				}

				var source = new KrujevaDict.Data.Dealers();

				var record = this.getRecord();

				var method = (record.id) ? 'update' : 'insert';

				var loader = Loader.start(this.$name('form-loader'));

				var self = this;

				source[method](record, function (result) {

					Loader.end(loader, function () {

						if (result && result.item) {

							Alert.success('Успешно сохранено');

							self.parent.open('list');

						} else {

							Alert.error('Не удалось сохранить');
						}

					});

				});
			},

			removeClick: function () {

				if (!confirm('Вы действительно хотите удалить дилера?')) {

					return;
				}

				var source = new KrujevaDict.Data.Dealers();

				var record = this.getRecord();

				var loader = Loader.start(this.$name('form-loader'));

				var self = this;

				source.remove({id: record['id']}, function (result) {

					Loader.end(loader, function () {

						if (result && result.item) {

							Alert.success('Успешно удалено');

							self.parent.open('list');

						} else {

							Alert.error('Не удалось удалить');
						}

					});

				});
			},

			cancelClick: function () {
				this.parent.open('list');
			}

		});
	});