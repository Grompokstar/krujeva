Module.define(
	"Base.Application",
	"DateTime",
	"Message",
	"City.Events",
	"City.UI.MenuForm",
	"City.UI.LoginForm",
	"City.UI.PlaygroundsForm",
	"City.UI.StatisticsForm",
	"City.UI.EmployeesForm",
	"City.UI.MailForm",
	"City.UI.CitiesForm",
	"City.UI.FranchiseesForm",
	"City.UI.StatisticsForm",
	"City.UI.ProfileForm",

	function () {
		NS("City");

		City.Application = Class(Base.Application, {

			pages: {
				login: ["City.UI.LoginForm", {destroyOnHide: true}],
				playgrounds: ["City.UI.PlaygroundsForm"],
				stats: ["City.UI.StatisticsForm"],
				employees: ["City.UI.EmployeesForm"],
				mail: ["City.UI.MailForm"],
				cities: ["City.UI.CitiesForm"],
				franchisees: ["City.UI.FranchiseesForm"],
				statistics: ["City.UI.StatisticsForm"],
				profile: ["City.UI.ProfileForm", {destroyOnHide: true}]
			},

			defaultPage: 'playgrounds',

			data: null,

			init: function(data) {
				this.ParentCall();

				this.setServerData(data);

				this.openNextPage();

				setTimeout(function () {
					City.Events.init();
				}, 0);
			},

			setServerData: function (data) {

				DateTime.utcTime = false;

				//@config
				if (data.config) {
					ServerTime.setTimestamp(data.config.timestamp);
				}

				//@context
				if (data.context) {
					this.security.init(data.context);

					ServerTime.start();
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

						this.showWidget("City.UI.MenuForm", options);

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