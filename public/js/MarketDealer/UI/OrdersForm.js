Module.define(
	"Base.Form",
	"Base.ListTrait",
	"KrujevaDealer.UI.Orders.ViewForm",
	"KrujevaDealer.OrderStatus",

	function () {
		NS("KrujevaDealer.UI");

		KrujevaDealer.UI.OrdersForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDealer/OrdersForm",
			listItemTemplate: 'KrujevaDealer/Orders/ListItem',
			listGroupTemplate: 'KrujevaDealer/Orders/ListGroupItem',
			emptyListTemplate: 'KrujevaDealer/Orders/EmptyListTemplate',
			emptyItemTemplate: 'KrujevaDealer/Orders/EmptyItemTemplate',

			loadMethod: "dealerlist",

			currentArg: "new",

			itemForm: null,

			groupItems: true,

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDealer.Data.Orders();

				KrujevaDealer.Events.on('Krujeva.Orders.Insert', this.onOrderInsert, this);
				KrujevaDealer.Events.on('Krujeva.Orders.NotNew', this.onOrderNotNew, this);
				KrujevaDealer.Events.on('Krujeva.Orders.Cancelled', this.onOrderCancelled, this);
				KrujevaDealer.Events.on('Krujeva.Orders.Confirmed', this.onOrderConfirmed, this);
			},

			destroy: function () {
                KrujevaDealer.Events.off('Krujeva.Orders.NotNew', this.onOrderNotNew, this);
				KrujevaDealer.Events.off('Krujeva.Orders.Insert', this.onOrderInsert, this);
				KrujevaDealer.Events.off('Krujeva.Orders.Cancelled', this.onOrderCancelled, this);
				KrujevaDealer.Events.off('Krujeva.Orders.Confirmed', this.onOrderConfirmed, this);

				this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();
				this.listenScroll();
				this.renderEmptyItem();
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

				var self = this;

				this.refreshList(function(items) {

					Loader.end(loader, null, true);

					self.setEmptyText();
				});
			},

			setEmptyText: function () {

				var title = '';

				switch (this.currentArg) {

					case "all":
						title = 'Отсутствуют заказы';
						break;

					case "new":
						title = 'Нет новых заказов';
						break;

					case "confirmed":
						title = 'Нет подтвержденныx заказов';
						break;

					case "canceled":
						title = 'Отсутствуют отклоненные заказы';
						break;
				}

				if (Object.keys(this.objectListItems).length) {

					this.$name('empty-orders-list').remove();

				} else {

					this.$name('list-items').empty().append(UI.Template.render(this.emptyListTemplate, {title: title}));
				}
			},

			onOrderInsert: function(args) {

				if (this.currentArg == 'all' || this.currentArg == 'new') {

					this.insertItems([args.item], false, true);
				}

				this.setEmptyText();
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

				this.itemForm = new KrujevaDealer.UI.Orders.ViewForm(this, Util.clone(this.objectListItems[id]['item']));

				this.itemForm.render();

				this.renderEmptyItem(true);

				this.itemForm.$element.appendTo(this.$name('page').empty());


				this.itemForm.on('destroy', function () {

					this.itemForm = null;

					this.renderEmptyItem();

				}.bind(this));
			},

			renderEmptyItem: function (remove) {

				if (remove) {

					this.$name('empty-item-template').remove();

					return;
				}

				this.$name('page').empty().append(UI.Template.render(this.emptyItemTemplate));
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

				this.setEmptyText();
			},

			onOrderConfirmed: function (args) {

				if (this.currentArg == 'confirmed') {

					this.onItemRemove({id: args.item.id});

					this.insertGroupItems([args.item], false, true);

				} else {

					this.onItemRemove(args.item);
				}

				this.setEmptyText();
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