Module.define(

	function () {
		NS("App.Admin");

		App.Admin.ProductsAdd = Class(App.BaseForm, {

			//@oldPhoto
			oldPhoto: null,

			newFile: null,

			indexPrices: 0,

			initialize: function () {

				this.$container = $('body');

				this.initInteractionEvents();

				if (!this.$name('date').val()) {

					this.$name('date').val(DateTime.getCurrentDate());
				}

				this.$name('date').datepicker();

				this.initPrices();
			},

			saveClick: function () {

				var record = this.formRecord(this.$name('form'));

				var prices = [];

				//price
				this.$('.price-block').each(function () {

					var volume = $(this).find('input[name=volume]').val();
					var price = $(this).find('input[name=price]').val();

					if (volume && price) {

						prices.push({
							volume: volume,
							price: price
						});
					}
				});

				record['price'] = JSON.stringify(prices);

				var files = this.newFile ? [
					{name: 'file', file: this.newFile}
				] : [];

				Xhr.upload({
					url: '/Products/' + (record.id ? 'update' : 'insert'),
					files: files,
					data: {item: JSON.stringify(record)}
				}, function (result, response) {

					result = result.data;

					if (result && result.item) {

						Alert.success('Успешно сохранено');

						setTimeout(function () {
							window.location = '/Admin/products';
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

			addPriceClick: function (data) {

				var index = this.indexPrices++;

				var $template = UI.Template.$render('Krujeva/AddPrice', {index: index, data: data});

				$template.appendTo(this.$name('price-list'));
			},

			removePriceClick: function (caller) {
				this.$name('price-'+$(caller).data('index')).remove();
			},

			initPrices: function () {

				var prices = this.$name('price-list').attr('value');

				if (!prices) {
					return;
				}

				prices = prices.replace(/&&&&&/g, '"');

				if (!prices) {
					return;
				}

				prices = JSON.parse(prices);

				for (var i in prices) {

					this.addPriceClick(prices[i]);
				}
			}


		});
	});
