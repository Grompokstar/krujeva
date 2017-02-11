Module.define(

	function () {
		NS("App.Admin");

		App.Admin.SliderAdd = Class(App.BaseForm, {

			//@oldPhoto
			oldPhoto: null,

			newFile: null,

			initialize: function () {

				this.$container = $('body');

				this.initInteractionEvents();

				if (!this.$name('date').val()) {

					this.$name('date').val(DateTime.getCurrentDate());
				}

				this.$name('date').datepicker();
			},

			saveClick: function () {

				var record = this.formRecord(this.$name('form'));

				var files = this.newFile ? [
					{name: 'file', file: this.newFile}
				] : [];

				Xhr.upload({
					url: '/Slider/' + (record.id ? 'update' : 'insert'),
					files: files,
					data: {item: JSON.stringify(record)}
				}, function (result, response) {

					result = result.data;

					if (result && result.item) {

						Alert.success('Успешно сохранено');

						setTimeout(function () {
							window.location = '/Admin/slider';
						}, 500);
						 
					} else {

						Alert.error('Не удалось сохранить');
					}


				}.bind(this));
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

					var $cnt = this.$name('photo-product-preview').empty();

					preview.appendTo($cnt);

					$cnt.css('width', preview.width());

					this.newFile = inputfile;

					this.oldPhoto = null;

				}.bind(this);

				if (file) {
					reader.readAsDataURL(file);
				} else {
					preview.attr('src', '');
				}
			},


		});
	});
