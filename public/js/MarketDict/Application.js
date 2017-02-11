Module.define(
	"Base.Application",
	"DateTime",
	"Message",
	"System.NumSeq",
	"KrujevaDict.UI.LoginForm",
	"KrujevaDict.UI.BrandsForm",
	"KrujevaDict.UI.ProductCategoriesForm",
	"KrujevaDict.UI.ProductTasksForm",
	"KrujevaDict.UI.PropertiesForm",
	"KrujevaDict.UI.MenuForm",
	"KrujevaDict.UI.ProductsForm",
	"KrujevaDict.UI.DealersForm",
	"KrujevaDict.UI.UsersForm",

	function () {
		NS("KrujevaDict");

		KrujevaDict.Application = Class(Base.Application, {

			pages: {
				login: ["KrujevaDict.UI.LoginForm", {destroyOnHide: true}],
				brands: ["KrujevaDict.UI.BrandsForm", {destroyOnHide: true}],
				categories: ["KrujevaDict.UI.ProductCategoriesForm", {destroyOnHide: true}],
				tasks: ["KrujevaDict.UI.ProductTasksForm", {destroyOnHide: true}],
				properies: ["KrujevaDict.UI.PropertiesForm", {destroyOnHide: true}],
				products: ["KrujevaDict.UI.ProductsForm", {destroyOnHide: true}],
				dealers: ["KrujevaDict.UI.DealersForm", {destroyOnHide: true}],
				users: ["KrujevaDict.UI.UsersForm", {destroyOnHide: true}],
			},

			defaultPage: 'brands',

			data: null,

			init: function(data) {

				this.setServerData(data);

				this.openNextPage();

				Alert.delay = 600;

				//setTimeout(function () {
				//	City.Events.init();
				//}, 0);
			},

			setServerData: function (data) {

				DateTime.utcTime = false;

				//@config
				if (data.config && data.config.timestamp) {
					ServerTime.setTimestamp(data.config.timestamp);
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
						//Message.connect();
					}
				}
			},

			openNextPage: function (options) {

				var page = this.nextPage();

				var deferred = this.open(page, options);

				if (['login'].indexOf(page) == -1) {

					$.when(deferred).done(function () {

						this.showWidget("KrujevaDict.UI.MenuForm", options);

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
			},
		});
});