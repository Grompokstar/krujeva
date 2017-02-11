Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",
	"KrujevaMobile.Widget.PhotoSwipe",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.BonusProductForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/BonusProductForm",

			item: null,
			noitem: false,
			pagedata: {},

			photoswipeForm: null,

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

				this.item = null;

				this.pagedata = {};

				if (this.photoswipeForm) {

					this.photoswipeForm.destroy();

					this.photoswipeForm = null;
				}

				this.ParentCall();
			},

            onCartChanged: function (args) {
                application.cartForm.updateCountElement(this.$name('svgbasket'), args.data, this.pagedata.dealerregionid);

				if (this.item['id'] != args.id) {
					return;
				}

				var count = args.count;

				this.$name('count-buy-product').html(count);

				if (args.type == 'add') {

					this.$name('buy-btn-product').hide();

					this.$name('cart-product-counter').show();

				} else if (!count) {

					this.$name('buy-btn-product').show();

					this.$name('cart-product-counter').hide();
				}

            },

			render: function (options) {

				if (options && options.data) {

					this.pagedata = Util.clone(options.data);
				}

				/*this.pagedata = {
					productid: 166,
					product: {
						id: 166,
						name: 'Маска антистресс, против старения волос, 500мл',
						photoname: 'av_C6_bc06fe01.jpg',
						photopath: "/public/files/2016/03/15/",
						price: 300
					}
				};*/

				this.ParentCall();

				if (!this.item) {

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

				if (!this.pagedata || !this.pagedata.productid) {
					callback(null, null, function (data, result) {});
					return;
				}

				var source = new KrujevaMobile.Data.Products();

				var self = this;

				this.executeRequestCallback = false;

				this.isRequestEnd = false;

				source.mobileitem({
					productid: this.pagedata.productid,
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

				if (data && Util.isObject(data)) {

					data['fullitem'] = true;

					data['bigphotoname'] = data['photoname'] + '';

					delete data['photoname'];

					this.item = Util.merge(this.pagedata.product, data, true);

					this.renderAdditionData();

				} else {

					if (result && result.message) {

						Alert.error(result.message);

					} else {

						this.noitem = true;

					}

				}

			},

			getImgSrc: function () {

				if (!this.pagedata || !this.pagedata.product) {
					return '';
				}

				var item = this.pagedata.product;

				return Xhr.prefix + item['photopath'] + '2x_' + item['photoname'];
			},

			renderAdditionData: function () {

				var html = UI.Template.render('KrujevaMobile/BonusProduct/Item', {widget: this, item: this.item});

				this.$name('data-item').html(html);

				if (this.item.usedescription) {

					var htmluse = UI.Template.render('KrujevaMobile/BonusProduct/UseItem', {widget: this, item: this.item});

					this.$name('card-usedescription').html(htmluse);
				}

				//@load img background
				var path = Xhr.prefix + this.item['photopath'] + this.item['bigphotoname'];

				var self = this;

				$('<img src="' + path + '">').load(function () {

					var style = "background: url('"+ path +"') no-repeat;background-size:contain; background-position:center center;";

					self.$name('product-bg-img').attr('style', style);
				});

				this.refreshIscroll();

				this.initInteractionEvents();
			},

			onProductImgClick: function () {

				if (!this.item) {
					return null;
				}

				var path = Xhr.prefix + this.item['photopath'] + this.item['bigphotoname'];

				if (this.photoswipeForm) {

					this.photoswipeForm.destroy();

					this.photoswipeForm = null;
				}

				this.photoswipeForm = application.showWidget("KrujevaMobile.Widget.PhotoSwipe", {
					options: {
						path: path,
						width: this.item['photowidth'],
						height: this.item['photoheight'],
						title: this.item.name,
						animate: true
					}
				}).on('destroy', function () {

					this.photoswipeForm = null;

				}.bind(this));
			},

			buyClick: function () {

				if (!this.item) {
					return;
				}

				application.cartForm.addProduct(this.item, this.pagedata);
			},

            buyPlusClick: function () {

                if (!this.item) {
                    return;
                }

                application.cartForm.addProduct(this.item, this.pagedata);
            },

            buyMinusClick: function () {

                if (!this.item) {
                    return;
                }

                application.cartForm.removeProduct(this.item, this.pagedata);
            }
		});
	}
);