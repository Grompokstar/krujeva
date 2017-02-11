Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.Widget");

		KrujevaMobile.Widget.CartForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/Widget/CartForm",

			//храним в какой корзине дилера мы сейчас сидим
			currentDealerData: {},

			isVisible: false,

			cartClick: function () {
				application.open('cart', {animate: true});
			},

			show: function (pagedata) {

				if (pagedata) {
					this.restoreCart(pagedata);
				}

				if (!this.isInitiableCart()) {
					return;
				}

				//count items
				if (!this.countInCart(true)) {
					return;
				}

				if (this.isVisible) {

					this.updateRenderCartData();

					return;
				}


				this.render();

				this.initCSS();

				this.$element.appendTo(application.$page());

				var style = [];

				style.push('-webkit-transform: translate3d(0, 0, 0);');

				style.push('transform: translate3d(0, 0, 0);');

				setTimeout(function () {

					this.$element.attr('style', style.join(''));

					this.isVisible = true;

				}.bind(this), 100);
			},

			initCSS: function () {
				var style = [];

				style.push('-webkit-transform: translate3d(0, 50px, 0);');

				style.push('transform: translate3d(0, 50px, 0);');

				this.$element.attr('style', style.join(''));
			},

			hide: function () {

				if (!this.$element) {
					return;
				}

				if (!this.isVisible) {
					return;
				}

				var style = [];

				style.push('-webkit-transform: translate3d(0, 50px, 0);');

				style.push('transform: translate3d(0, 50px, 0);');

				setTimeout(function () {

					this.$element.attr('style', style.join(''));

					setTimeout(function () {

						this.$element.remove();

						this.isVisible = false;

						this.currentDealerData = {};

					}.bind(this), 400);

				}.bind(this), 0);
			},

			restoreCart: function (data) {

				if (!data) {
					return;
				}

				//пользователь может сразу держать несколько корзин
				//dealerregionid - ключ по которому дробятся корзины
				if (!data.dealerregionid) {
					return;
				}

				if (!data.minsum) {
					return;
				}

				if (!data.dealername) {
					return;
				}

				this.currentDealerData = {
					dealerregionid: data.dealerregionid,
					minsum: data.minsum,
					dealername: data.dealername,
					bonuscart: data.bonuscart
				};
			},

			//понятно куда кидать - в какую корзину или нет ?
			isInitiableCart: function () {
				return Object.keys(this.currentDealerData).length;
			},

			//in product data
			//out count
			addProduct: function (product, data) {

				this.restoreCart(data);

				if (!this.isInitiableCart()) {
					return;
				}

				var cartData = this._getStorageData();

				if (!cartData) {
					cartData = {};
				}

				var currentCart = this.currentDealerData;

				//dealer cart
				if (!cartData[currentCart['dealerregionid']]) {

					cartData[currentCart['dealerregionid']] = {
						dealer: Util.clone(currentCart),
						items: []
					};
				}

				//бонусная тема
				if (currentCart['bonuscart']) {

					//на какую сумму уже взяли бонусами
					var tprice = this.calcSummary(cartData[currentCart['dealerregionid']]) + product['price'];

					if (tprice > context('bonus')) {
						Alert.error('Не хватает бонусных баллов');
						return;
					}
				}

				//find old product
				var items = cartData[currentCart['dealerregionid']]['items'];

				var cartCount = 0;

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					if (item['id'] == product['id']) {

						item['cartCount']++;

						cartCount = item['cartCount'];

						break;
					}
				}

				if (!cartCount) {

					cartData[currentCart['dealerregionid']]['items'].push(
						Util.merge(product, {cartCount: 1}, true)
					);

					cartCount = 1;
				}

				this._setStorageData(cartData);

				this.show();

                this.emit('changed', {
					data: Util.clone(cartData),
					type: 'add',
					id: product['id'],
					count: cartCount,
					dealerregionid: currentCart['dealerregionid']
				});

				return cartCount;
			},

			plusProductById: function (productid, dealerregionid) {

				var cartData = this._getStorageData();

				if (!cartData) {
					return 0;
				}

				if (!cartData[dealerregionid]) {
					return 0;
				}

				var items = cartData[dealerregionid]['items'];

				//бонусная тема
				var dealer = cartData[dealerregionid]['dealer'];

				var product = null;

				for (var j in items) if (items.hasOwnProperty(j)) {
					var itema = items[j];

					if (itema['id'] == productid) {
						product = itema;
					}
				}

				if (dealer['bonuscart'] && product) {

					//на какую сумму уже взяли бонусами
					var tprice = this.calcSummary(cartData[dealerregionid]) + product['price'];

					if (tprice > context('bonus')) {
						Alert.error('Не хватает бонусных баллов');
						return;
					}
				}


				//find old product
				var cartCount = 0;

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					if (item['id'] == productid) {

						item['cartCount']++;

						cartCount = item['cartCount'];

						break;
					}
				}

				this._setStorageData(cartData);

				this.show();

				this.emit('changed', {
					data: Util.clone(cartData),
					type: 'add',
					id: productid,
					count: cartCount,
					dealerregionid: dealerregionid
				});

				return cartCount;
			},

			minusProductById: function (productid, dealerregionid) {

				var cartData = this._getStorageData();

				if (!cartData) {
					return 0;
				}

				if (!cartData[dealerregionid]) {
					return 0;
				}

				//find old product
				var items = cartData[dealerregionid]['items'];

				var cartCount = 0;

				var removeIndex = null;

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					if (item['id'] == productid) {

						item['cartCount']--;

						cartCount = item['cartCount'];

						if (cartCount <= 0) {
							removeIndex = i;
						}

						break;
					}
				}

				if (removeIndex !== null) {
					cartData[dealerregionid]['items'].splice(removeIndex, 1);
				}

				var needHide = false;

				if (!cartData[dealerregionid]['items'].length) {

					delete cartData[dealerregionid];

					needHide = true;
				}

				this._setStorageData(cartData);

				this.updateRenderCartData();

				this.emit('changed', {
					data: Util.clone(cartData),
					type: 'remove',
					id: productid,
					count: cartCount,
					dealerregionid: dealerregionid
				});

				if (needHide) {

					this.hide();
				}

				return cartCount;
			},

			//in product data
			//out count
			removeProduct: function (product, data) {

				this.restoreCart(data);

				if (!this.isInitiableCart()) {
					return;
				}

                var cartData = this._getStorageData();

                if (!cartData) {
                    return 0;
                }

                var currentCart = this.currentDealerData;

                //dealer cart
                if (!cartData[currentCart['dealerregionid']]) {
                    return 0;
                }

                //find old product
                var items = cartData[currentCart['dealerregionid']]['items'];
                var cartCount = 0;
                var removeIndex = null;

                for (var i in items) if (items.hasOwnProperty(i)) {

                    var item = items[i];

                    if (item['id'] == product['id']) {

                        item['cartCount']--;

                        cartCount = item['cartCount'];

                        if (cartCount <= 0) {
                            removeIndex = i;
                        }

                        break;
                    }
                }

                if (removeIndex !== null) {
                    cartData[currentCart['dealerregionid']]['items'].splice(removeIndex, 1);
                }

                var needHide = false;

                if (!cartData[currentCart['dealerregionid']]['items'].length) {

                    delete cartData[currentCart['dealerregionid']];

                    needHide = true;
                }

                this._setStorageData(cartData);

                this.updateRenderCartData();

                this.emit('changed', {
					data: Util.clone(cartData),
					type: 'remove',
					id: product['id'],
					count: cartCount,
					dealerregionid: currentCart['dealerregionid']
				});

                if (needHide) {

                    this.hide();
                }

                return cartCount;
			},

			//сколько товаров в корзине - точнее во всех корзинах
			countInCart: function (getCurrentCart) {

				var cartData = this._getStorageData();

				if (!cartData) {
					return 0;
				}

				//дай мне количество товара только в текущей корзине
				var currentCartCount = typeof getCurrentCart !== 'undefined';

				if (currentCartCount) {

					var currentCart = this.currentDealerData;

					if (!currentCart['dealerregionid']) {
						return 0;
					}

					if (!cartData[currentCart['dealerregionid']]) {
						return 0;
					}

					return cartData[currentCart['dealerregionid']]['items'].length;

				}


				var countAll = 0;

				for (var i in cartData) if (cartData.hasOwnProperty(i)) {

					var v = cartData[i];

					countAll += v['items'].length;
				}

				return countAll;
			},

            updateCountElement: function ($el, data, dealerregionid) {

                var count = application.cartForm.getCountByData(data, dealerregionid);

                if (count) {

                    $el.html('<div class="count-cart">' + count + '</div>');

                } else {

                    $el.empty();

                }

            },

            getCountByData: function (data, dealerregionid) {

                if (!data) {
                    return 0;
                }

                if (!data[dealerregionid]) {
                    return 0;
                }

                return data[dealerregionid]['items'].length;
            },


			cartPriceData: function () {

				var cartData = this._getStorageData();

				if (!cartData) {
					return null;
				}

				var currentCart = this.currentDealerData;

				if (!currentCart['dealerregionid']) {
					return null;
				}

				if (!cartData[currentCart['dealerregionid']]) {
					return null;
				}

				var data = {
					minsum: cartData[currentCart['dealerregionid']]['dealer']['minsum'],
					buysum: 0,
					countproducts: 0,
					bonuscart: currentCart['bonuscart']
				};

				var items = cartData[currentCart['dealerregionid']]['items'];

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					data['buysum'] += (item['price'] * item['cartCount']);

					data['countproducts']++;
				}

				return data;
			},

			updateRenderCartData: function () {

				var data = this.cartPriceData();

				if (!data) {
					return;
				}

				var titles = [' рубль', ' рубля', ' рублей'];

				if (data.bonuscart) {
					titles = [' балл', ' балла', ' баллов'];
				}

				var cart = Util.priceFormat(data['buysum'], 0, 3, ' ') + ( Util.declOfNum(data['buysum'], titles));

				this.$name('cart-data').html(cart);
			},

			getProductCountInCart: function (dealerregionid, productid, cartdata) {

				if (!cartdata) {
					return 0;
				}

				if (!cartdata[dealerregionid]) {
					return 0;
				}

				var items = cartdata[dealerregionid]['items'];

				var countItems = 0;

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					if (item['id'] == productid) {
						countItems = item['cartCount'];
						break;
					}
				}

				return countItems;
			},

			calcSummary: function (order) {

				var summaryCount = 0;

				if (!order) {
					return 0;
				}

				if (!order['items']) {
					return 0;
				}

				for (var j in order['items']) if (order['items'].hasOwnProperty(j)) {
					var product = order['items'][j];
					summaryCount += (product['cartCount'] * product['price'])
				}

				return summaryCount;
			},


			clearStorageDataByDealerRegionId: function (dealerregionid) {

				var cartData = this._getStorageData();

				if (!cartData) {
					return null;
				}

				delete cartData[dealerregionid];

				this._setStorageData(cartData);

				this.emit('changed', {});

				return true;
			},

			_getStorageData: function () {
				return LocalStorage.get('market.cart');
			},

			_setStorageData: function (data) {
				return LocalStorage.set('market.cart', data);
			},

			_clearStorageData: function () {
				return LocalStorage.remove('market.cart');
			},
		});
	}
);