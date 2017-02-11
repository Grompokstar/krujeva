Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.Widget.SearchForm",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.ListBonusProductsForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/ListBonusProductsForm",

			products:{},
            listproducts: [],
			noproducts: false,
			pagedata: {},

			//@request
			openedPage: false, //открылась ли страница? анимация законилась ?

			executeRequestCallback: false, //выполнена ли callback функция

			requestData: null, //храним данные ответа

			requestResult: null, //храним данные ответа

			isRequestEnd: false, //закончилось выполнение запроса или нет

            initialize: function () {
                this.ParentCall();

                application.cartForm.on('changed', this.onCartChanged, this);
            },

			destroy: function () {
                application.cartForm.off('changed', this.onCartChanged, this);

				this.products = {};

				this.pagedata = {};

				this.ParentCall();
			},

            onCartChanged: function (args) {
                application.cartForm.updateCountElement(this.$name('svgbasket'), args.data, this.pagedata.dealerregionid);

				var count = args.count;

				if (args.type == 'add' || count) {

					this.$name('buy-list-item-btn-product-' + args.id).html('В корзине '+ count + ' шт');

				} else if (!count) {

					this.$name('buy-list-item-btn-product-' + args.id).html('Купить');
				}
            },

			render: function (options) {

				if (options && options.data) {

					this.pagedata = Util.clone(options.data);
				}

				this.ParentCall();

				if (!Object.keys(this.products).length && !this.noproducts) {

					application.showLoader(this, this.serverSend, {
						text: '&nbsp;',
						bgcolor: 'transparent',
						bgloader: '#0288D1',
						bgloadercircle: 'rgba(2, 136, 209, .25)',
						prependElement: this.$element,
						animate: false,
						showTimeout: 0,
						loadertimeout: 0
					});
				}

				return this;
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function () {

				this.openedPage = true;

				this.callbackRequestAfterOpen();
			},

			backClick: function () {
				application.backOpenPage();
			},

			cartClick: function () {
				application.open('cart', {animate: true});
			},

			serverSend: function (callback) {

				/*this.pagedata = {
					categoryid: 75,
					dealerbrandid: 7
				};*/

				if (!this.pagedata || !this.pagedata.categoryid) {
					callback(null, null, function (data, result) {});
					return;
				}

				var source = new KrujevaMobile.Data.Products();

				var self = this;

				this.executeRequestCallback = false;

				this.isRequestEnd = false;

				source.mobilelistgift({
					categoryid: this.pagedata.categoryid
				}, function (data, result) {

					callback(data, result, function (data, result) {

						self.requestData = data;

						self.requestResult = result;

						self.isRequestEnd = true;

						self.callbackRequestAfterOpen();

					});

				});
			},

			callbackRequestAfterOpen: function () {

				if (!this.isRequestEnd) {
					return;
				}

				if (!this.openedPage) {
					return;
				}

				if (this.executeRequestCallback) {
					return;
				}

				this.executeRequestCallback = true;

				var data = this.requestData;

				var result = this.requestResult;

				if (data) {

					this.formItemsData(data);

					this.render();

				} else {

					if (result && result.message) {

						Alert.error(result.message);

					} else {

						this.noproducts = true;

						this.render();

					}

				}

			},

			formItemsData: function (data) {
				var productssort = [];

				if (data.listitems && Util.isArray(data.listitems)) {

					//@products

					for (var i in data.listitems) if (data.listitems.hasOwnProperty(i)) {

						var product = data.listitems[i];

						product['sortparam'] = product['bonusnayacena'];
						product['price'] = product['bonusnayacena'];

						this.products[product['id']] = product;

						productssort.push(product);
					}

					productssort = Util.sort(productssort, 'sortparam');
				}

                this.listproducts = productssort;
			},

			getImgSrc: function (item) {
				return Xhr.prefix + item['photopath'] + (Util.isRetina() ? '2x_' : '') + item['photoname'];
			},

			itemClick: function (caller) {

				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var item = null;

				for (var i in this.products) if (this.products.hasOwnProperty(i)) {

					var it = this.products[i];

					if (it.id == id) {
						item = Util.clone(it);
						break;
					}
				}

				if (!item) {
					return;
				}

				this.pagedata['productid'] = id;

				this.pagedata['product'] = item;

				application.open('bonusproduct', {
					animate: true,
					data: Util.clone(this.pagedata)
				});
			},

			buyClick: function (caller) {
				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var product = this.products[id];

				if (!product) {
					return;
				}

				application.cartForm.addProduct(product, this.pagedata);
			},
		});
	}
);