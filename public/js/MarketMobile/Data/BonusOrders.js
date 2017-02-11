Module.define(
	"KrujevaMobile.Data.ServerSource",

	function () {
		NS("KrujevaMobile.Data");

		KrujevaMobile.Data.BonusOrders = Class(KrujevaMobile.Data.ServerSource, {
			url: "/BonusOrders/",

			addorder: function (item, callback) {
				return this.call("addorder", { item: JSON.stringify(item) }, callback);
			}
		});
});
