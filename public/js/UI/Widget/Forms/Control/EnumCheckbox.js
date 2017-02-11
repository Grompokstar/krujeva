Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.EnumCheckbox = Class(UI.Widget.Forms.Control.Element, {
			createElement: function () {
				var enumClass;
				var attributes = {};
				var onClick = this.$control.attr("on-click");
				var multiline = this.$control.attr("multiline");

				if (multiline == "no") {
					multiline = false;
				}

				multiline = !!Util.coalesce(multiline, true);

				if (onClick) {
					attributes["on-click"] = onClick;
				}

				eval("enumClass = " + this.$control.attr("enum") + ";");

				this.$element = UI.Template.$render("UI/Widget/Forms/Control/EnumCheckbox/Control", {
					enum: enumClass,
					attributes: attributes,
					multiline: multiline
				});
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				$("input", this.$element).on("click", function () {
					var value = [];

					$("input", self.$element).each(function () {
						if ($(this).is(":checked")) {
							value.push(this.value);
						}
					});

					self.setValue(value);
				});
			},

			updateElement: function (value) {
				if (!Util.isArray(value)) {
					value = [];
				}

				$("input", this.$element).each(function () {
					$(this).prop("checked", !!~value.indexOf(+this.value));
				});
			}
		});
	}
);
