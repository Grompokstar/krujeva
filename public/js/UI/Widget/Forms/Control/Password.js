Module.define(
	"UI.Widget.Forms.Control.Input",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Password = Class(UI.Widget.Forms.Control.Input, {
			createElement: function () {
				this.$element = $("<input type='password'>");
			}
		});
	}
);
