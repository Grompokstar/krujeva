Module.define(
	"KrujevaBonus.Data.ServerSource",

	function () {
		NS("KrujevaBonus.Data");

		KrujevaBonus.Data.BonusOrders = Class(KrujevaBonus.Data.ServerSource, {
			url: "/BonusOrders/",

			bonuslist: function (options, callback) {
				return this.call("bonuslist", options, callback);
			},

            bonusget: function (options, callback) {
                return this.call("bonusget", options, callback);
            },

			bonuscancel: function (options, callback) {
				return this.call("bonuscancel", options, callback);
			},

			bonusconfirm: function (options, callback) {
				return this.call("bonusconfirm", { item: JSON.stringify(options) }, callback);
			}

		});
});