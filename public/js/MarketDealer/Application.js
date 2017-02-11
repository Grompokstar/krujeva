Module.define(
	"Base.Application",
	"DateTime",
	"Message",
	"KrujevaDealer.Events",
	"System.NumSeq",
	"KrujevaDealer.UI.LoginForm",
	"KrujevaDealer.UI.ForgotForm",
	"KrujevaDealer.UI.OrdersForm",
	"KrujevaDealer.UI.OrdersForm",
	"KrujevaDealer.UI.MenuForm",
	"KrujevaDealer.UI.PricesForm",
	"KrujevaDealer.UI.Widget.ConfirmOrder",

	function () {
		NS("KrujevaDealer");

		KrujevaDealer.Application = Class(Base.Application, {

			pages: {
				login:  ["KrujevaDealer.UI.LoginForm", {destroyOnHide: true}],
				forgot: ["KrujevaDealer.UI.ForgotForm", {destroyOnHide: true}],
				orders: ["KrujevaDealer.UI.OrdersForm"],
				prices: ["KrujevaDealer.UI.PricesForm"],
			},

			defaultPage: 'orders',

			data: null,

			initialize: function () {

				this.ParentCall();

				this.data = new KrujevaDealer.Data.Data();
			},

			init: function(data) {

				this.setServerData(data);

				setTimeout(function () {
					KrujevaDealer.Events.init();
				}, 0);

				this.openNextPage();
			},

			setServerData: function (data) {

				DateTime.utcTime = false;

				if (!data) {
					return;
				}

				//@config
				if (data.config && data.config.timestamp) {
					ServerTime.setTimestamp(data.config.timestamp);
				}

				//@new orders
				if (data.neworders) {
					this.data.setNewOrders(data.neworders);
				}

				//@new orders
				if (data.regionbrands) {
					this.data.setRegionBrands(data.regionbrands);
				}

				//@context
				if (data.context) {
					this.security.init(data.context);

					if (data.config && data.config.timestamp) {

						setTimeout(function() {

							ServerTime.start();

						}, 3000);

					} else {

						ServerTime.start();
					}

				}

				var message = null;

				if (data.config && data.config.message) {

					message = data.config.message;

				} else if (data.message) {

					message = data.message;
				}

				if (message) {
					Message.url = message.url;

					Message.sessionId = LocalStorage.get('sid');

					if (context("user")) {

						Message.connect();
					}
				}
			},

			openNextPage: function (options) {

				var page = this.nextPage();

				var deferred = this.open(page, options);

				if (['login'].indexOf(page) == -1) {

					$.when(deferred).done(function () {

						this.showWidget("KrujevaDealer.UI.MenuForm", options);

					}.bind(this));
				}
			},

			nextPage: function () {
				var page = this.ParentCall();

				if (!context('role.id')) {

					return 'login';

				} else {

					if (page == 'login') {

						page = this.defaultPage;
					}

					return page;
				}
			}
		});
});