Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.Widget.SearchForm",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.ListProductsForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/ListProductsForm",

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

				this.searchForm = new KrujevaMobile.Widget.SearchForm(this);

				this.searchForm.on('data', this.onSearchData, this);

				this.searchForm.on('clear', this.onSearchClear, this);

				this.searchForm.on('clearlist', this.onSearchClearList, this);

                application.cartForm.on('changed', this.onCartChanged, this);
            },

			destroy: function () {
                application.cartForm.off('changed', this.onCartChanged, this);

				if (this.searchForm) {

					this.searchForm.off('data', this.onSearchData, this);

					this.searchForm.off('clear', this.onSearchClear, this);

					this.searchForm.off('clearlist', this.onSearchClearList, this);

					this.searchForm.destroy();

					this.searchForm = null;
				}

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

				//@render search widget
				this.searchForm.setPageData(this.pagedata, this.$name('list-items'));
				this.searchForm.render().$element.appendTo(this.$name('search-form'));

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

				source.mobilelist({
					categoryid: this.pagedata.categoryid,
					dealerbrandid: this.pagedata.dealerbrandid,
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
				var resres = [];
				var productssort = [];

				if (data.listitems && Util.isArray(data.listitems)) {

					//@prices
					var prices = [];
					var pricesIndex = {};

					if (data.prices && Util.isArray(data.prices)) {
						prices = data.prices;
					}

					for (var j in prices) if (prices.hasOwnProperty(j)) {

						var price = prices[j];

						pricesIndex[price['productid']] = parseFloat(price['price']);
					}

					//@products
					for (var i in data.listitems) if (data.listitems.hasOwnProperty(i)) {

						var product = data.listitems[i];

						product['sortparam'] = 99999999;

						//@price
						if (pricesIndex[product['id']]) {

							product['price'] = pricesIndex[product['id']];

							product['sortparam'] = parseFloat(product['price']);
						}

						this.products[product['id']] = product;

						productssort.push(product);
					}

					productssort = Util.sort(productssort, 'sortparam');


                    //sort by name
                    var indexPrc = {};
                    var counterPrc = 0;
                    var objData = {};

                    for (var z in productssort) if (productssort.hasOwnProperty(z)) {
                        var producta = productssort[z];

                        if (typeof indexPrc[producta['sortparam']] == 'undefined') {

                            indexPrc[producta['sortparam']] = counterPrc;

                            objData[indexPrc[producta['sortparam']]] = [];

                            counterPrc++;
                        }

                        objData[indexPrc[producta['sortparam']]].push(producta);
                    }

                    for (var x in objData) if (objData.hasOwnProperty(x)) {

                        var objitems = Util.sort(objData[x], 'name');

                        for (var c in objitems) if (objitems.hasOwnProperty(c)) {

                            resres.push(objitems[c]);

                        }

                    }

				}

				this.listproducts = resres;
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

				application.open('product', {
					animate: true,
					data: Util.clone(this.pagedata)
				});

			},


			//@search widget
			onSearchData: function ($html) {

				this.$name('offset-list').empty();

				$html.appendTo(this.$name('offset-list'));

				this.refreshIscroll(true);
			},

			onSearchClear: function () {

				//@render return data
				var  html = UI.Template.render('KrujevaMobile/ListProducts/Items', {widget: this});

				this.$name('offset-list').html(html);

				this.initInteractionEvents();

				this.refreshIscroll();

				this.searchForm.initInteractionEvents();
			},

			onSearchClearList: function (data) {

				this.$name('offset-list').empty();
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