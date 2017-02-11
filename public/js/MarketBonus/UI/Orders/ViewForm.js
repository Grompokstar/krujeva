Module.define(
	"Base.Form",
	"Base.ListTrait",
	"KrujevaBonus.UI.Widget.AddPositionOrder",

	function () {
		NS("KrujevaBonus.UI.Orders");

		KrujevaBonus.UI.Orders.ViewForm = Class(Base.Form, {
			template: "KrujevaBonus/Orders/ViewForm",

            products: [],

            order: null,

			confirmForm: null,

			addForm: null,

			editOrder: {},

            initialize: function (parent, order) {
                this.ParentCall();

                this.order = order;
            },

			destroy: function () {

				this.order = null;

				this.confirmForm = null;

				this.addForm = null;

				this.editOrder = {};

				this.products = [];

				this.ParentCall();
			},

            render: function () {
                this.ParentCall();

                this.loadOrder();

                return this;
            },

            loadOrder: function () {

                if (this.products.length) {
                    return;
                }

                if (!this.order) {
                    return;
                }

                Loader.start(this.$name('view-loader'));

                var source = new KrujevaBonus.Data.BonusOrders();

                source.bonusget({id: this.order.id}, function (data, result) {

                    if (typeof this.render == 'undefined') {
                        return;
                    }

                    if (data && Util.isArray(data)) {
                        this.products = data;
						this.setEditOrder();
                        this.render();
                    }

                }.bind(this));

            },

			cancelClicked: function () {

				if (!confirm('Отклонить заказ?')) {
					return;
				}

				var loader = Loader.start(this.$name('view-loader'), {showTimeout: 0});

				var source = new KrujevaBonus.Data.BonusOrders();

				source.bonuscancel({id: this.order.id}, function (data, result) {

					Loader.end(loader, function () {

						if (data) {

							Alert.success('Заказ отклонен');

							this.destroy();
						} else {

							if (result && result.message) {

								Alert.error(result.message);

							} else {

							}

						}

					}.bind(this));

				}.bind(this));
			},

			verifyClicked: function () {

				if (this.confirmForm) {
					this.confirmForm.off('confirmed', this.onConfirmed, this);

					this.confirmForm = null;
				}

				this.confirmForm = application.showWidget('KrujevaBonus.UI.Widget.ConfirmOrder', {animate: true, orderid: this.order.id, order: Util.clone(this.editOrder)});

				this.confirmForm.on('confirmed', this.onConfirmed, this);
			},

			onConfirmed: function () {
				this.confirmForm.off('confirmed', this.onConfirmed, this);

				this.confirmForm = null;

				this.destroy();
			},

			addPositionClick: function () {

				if (this.addForm) {
					this.addForm.off('add', this.onProductAdd, this);
					this.addForm = null;
				}

				this.addForm = application.showWidget('KrujevaBonus.UI.Widget.AddPositionOrder', {animate: true, order: Util.clone(this.order) });

				this.addForm.on('add', this.onProductAdd, this);
			},

			onProductAdd: function (args) {

				this.addForm.off('add', this.onProductAdd, this);

				this.addForm = null;

				var item = args.item;

				if (this.editOrder.products[item.productid]) {
					return;
				}

				this.editOrder.products[item.productid] = item;

				//@render item
				var html = UI.Template.render('KrujevaBonus/Orders/ViewProductItem', {product: item});

				this.$('.product-tr-line:last').after(html);

				//@calc summary
				var summary = this.calcSummary();

				this.editOrder['totalprice'] = summary['total'];

				this.$name('total-count').html(summary['count'] + ' шт');

				var price = Util.priceFormat(summary['total'], 0, 3, ' ');

				this.$name('total-price').html(price + ' руб.');
			},

			setEditOrder: function () {

				this.editOrder = {
					orderid: this.order.id,
					products: {},
					totalprice: 0
				};

				var total = 0;

				for (var i in this.products) if (this.products.hasOwnProperty(i)) {

					var product = this.products[i];

					this.editOrder.products[product.productid] = {
						productid: product.productid,
						price: product.price,
						count: product.count,
						name: product.name
					};

					total += parseFloat(product.price) * parseFloat(product.count);
				}

				this.editOrder['totalprice'] = total;
			},

			calcSummary: function () {

				var total = 0;
				var count = 0;

				for (var i in this.editOrder.products) if (this.editOrder.products.hasOwnProperty(i)) {

					var product = this.editOrder.products[i];

					total += parseFloat(product.price) * parseFloat(product.count);

					count += parseFloat(product.count);
				}

				return {total: total, count: count};
			},

			removeProductClick: function (caller) {

				if (!confirm('Удалить позицию?')) {
					return;
				}

				var productid = $(caller).data('productid');

				if (!productid) {
					return;
				}

				this.$name('product-'+ productid).remove();

				delete this.editOrder['products'][productid];

				var summary = this.calcSummary();

				this.editOrder['totalprice'] = summary['total'];

				this.$name('total-count').html(summary['count']+ ' шт');

				var price = Util.priceFormat(summary['total'], 0, 3, ' ');

				this.$name('total-price').html(price + ' руб.');
			},

			plusClick: function (caller) {

				var productid = $(caller).data('productid');

				if (!productid) {
					return;
				}

				if (!this.editOrder['products'][productid]) {
					return;
				}

				this.editOrder['products'][productid]['count']++;

				this.$name('product-count-'+ productid).html(this.editOrder['products'][productid]['count'] + ' шт');


				var summary = this.calcSummary();

				this.editOrder['totalprice'] = summary['total'];

				this.$name('total-count').html(summary['count'] + ' шт');

				var price = Util.priceFormat(summary['total'], 0, 3, ' ');

				this.$name('total-price').html(price + ' руб.');
			},

			minusClick: function (caller) {

				var productid = $(caller).data('productid');

				if (!productid) {
					return;
				}

				if (!this.editOrder['products'][productid]) {
					return;
				}

				if (this.editOrder['products'][productid]['count'] == 1) {

					this.removeProductClick(caller);

					return;
				}

				this.editOrder['products'][productid]['count']--;

				this.$name('product-count-' + productid).html(this.editOrder['products'][productid]['count'] + ' шт');

				var summary = this.calcSummary();

				this.editOrder['totalprice'] = summary['total'];

				this.$name('total-count').html(summary['count'] + ' шт');

				var price = Util.priceFormat(summary['total'], 0, 3, ' ');

				this.$name('total-price').html(price + ' руб.');
			},

			printClick: function () {

				var myWindow = window.open('', '', 'width=900,height=870');

				myWindow.document.write(UI.Template.render('KrujevaBonus/Orders/Print', {order: this.order, editOrder: this.editOrder}));

				myWindow.document.close();

				myWindow.focus();

				myWindow.print();

				myWindow.close();
			}

		});
	});