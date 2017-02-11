Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.EnumRadio = Class(UI.Widget.Forms.Control.Element, {
			createElement: function () {
				var enumClass;
				var attributes = {};
				var onClick = this.$control.attr("on-click");

				if (onClick) {
					attributes["on-click"] = onClick;
				}

				eval("enumClass = " + this.$control.attr("enum") + ";");

				this.$element = UI.Template.$render("UI/Widget/Forms/Control/EnumRadio/Control", {
					enum: enumClass,
					attributes: attributes
				});
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				$("input", this.$element).on("click", function () {
					self.setValue(this.value);
				});
			},

			updateElement: function (value) {
				$("[value=\"" + value + "\"]", this.$element).attr("checked", true);
			}
		});
	}
);
