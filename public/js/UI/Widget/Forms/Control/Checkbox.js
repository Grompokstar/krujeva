Module.define(
	"UI.Widget.Forms.Control.Input",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Checkbox = Class(UI.Widget.Forms.Control.Input, {
			createElement: function () {
				this.$element = $("<input type='checkbox'>");
			},

			updateElement: function (value) {
				this.$element.prop("checked", !!value);
			},

			listenElement: function () {
				var self = this;

				this.$element.on("click", function () {
					self.setValue($(this).is(":checked"));
				});
			}
		});
	}
);
