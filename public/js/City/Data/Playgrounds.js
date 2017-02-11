Module.define(
	"City.Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Playgrounds = Class(City.Data.ServerSource, {
			url: "City/Web/Playgrounds/",

			statistics: function (options, callback) {
				return this.call("statistics", options, callback);
			},

			statisticsall: function (options, callback) {
				return this.call("statisticsall", options, callback);
			},
		});
});
