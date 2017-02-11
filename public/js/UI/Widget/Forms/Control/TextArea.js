Module.define(
	"UI.Widget.Forms.Control.Input",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.TextArea = Class(UI.Widget.Forms.Control.Input, {
			createElement: function () {
				this.$element = $("<textarea></textarea>");
			}
		});
	}
);
