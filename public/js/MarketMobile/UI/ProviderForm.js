Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.ProviderForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/ProviderForm",

			dealers: [],
			nodealers: false,

			destroy: function () {
				this.dealers = [];

				this.ParentCall();
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function () {

				if (!this.dealers.length) {

					application.showLoader(this, this.serverSend, {
						text: '&nbsp;',
						bgcolor: 'transparent',
						bgloader: '#0288D1',
						bgloadercircle: 'rgba(2, 136, 209, .25)',
						prependElement: this.$element,
						animate: false,
						loadertimeout: 0
					});

				} else {

					this.refreshIscroll();
				}
			},

			menuClick: function () {
				application.showMenu();
			},

			serverSend: function (callback) {

				var source = new KrujevaMobile.Data.Dealers();

				var self = this;

				source.mobilelist({areaid: context('regionid')}, function (data, result) {

					callback(data, result, function (data, result) {

						if (data) {

							self.dealers = data;

							self.render();

						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

								//нет брендов в регионе
								self.nodealers = true;

								self.render();

								//Alert.error('Не удалось получить список поставщиков');
							}

						}

					});

				});
			},

			getSrcImg: function (brand) {

				return Xhr.prefix+'/'+'pub'+''+'lic/'+''+'img/brands/'+ brand['code']+(Util.isRetina()? '_2x': '') +'.jpg';
			},

			brandClick: function (caller) {

				var $el = $(caller);

				var dealerid = $el.data('dealerid');

				var brandid = $el.data('brandid');

				var dealerbrandid = $el.data('dealerbrandid');

				if (!dealerid) {
					return;
				}

				var selectedDealer = null;

				for (var i in this.dealers) if (this.dealers.hasOwnProperty(i)) {

					var dealer = this.dealers[i];

					if (dealer['id'] == dealerid) {

						selectedDealer = Util.clone(dealer);

						break;
					}
				}

				if (!selectedDealer) {
					return;
				}

				var selectedBrand = null;

				for (var j in selectedDealer['brands']) if (selectedDealer['brands'].hasOwnProperty(j)) {

					var brand = selectedDealer['brands'][j];

					if (brand['id'] == brandid) {
						selectedBrand = brand;
						break;
					}
				}

				if (!selectedBrand) {
					return;
				}

				var data = {
					brandid: brandid,
					dealerbrandid: dealerbrandid,
					dealername: selectedDealer['name'],
					brandname: selectedBrand['name'],
					minsum: selectedDealer['minsum'],
					dealerregionid: selectedDealer['dealerregionid']
				};

                application.cartForm.restoreCart(data);

				application.open('brandcategory1', {animate: true, data: data});
			},

			dealerInfoClick: function (caller) {
				var id = $(caller).data('id');

				var selectedDealer = null;

				for (var i in this.dealers) if (this.dealers.hasOwnProperty(i)) {

					var dealer = this.dealers[i];

					if (dealer['id'] == id) {

						selectedDealer = Util.clone(dealer);

						break;
					}
				}

				if (!selectedDealer) {
					return;
				}

				application.open('dealer', {dealer: selectedDealer, animate: true});
			}
		});
	}
);