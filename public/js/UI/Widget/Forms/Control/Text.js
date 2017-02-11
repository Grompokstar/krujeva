Module.define(
	"UI.Widget.Text",
	"UI.Widget.Forms.Control.Input",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Text = Class(UI.Widget.Forms.Control.Input, {
			textControl: null,

			createElement: function () {
				var attributes = this.getControlAttributes();

				Util.merge(attributes, {type: "text"});

				this.$element = $("<input/>", attributes);
			},

			getControlAttributes: function() {
				var listAttributes = ["style", "placeholder", "name", "class", "on-change", "on-keyup", "value"];
				var controlAttributes = {};

				Util.each(listAttributes, function(attr) {

					var valueAttribute = this.$control.attr(attr);

					if (valueAttribute) {
						controlAttributes[attr] = valueAttribute;
					}

				}.bind(this));

				return controlAttributes;
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				this.textControl = new UI.Widget.Text(this.parent, {
					element: this.$element
				});

				var onLiveChange = this.$control.attr("on-live-change");

				if (onLiveChange) {
					this.textControl.liveChange().on("livechange", function (args) {
						if (self.parent && Util.isFunction(self.parent[onLiveChange])) {
							self.parent[onLiveChange](args);
						}
					});
				}
			},

			removeElement: function () {
				this.textControl.unlink();
				this.textControl.destroy();

				this.textControl = null;

				this.ParentCall();

			}
		});
	}
);
