Module.define(
	"Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Employees = Class(Data.ServerSource, {
			url: "City/Web/Employees/",

			enters: function (options, callback) {
				return this.call("enters", options, callback);
			},

			playgrounds: function (options, callback) {
				return this.call("playgrounds", options, callback);
			},
		});
});
