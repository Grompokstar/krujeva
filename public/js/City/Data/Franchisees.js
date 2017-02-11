Module.define(
	"Data.ServerSource",

	function () {
		NS("City.Data");

		City.Data.Franchisees = Class(Data.ServerSource, {
			url: "City/Web/Franchisees/"
		});
});
