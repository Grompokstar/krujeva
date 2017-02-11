Module.define(
	"Events",

	function () {
		NS("KrujevaBonus.Data");

		KrujevaBonus.Data.Data = Class(Events, {
			neworders: null,
			regionbrands: [],

			initialize: function() {
				this.ParentCall();

				KrujevaBonus.Events.on('Stats.Update', this.onStatsUpdate, this);
			},

			destroy: function () {
				KrujevaBonus.Events.off('Stats.Update', this.onStatsUpdate, this);

				this.ParentCall();
			},

			onStatsUpdate: function (args) {

				if (args.stats) {
					this.setNewOrders(args.stats.neworders);
				}
			},

			setNewOrders: function (count) {
				this.neworders = count;
				this.emit('neworders.change', {count: count});
			},

			setRegionBrands: function (regionbrands) {
				this.regionbrands = regionbrands;
			}
		});
});