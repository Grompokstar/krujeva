Module.define(
	"UI.Widget.Form",
	"Security.AccessMode",

	function () {
		NS("Security.UI.Admin");

		Security.UI.Admin.RoleKeysForm = Class(UI.Widget.Form, {
			formTemplate: "Security/Admin/RoleKeysForm",

			draggable: false,

			item: null,

			styles: ["form__splash"],

			initialize: function (parent, options) {
				this.override(["item"], options);

				this.ParentCall();
			},

			render: function () {
				this.ParentCall();

				return this;
			},

			onModeClick: function (caller) {
				var value = +$(caller).val();
				var on = $(caller).is(":checked");
				var keyIndex = +$(caller).data("column");
				var mode = pgIntArrayDecode(this.item.access[keyIndex].mode);

				if (on) {
					if (!~mode.indexOf(value)) {
						mode.push(value);
					}
				} else {
					var index = mode.indexOf(value);

					if (~index) {
						mode.splice(index, 1);
					}
				}

				this.item.access[keyIndex].mode = pgIntArrayEncode(mode);
			},

			onSaveClick: function () {
				this.emit("save", {
					item: this.item
				});
			},

			onSaveCloseClick: function () {
				this.emit("save", {
					item: this.item,
					close: true
				});
			}
		});
	}
);
