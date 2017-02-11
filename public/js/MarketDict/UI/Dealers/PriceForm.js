Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.DealerRegions",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.PriceForm = Class(Base.Form, {
			template: "KrujevaDict/Dealers/PriceForm",

			item: null,

			render: function () {

				if (this.getArgumentString() && !this.isAppended()) {

					var id = this.getArgumentString();

					this.getProductPack(id);
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

					self.item = result;

					self.render();

					self.setArgumentString(id);
				});

			},

			cancelClick: function () {
				this.parent.open('list');
			},

			downloadCatalogClick: function (caller) {
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

				var loader = Loader.start(this.$name('form-loader'));

				var self = this;

				var source = new KrujevaDict.Data.Dealers();

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