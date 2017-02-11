Module.define(
	"Base.Form",
	"Base.ListTrait",
	"KrujevaBonus.UI.Orders.ViewForm",
	"KrujevaBonus.BonusOrderStatus",

	function () {
		NS("KrujevaBonus.UI");

		KrujevaBonus.UI.OrdersForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaBonus/OrdersForm",
			listItemTemplate: 'KrujevaBonus/Orders/ListItem',
			listGroupTemplate: 'KrujevaBonus/Orders/ListGroupItem',

			loadMethod: "bonuslist",

			currentArg: "new",

			itemForm: null,

			groupItems: true,

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaBonus.Data.BonusOrders();

				KrujevaBonus.Events.on('Krujeva.BonusOrders.Insert', this.onOrderInsert, this);
				KrujevaBonus.Events.on('Krujeva.BonusOrders.NotNew', this.onOrderNotNew, this);
				KrujevaBonus.Events.on('Krujeva.BonusOrders.Cancelled', this.onOrderCancelled, this);
				KrujevaBonus.Events.on('Krujeva.BonusOrders.Confirmed', this.onOrderConfirmed, this);
			},

			destroy: function () {
                KrujevaBonus.Events.off('Krujeva.BonusOrders.NotNew', this.onOrderNotNew, this);
				KrujevaBonus.Events.off('Krujeva.BonusOrders.Insert', this.onOrderInsert, this);
				KrujevaBonus.Events.off('Krujeva.BonusOrders.Cancelled', this.onOrderCancelled, this);
				KrujevaBonus.Events.off('Krujeva.BonusOrders.Confirmed', this.onOrderConfirmed, this);

				this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();
				this.listenScroll();
			},

			show: function (options) {
				this.ParentCall();

				if (!options['args']) {

					this.currentArg = "new";

				} else {

					this.currentArg = options['args'];
				}

				this.changeView();
			},

			changeView: function () {

				var title = '';

				switch (this.currentArg) {

					case "all":
						title = 'Все заказы';
						break;

					case "new":
						title = 'Новые заказы';
						break;

					case "confirmed":
						title = 'Подтвержденные заказы';
						break;

					case "canceled":
						title = 'Отклоненные заказы';
						break;
				}

				this.$name('type-page').html(title);

				this.filter['type'] = this.currentArg;

				this.groupItems = this.filter['type'] == 'confirmed';


				var loader = Loader.start(this.$name('form-loader'));

				if (this.$list) {

					this.objectListItems = {};

					this.$list.empty();
				}

                if (this.itemForm) {

                    this.itemForm.destroy();

                    this.itemForm = null;
                }

				this.refreshList(function() {
					Loader.end(loader, null, true);
				});
			},

			onOrderInsert: function(args) {

				if (this.currentArg == 'all' || this.currentArg == 'new') {

					this.insertItems([args.item], false, true);
				}
			},

			itemClick: function (caller) {
				var id = $(caller).data('id');

				//@select Item
				this.$('.order-list-item').removeClass('opened');
				$(caller).addClass('opened');

                if (this.itemForm) {

                    this.itemForm.destroy();

                    this.itemForm = null;
                }

                if (!this.objectListItems[id]) {
                    return;
                }

				this.itemForm = new KrujevaBonus.UI.Orders.ViewForm(this, Util.clone(this.objectListItems[id]['item']));

				this.itemForm.render();

				this.itemForm.$element.appendTo(this.$name('page').empty());

				this.itemForm.on('destroy', function () {

					this.itemForm = null;

				}.bind(this));
			},

			getTime: function (order) {

				var currentDate = DateTime.getCurrentDate('YYYY-MM-DD');

				var yesterdayDate = DateTime.momentDate(currentDate, 'YYYY-MM-DD');

				yesterdayDate = yesterdayDate.subtract(1, 'days').format('YYYY-MM-DD');

				var orderDate = DateTime.getDate(order['localcreateddatetime'], 'YYYY-MM-DD', 'YYYY-MM-DD HH:mm');


				var datePrefix = DateTime.getDate(order['localcreateddatetime'], 'DD MMM ', 'YYYY-MM-DD HH:mm')+ ' в ';

				if (currentDate == orderDate) {

					datePrefix = 'сегодня в ';

				} else if (yesterdayDate == orderDate) {

					datePrefix = 'вчера в ';
				}

				return datePrefix + DateTime.getDate(order['localcreateddatetime'], 'HH:mm', 'YYYY-MM-DD HH:mm');
			},

            onOrderNotNew: function (args) {
                this.$name('new-order-ico-'+args.id).hide();
            },

			onOrderCancelled: function (args) {

				if (this.currentArg == 'canceled') {

					this.insertItems([args.item], false, true);

				} else {

					this.onItemRemove(args.item);
				}
			},

			onOrderConfirmed: function (args) {

				if (this.currentArg == 'confirmed') {

					this.onItemRemove({id: args.item.id});

					this.insertGroupItems([args.item], false, true);

				} else {

					this.onItemRemove(args.item);
				}
			},

			getClassOrder: function (item) {

				if (this.currentArg != 'confirmed') {
					return '';
				}

				if (!item['localdeliverydate']) {
					return '';
				}
				var date = parseInt(DateTime.getDate(item['localdeliverydate'], 'X')) + (60*60*24);

				if (DateTime.getCurrentDate('X') > date) {
					return 'end-order';
				}

				return '';
			}

		});
	});