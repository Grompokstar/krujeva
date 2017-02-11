Module.define(
	"Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Countries = Class(Data.ServerSource, {
			url: "City/Web/Countries/"
		});
});
