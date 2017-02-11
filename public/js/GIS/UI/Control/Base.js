Module.define(
	"Events",

	function () {
		NS("GIS.UI.Control");

		GIS.UI.Control.Base = Class(Events, {
			map: null,

			initialize: function (map, options) {
				Util.merge(this, options);

				this.map = map;
			},

			destroy: function () {
				this.emit("destroy");
			}
		});
	}
);
