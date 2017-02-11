Module.define(
	"UI.Widget.Forms.Control.Array",
	"UI.Widget.Forms.Control.Checkbox",
	"UI.Widget.Forms.Control.Container",
	"UI.Widget.Forms.Control.DataAutocomplete",
	"UI.Widget.Forms.Control.Date",
	"UI.Widget.Forms.Control.Datepicker",
	"UI.Widget.Forms.Control.Datetimepicker",
	"UI.Widget.Forms.Control.EnumCheckbox",
	"UI.Widget.Forms.Control.EnumRadio",
	"UI.Widget.Forms.Control.EnumSelect",
	"UI.Widget.Forms.Control.Group",
	"UI.Widget.Forms.Control.Label",
	"UI.Widget.Forms.Control.ListDataAutocomplete",
	"UI.Widget.Forms.Control.Password",
	"UI.Widget.Forms.Control.ServerSourceCheckbox",
	"UI.Widget.Forms.Control.Text",
	"UI.Widget.Forms.Control.TextArea",
	"UI.Widget.Forms.Control.Timepicker",
	"UI.Widget.Forms.Control.XhrDataAutocomplete",
	"UI.Widget.Forms.Control.File",

	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.Controls = Class(UI.Widget.Forms.Control.Container, {
			$controls: function (selector) {
				return this.$("control" + selector);
			},

			eachControl: function (callback) {
				this.$("control:not([group]):not([control-bind=manual])").each(function () {
					return callback($(this));
				});
			}
		});
	}
);
