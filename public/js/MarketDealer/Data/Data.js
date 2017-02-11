Module.define(
	"Events",

	function () {
		NS("KrujevaDealer.Data");

		KrujevaDealer.Data.Data = Class(Events, {
			neworders: null,
			regionbrands: [],

			initialize: function() {
				this.ParentCall();

				KrujevaDealer.Events.on('Stats.Update', this.onStatsUpdate, this);
			},

			destroy: function () {
				KrujevaDealer.Events.off('Stats.Update', this.onStatsUpdate, this);

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