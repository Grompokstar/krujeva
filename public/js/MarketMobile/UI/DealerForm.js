Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.DealerForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/DealerForm",

			dealer: null,

			backClick: function () {
				application.backOpenPage();
			},

			render: function (options) {

				if (options && options.dealer) {

					this.dealer = Util.clone(options.dealer);
				}

				return this.ParentCall();
			},

			afterRender: function () {
				this.listenScroll();
			},

			getSrcImg: function (brand) {

				return Xhr.prefix + '/' + 'pub' + '' + 'lic/' + '' + 'img/brands/' + brand['code'] + (Util.isRetina() ? '_2x' : '') + '.jpg';
			},

			brandClick: function (caller) {

				var $el = $(caller);

				var dealerid = $el.data('dealerid');

				var brandid = $el.data('brandid');

				var dealerbrandid = $el.data('dealerbrandid');

				if (!dealerid) {
					return;
				}

				var selectedDealer = this.dealer;


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
					dealerregionid: selectedDealer['dealerregionid'],
				};

				application.cartForm.restoreCart(data);

				application.open('brandcategory1', {animate: true, data: data});
			},

            phoneClick: function (caller) {

                var phone = $(caller).data('phone');

                if (!phone) {
                    return;
                }

                if (Util.isIOS()) {

                    window.open("tel://"+ phone, '_system');

                } else {

                    navigator.app.loadUrl("tel://"+ phone, {openExternal: true});
                }
            }
		});
	}
);