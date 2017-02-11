Module.define(
	"UI.Widget.Forms.Control.Text",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Timepicker = Class(UI.Widget.Forms.Control.Text, {
			insertElement: function () {
				this.ParentCall();

				this.$element.timepicker();
			}
		});
	}
);
