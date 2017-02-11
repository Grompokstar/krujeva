Module.define(
	"Events",

	function () {
		NS("UI.Plugin");

		UI.Plugin.Base = Class(Events, {
			element: null,
			options: null,

			isDestroyed: false,

			initialize: function (element, options) {
				this.element = element;
				this.options = Util.object(options);
			},

			destroy: function () {
				this.element = null;
				this.options = null;

				this.off();

				this.isDestroyed = true;

				return this;
			}
		});
});
