Module.define(
	"UI.Widget.Forms.Control.Element",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Input = Class(UI.Widget.Forms.Control.Element, {
			bind: function () {
				this.ParentCall();

				this.listenElement();
			},

			createElement: function () {
				this.$element = $("<input>");
			},

			updateElement: function (value) {
				this.$element.val(Util.isDefined(value) ? value : null);
			},

			listenElement: function () {
				var self = this;

				this.$element.on("change", function () {
					self.setValue(this.value);
				});
			}
		});
	}
);
