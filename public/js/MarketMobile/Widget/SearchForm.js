Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.Widget");

		KrujevaMobile.Widget.SearchForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/Widget/SearchForm",

			searchXhr: null,

			searchTimeout: null,

			makerequest: true,

			pagedata: {},

			$loadercnt: null,

			products: {},

			setPageData: function (data, $loadercnt) {
				this.pagedata = data;
				this.$loadercnt = $loadercnt;
			},

			initialize: function () {
				this.ParentCall();

				application.cartForm.on('changed', this.onCartChanged, this);
			},

			destroy: function () {

				application.cartForm.off('changed', this.onCartChanged, this);

				if (this.searchTimeout) {

					clearTimeout(this.searchTimeout);

					this.searchTimeout = null;
				}

				if (this.searchXhr) {

					this.searchXhr.abort();

					this.searchXhr = null;
				}

				this.$loadercnt = null;

				this.pagedata = {};

				this.ParentCall();
			},

			onKeyUpSearch: function (caller) {
				var val = $(caller).val();

				//this.initInteractionEvents();

				if (!val) {

					this.searchvalue = null;

					this.cancelSearch(false);

				} else {

					this.searchvalue = val;

					this.makerequest = true;

					this.$name('cancel-search-block').show();

					this.emit('clearlist');

					if (this.searchTimeout) {

						clearTimeout(this.searchTimeout);

						this.searchTimeout = null;
					}

					this.searchTimeout = setTimeout(function () {

						this.searchCall();

					}.bind(this), 400);

				}
			},

			searchCall: function () {

				application.showLoader(this, this.serverSendSearch, {
					text: '&nbsp;',
					bgcolor: 'transparent',
					bgloader: '#0288D1',
					bgloadercircle: 'rgba(2, 136, 209, .25)',
					prependElement: this.$loadercnt,
					cssclass: 'toploader',
					animate: false,
					loadertimeout: 0,
					showTimeout: 0,
					showNoInternet: false
				});

			},

			serverSendSearch: function (callback) {

				if (this.searchXhr) {
					this.searchXhr.abort();
					this.searchXhr = null;
				}

				var source = new KrujevaMobile.Data.Products();

				var data = {
					value: this.searchvalue.toLowerCase().trim(),
					brandid: this.pagedata.brandid,
					dealerbrandid: this.pagedata.dealerbrandid
				};

				var self = this;

				this.searchXhr = source.mobilesearch(data, function (data, result) {

					self.searchXhr = null;

					callback(data, result, function (data, result) {

						if (!self.makerequest) {
							return;
						}

						if (data && Util.isArray(data) && data.length) {

							self.products = {};

							var productssort = [];

							for (var i in data) if (data.hasOwnProperty(i)) {

								var product = data[i];

								product['sortparam'] = 99999999;

								if (product['priceobject']) {

									var pr = JSON.parse(product['priceobject']);

									if (pr.price) {
										product['price'] = parseFloat(pr.price);

										product['sortparam'] = product['price'];
									}

									delete product['priceobject'];
								}

								self.products[product['id']] = product;

								productssort.push(product);
							}

							productssort = Util.sort(productssort, 'sortparam');

							var $html = UI.Template.$render('KrujevaMobile/Widget/SearchItem', {widget: self, items: productssort});


							//инициализируем клики
							self.initInteractionEvents($html);

						 	self.emit('data', $html);
						}

					});

				});
			},

			cancelSearch: function (hidekeyboard) {

				this.searchvalue = null;

				this.makerequest = false;

				if (this.searchTimeout) {

					clearTimeout(this.searchTimeout);

					this.searchTimeout = null;
				}

				if (this.searchXhr) {

					this.searchXhr.abort();

					this.searchXhr = null;
				}

				setTimeout(function () {
					this.emit('clear');
				}.bind(this), 100);

				setTimeout(function () {
					this.$name('cancel-search-block').hide();
				}.bind(this), 200);

				if (hidekeyboard) {

					this.$name('search-field').val('').blur().parent().focus();
				}
			},

			cancelSearchClick: function () {

				this.cancelSearch(true);
			},

			getProductImgSrc: function (item) {
				return Xhr.prefix + item['photopath'] + (Util.isRetina() ? '2x_' : '') + item['photoname'];
			},



			buyPlusSearchClick: function (caller) {

				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var product = this.products[id];

				if (!product) {
					return;
				}

				//@hide keyboard
				this.$name('search-field').blur().parent().focus();

				application.cartForm.addProduct(product, this.pagedata);
			},

			buySearchClick: function (caller) {
				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var product = this.products[id];

				if (!product) {
					return;
				}

				this.$name('search-field').val('');  

				//@hide keyboard
				//this.$name('search-field').blur().parent().focus();

				application.cartForm.addProduct(product, this.pagedata);
			},

			buyMinusSearchClick: function (caller) {

				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var product = this.products[id];

				if (!product) {
					return;
				}

				//@hide keyboard
				this.$name('search-field').blur().parent().focus();

				application.cartForm.removeProduct(product, this.pagedata);
			},

			cartOpenSearchClick: function () {

				//@hide keyboard
				this.$name('search-field').blur().parent().focus();

				application.open('cart', {animate: true});
			},

			searchItemClick: function(caller) {

				var id = $(caller).data('id');

				if (!id) {
					return;
				}

				var product = this.products[id];

				if (!product) {
					return;
				}

				this.pagedata['productid'] = id;

				this.pagedata['product'] = Util.clone(product);

				application.open('product', {
					animate: true,
					data: Util.clone(this.pagedata)
				});
			},

			onCartChanged: function (args) {

				var product = this.products[args.id];

				if (!product) {

					return;
				}

				if (!this.parent) {

					return;
				}

				var count = args.count;

				if (args.type == 'add' || count) {

					this.parent.$name('buy-search-btn-product-' + args.id).html('В корзине ' + count + ' шт');

				} else if (!count) {

					this.parent.$name('buy-search-btn-product-' + args.id).html('Купить');
				}
			}

		});
	}
);