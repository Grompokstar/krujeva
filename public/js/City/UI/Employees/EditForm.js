Module.define(
	"Base.Form",
	"City.UI.Employees.EmployeeAvailablePlaygrounds",

	function () {
		NS("City.UI.Employees");

		City.UI.Employees.EditForm = Class(Base.Form, {
			template: "City/Employees/EditForm",

			fields: {
				id: 'id',
				name: 'Имя',
				surname: 'Фамилия',
				patronymic: 'Отчество',
				bithdate: 'День рождения',
				cityid: 'Город - где работает сотрудник',
				phone: 'Телефон',

				photoname: 'photoname',
				relativepath: 'relativepath',

				radiorole: "radiorole",
				userid: 'userid',
				employeeavailableplaygroundsselect: 'Площадки (где работает или администрирует)'
			},

			blocks: {
				employeeavailableplaygrounds: "City.UI.Employees.EmployeeAvailablePlaygrounds"
			},

			rules: {
				edit: {
					surname: ['require'],
					name: ['require'],
				}
			},

			newFile: null,

			render: function (options) {

				if (options && options.item) {
					this.setRecord(options.item);
					this.setValue('radiorole', options.item.roleid, true);
				}

				if (!this.getValue('radiorole')) {
					this.setValue('radiorole', City.Roles.Nanny, true);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				var days = 100 * 365 * -1;

				this.fieldElement('bithdate').datepicker({
					changeMonth: true,
					changeYear: true,
					minDate: days,
					maxDate: "+0D",
					defaultDate: '25.06.1990'
				});

				this.fieldElement('phone').mask('+7 (999) 999-99-99');
			},

			backClick: function () {
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

			validate: function () {
				var validate = this.ParentCall();

				if (validate && !this.employeeavailableplaygrounds) {
					validate = false;
					this.setError('employeeavailableplaygroundsselect', "Укажите площадки - в которых должен работать сотрудник");
				}

				return validate;
			},

			radioRoleClick: function (caller) {

				this.setValue('radiorole', $(caller).data('value'), true);

				$(caller).addClass('active').siblings().removeClass('active');
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

				var method = record.id ? 'update' : 'insert';

				var files = this.newFile ? [{name: 'photo', file: this.newFile}] : [];

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Добавляем сотрудника'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				Xhr.upload({
					url: "/City/Web/Employees/" + method,
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

						Alert.success("Сотрудник сохранен");

						this.parent.open("list");

					}.bind(this));

				}.bind(this));

			}

		});
	});