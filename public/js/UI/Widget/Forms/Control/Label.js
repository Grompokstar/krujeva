Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Label = Class(UI.Widget.Forms.Control.Element, {
			createElement: function () {
				this.$element = $("<label>");
			}
		});
	}
);
