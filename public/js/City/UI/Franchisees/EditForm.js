Module.define(
	"Base.Form",
	"City.UI.Franchisees.OwnerEditForm",

	function () {
		NS("City.UI.Franchisees");

		City.UI.Franchisees.EditForm = Class(Base.Form, {
			template: "City/Franchisees/EditForm",

			fields: {
				id: 'id',
				name: 'Название франчайзи',
				ownerid: 'ownerid',
				countryid: 'Страна',
				cityid: 'Город',
				cityname: 'cityname',
				countryname: 'countryname',
				address: 'Адрес',
				inn: 'ИНН',
				bik: 'БИК'
			},

			rules: {
				edit: {
				}
			},

			blocks: {
				owner: "City.UI.Franchisees.OwnerEditForm"
			},

			newFile: null,

			isProfile: null,

			render: function (options) {

				if (options && options.item) {
					this.setRecord(options.item);
				}

				if (options && options.isProfile) {
					this.isProfile = true;
				}

				if (!this.owner) {
					this.addBlock('owner', {}, {});
				}

				return this.ParentCall();
			},

			backClick: function () {

				if (this.isProfile) {
					return;
				}

				this.parent.open("list");
			},

			onFileChange: function (input, ev) {
				var inputfile = input;

				if (!inputfile.files.length) {
					return;
				}

				var files = [];
				for (var i = 0, j = inputfile.files.length; i < j; i++) {
					files.push(inputfile.files[i]);
				}

				if (!files.length) {
					return;
				}

				var file = files[0];

				var reader = new FileReader();
				var preview = $('<img>');

				reader.onloadend = function () {
					preview.attr('src', reader.result);

					var $cnt = this.$name('img-photo-path').empty();

					preview.appendTo($cnt);

					this.newFile = inputfile;

				}.bind(this);

				if (file) {
					reader.readAsDataURL(file);
				} else {
					preview.attr('src', '');
				}
			},

			setFieldsValue: function () {
				this.ParentCall();

				var record = this.getRecord();

				if (record.owner) {
					this.setValue('name', record.owner.name, true);
				}
			},

			saveClick: function () {

				if (this.isProfile) {
					return;
				}

				this.setFieldsValue();

				if (!this.validate('edit')) {
					this.render();
					return;
				} else {
					this.hideErrors();
				}

				var record = this.getRecord();

				var method = record.id ? 'update' : 'insert';

				var files = this.newFile ? [{name: 'photo', file: this.newFile}] : [];

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Сохраняем франшизу'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				Xhr.upload({
					url: "/City/Web/Franchisees/"+ method,
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

						Alert.success("Франшиза сохранена");

						this.parent.open("list");

					}.bind(this));

				}.bind(this));

			},


		});
	});