Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.EnumSelect = Class(UI.Widget.Forms.Control.Element, {
			createElement: function () {
				var onSelect = this.$control.attr("on-select");
				var enumClass = null;
				var attributes = {};
				var selected = this.$control.attr("selected");
				var showEmpty = this.$control.attr("show-empty") !== undefined;
				var filter = this.$control.data("filter");
				var prepends = this.$control.attr("prepend");
				var prepend = null;

				if (onSelect) {
					attributes["on-change"] = onSelect;
				}

				if (prepends) {
					prepends = prepends.split(";");

					prepend = [];

					for (var i = 0; i < prepends.length; i++) {
						var item = prepends[i].split(":");

						prepend.push({
							title: item[0],
							value: item[1]
						});
					}
				}

				if (this.$control.attr("enum")) {
					eval("enumClass = " + this.$control.attr("enum") + ";");
				}

				this.$element = UI.Template.$render("UI/Widget/Forms/Control/EnumSelect/Control", {
					enum: enumClass,
					selected: selected,
					filter: filter,
					attributes: attributes,
					prepend: prepend,
					showEmpty: showEmpty
				});
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				this.$element.on("change", function () {
					self.setValue(this.value);
				});
			},

			updateElement: function (value) {
				value = Util.coalesce(value, "");

				$("[value=\"" + value + "\"]", this.$element).prop("selected", true);
			}
		});
	}
);
