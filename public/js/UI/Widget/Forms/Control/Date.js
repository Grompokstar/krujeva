Module.define(
	"UI.Widget.Forms.Control.Input",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Date = Class(UI.Widget.Forms.Control.Input, {
			createElement: function () {
				this.$element = $("<input type='date'>");
			}
		});
	}
);
