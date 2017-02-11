Module.define("Events", function () {
	NS("UI");

	UI.UI = Class(Events, {
		initialize: function () {
		},

		init: function (callback) {
			if (Util.isFunction(callback)) {
				callback.call(this);
			}

			return this;
		},

		run: function (callback) {
			if (Util.isFunction(callback)) {
				callback.call(this);
			}

			return this;
		},

		destroy: function () {
			this.emit("destroy");
		}
	});
});
