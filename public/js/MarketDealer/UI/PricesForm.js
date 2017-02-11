Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDealer.UI");

		KrujevaDealer.UI.PricesForm = Class(Base.Form, {
			template: "KrujevaDealer/PricesForm",

			regions: {},

			destroy: function () {

				this.regions = {};

				this.ParentCall();
			},

			render: function() {
				this.ParentCall();

				this.getPriceRegions();

				return this;
			},

			getPriceRegions: function () {

				var loader = Loader.start(this.$name('form-loader'));

				var source = new KrujevaDealer.Data.Dealers();

				source.regions({}, function (data, result) {

					Loader.end(loader, function () {

						if (!data && result && result.message) {

							Alert.error(result.message);
							return;
						}

						if (data && Util.isArray(data)) {
							this.renderRegions(data);
						}

					}.bind(this));

				}.bind(this));
			},

			renderRegions: function (regions) {

				var $html = $([]);

				var selectId = null;

				for (var i in regions) if (regions.hasOwnProperty(i)) {

					if (!selectId) {
						selectId = regions[i]['dealerbrandid'];
					}

					this.regions[regions[i]['dealerbrandid']] = regions[i];

					$html = $html.add(UI.Template.render('KrujevaDealer/Prices/Region', {item: regions[i]}));
				}

				$html.appendTo(this.$name('regions').empty());

				if (selectId) {

					this.selectRegion(selectId);
				}
			},

			regionClick: function (caller) {
				var id = $(caller).data('dealerbrandid');

				this.selectRegion(id);
			},

			selectRegion: function (dealerbrandid) {

				this.$name('region-' + dealerbrandid).addClass('active').siblings().removeClass('active');

				var region = this.regions[dealerbrandid];

				if (!region) {
					return;
				}

				//@render inner page
				var $html = UI.Template.$render('KrujevaDealer/Prices/Inner', {region: region, dealerbrandid: dealerbrandid});

				$html.appendTo(this.$name('page').empty());
			},

			downloadClick: function (caller) {

				var dealerbrandid = $(caller).data('dealerbrandid');

				var form = $("<form>", {
					target: "_blank",
					method: "post",
					action: "Dealers/pricelist"
				});

				form.append('<input name="dealerbrandid" value="' + dealerbrandid + '"/>');

				form.submit();
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

				var filesUpload = [
					{name: 'file', file: inputfile}
				];

				var loader = Loader.start(this.$name('price-loader'));

				var source = new KrujevaDealer.Data.Dealers();

				var record = {
					dealerbrandid: $(input).data('dealerbrandid')
				};

				Xhr.upload({
					url: source.url + 'loadprice',
					files: filesUpload,
					data: record
				}, function (result, response) {

					$(input).val('');

					Loader.end(loader, function () {

						if (result && result.data) {

							Alert.success('Успешно сохранено');

						} else {

							Alert.error(result.message);
						}

					});

				});
			},
		});
	});