Module.define(
	"Base.Application",
	"DateTime",
	"Message",
	"KrujevaBonus.Events",
	"System.NumSeq",
	"KrujevaBonus.UI.LoginForm",
	"KrujevaBonus.UI.ForgotForm",
	"KrujevaBonus.UI.OrdersForm",
	"KrujevaBonus.UI.OrdersForm",
	"KrujevaBonus.UI.MenuForm",
	"KrujevaBonus.UI.PricesForm",
	"KrujevaBonus.UI.Widget.ConfirmOrder",

	function () {
		NS("KrujevaBonus");

		KrujevaBonus.Application = Class(Base.Application, {

			pages: {
				login:  ["KrujevaBonus.UI.LoginForm", {destroyOnHide: true}],
				forgot: ["KrujevaBonus.UI.ForgotForm", {destroyOnHide: true}],
				orders: ["KrujevaBonus.UI.OrdersForm"],
			},

			defaultPage: 'orders',

			data: null,

			initialize: function () {

				this.ParentCall();

				this.data = new KrujevaBonus.Data.Data();
			},

			init: function(data) {

				this.setServerData(data);

				setTimeout(function () {
					KrujevaBonus.Events.init();
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

						this.showWidget("KrujevaBonus.UI.MenuForm", options);

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