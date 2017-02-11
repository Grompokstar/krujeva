Module.define(
	"UI.Widget.Forms.Control.Base",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Element = Class(UI.Widget.Forms.Control.Base, {
			$element: null,

			bind: function () {
				this.createElement();
				this.insertElement();
				this.applyAttributes();

				this.ParentCall();
			},

			unbind: function () {
				this.removeElement();

				this.ParentCall();
			},

			update: function () {
				this.updateElement(this.getValue());

				this.ParentCall();
			},

			createElement: function () {
				this.$element = $("<span>");
			},

			insertElement: function () {
				this.$element.insertAfter(this.$control);
			},

			updateElement: function (value) {
				this.$element.text(Util.isDefined(value) ? value : null);
			},

			removeElement: function () {
				this.$element.remove();
				this.$element = null;
			},

			applyAttributes: function () {
				if (this.control) {
					var attributes = this.control.attributes;
					var regexp = /^html-(.*)$/;

					for (var i = 0, count = attributes.length; i < count; i++) {
						var attr = attributes[i].nodeName;
						var match;

						if (match = regexp.exec(attr)) {
							var attribute = match[1];

							this.$element.attr(attribute, this.$control.attr(attr));
						}
					}
				}
			}
		});
	}
);
