Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.CartForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/CartForm",

			options: {},

			initialize: function () {
				this.ParentCall();

				application.cartForm.on('changed', this.onCartChanged, this);
			},

			destroy: function () {
				application.cartForm.off('changed', this.onCartChanged, this);

				this.ParentCall();
			},

			menuClick: function () {
				application.showMenu();
			},

			backClick: function () {
				application.backOpenPage();
			},

			render: function (options) {

				if (options && Util.isObject(options)) {

					this.options = Util.clone(options);
				}

				this.ParentCall();

				return this;
			},

			afterRender: function () {
				this.listenScroll();
			},

			afterOpen: function (page, options) {

				this.listenScroll();

				if (options.closeOtherPages) {

					//1 чистим историю
					LocalStorage.set('history.pages', [page]);

					//2 закрыть все нахер
					for (var i in application.openedPages) if (application.openedPages.hasOwnProperty(i)) {

						if (i == page) {
							continue;
						}

						application.openedPages[i].destroy();
					}
				}
			},

			plusProductClick: function (caller) {
				var productid = $(caller).data('id');

				var dealerregionid = $(caller).data('dealerregionid');

				application.cartForm.plusProductById(productid, dealerregionid);
			},

			minusProductClick: function (caller) {
				var productid = $(caller).data('id');

				var dealerregionid = $(caller).data('dealerregionid');

				application.cartForm.minusProductById(productid, dealerregionid);
			},

			onCartChanged: function (args) {

				var data = args.data;

				for (var i in data) if (data.hasOwnProperty(i)) {

					var cart = data[i];

					var priceSummary = 0;

					for (var j in cart['items']) if (cart['items'].hasOwnProperty(j)) {

						var product = cart['items'][j];

						priceSummary += (product['cartCount'] * product['price'])
					}

					//@price
					this.$name('summary-price-'+ cart.dealer.dealerregionid).html(Util.priceFormat(priceSummary, 0, 3, ' '));

					//@bonus
					var bonus = parseInt(priceSummary * 0.1);

					var titbonus = Util.declOfNum(bonus, [' балл', ' балла', ' баллов']);

					this.$name('summary-bonus-' + cart.dealer.dealerregionid).html(Util.priceFormat(bonus, 0, 3, ' ') + ' '+ titbonus);

					//@ кнопка оформить заказ
					var minsum = cart.dealer.minsum;

					var needSum = minsum - priceSummary;

					var okMin = priceSummary >= minsum;

					var titles = [' рубль', ' рубля', ' рублей'];

					if (cart['dealer']['bonuscart']) {
						titles = [' балл', ' балла', ' баллов'];
					}

					var txtMin = Util.priceFormat(cart.dealer.minsum, 0, 3, ' ') + Util.declOfNum(cart.dealer.minsum, titles);

					var txtNeedSum = Util.priceFormat(needSum, 0, 3, ' ') + Util.declOfNum(needSum, titles);

					this.$name('cart-min-sum-txt-'+ cart.dealer.dealerregionid).html(txtMin);

					this.$name('need-price-min-txt-'+ cart.dealer.dealerregionid).html(txtNeedSum);

					if (okMin) {

						this.$name('cart-min-sum-error-'+ cart.dealer.dealerregionid).hide();
						this.$name('buy-btn-cart-f-'+ cart.dealer.dealerregionid).removeClass('disabled');

					} else {

						this.$name('cart-min-sum-error-' + cart.dealer.dealerregionid).show();
						this.$name('buy-btn-cart-f-' + cart.dealer.dealerregionid).addClass('disabled');
					}
				}


				var count = args.count;

				this.$name('cart-product-' + args.id).html(count + ' шт');


				//@remove item
				if (args.type == 'remove' && !count) {

					this.$name('cart-product-item-' + args.id).remove();

					//@удалить блок - если не осталось ни одного итема
					if (!data[args.dealerregionid]) {

						this.$name('dealer-region-' + args.dealerregionid).remove();
					}
				}

				this.refreshIscroll();
			},

			buyClick: function (caller) {

				if ($(caller).hasClass('disabled')) {
					return;
				}

				var dealerregionid = $(caller).data('dealerregionid');

				application.orderData = {};

				var cart = application.cartForm._getStorageData();

				if (!cart) {
					return;
				}

				if (!cart[dealerregionid]) {
					return;
				}

				//if (cart[dealerregionid]['dealer']['bonuscart']) {
				//	Alert.error('Пока невозможно оформить бонусный заказ. <br> Мы работаем над этим!');
				//	return;
				//}


				//application.orderData['order']['dealer']['bonuscart']

				application.orderData['order'] = Util.clone(cart[dealerregionid]);

				application.open('order', {animate: true});
			},

			getImgSrc: function (item) {
				return Xhr.prefix + item['photopath'] + (Util.isRetina() ? '2x_' : '') + item['photoname'];
			},


		});
	}
);