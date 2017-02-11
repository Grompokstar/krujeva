Module.define(
	"Events",

	function () {
		NS("GIS.UI.Layer");

		GIS.UI.Layer.Base = Class(Events, {
			map: null,

			initialize: function (map) {
				this.map = map;

				this.map.on("destroy", this.onMapDestroy, this);
			},

			destroy: function () {
				this.map.off("destroy", this.onMapDestroy, this);

				this.map = null;

				this.emit("destroy");
			},

			onMapDestroy: function () {
				this.destroy();
			},

			override: function (fields, options) {
				var current = {};
				var i;

				for (i = 0; i < fields.length; i++) {
					current[fields[i]] = this[fields[i]];
				}

				options = Util.merge(current, options);

				for (i = 0; i < fields.length; i++) {
					this[fields[i]] = options[fields[i]];
				}
			}
		});
	}
);
