Module.define(
	"UI.Widget.Forms.Control.Base",
	"UI.Widget.Forms.Control.Container",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Group = Class(UI.Widget.Forms.Control.Container, UI.Widget.Forms.Control.Base, {
			controls: [],

			bind: function () {
				var self = this;
				var list = this.getValue();

				if (Util.isArray(list)) {
					var template = this.$control.attr("template");

					for (var i = 0; i < list.length; i++) {
						UI.Template.$render(template, { widget: this.parent, index: i }).appendTo(this.$control);
					}

					this.eachControl(function ($control) {
						self.bindControl($control);
					});
				}

				this.ParentCall();
			},

			unbind: function () {
				var self = this;

				this.eachControl(function ($control) {
					self.unbindControl($control);
				});

				this.ParentCall();
			},

			update: function () {
				var self = this;

				this.eachControl(function ($control) {
					self.updateControl($control);
				});

				this.ParentCall();
			},

			createControl: function ($control) {
				return Util.create(this.controlClass($control), [this.parent, $control]);
			},

			eachControl: function (callback) {
				this.$control.children("[control-group]:not([control-bind=manual])").each(function () {
					var $group = $(this);
					var group = $group.attr("control-group");

					$("control[group=" + group + "]", $group).each(function () {
						return callback($(this));
					});
				});
			}
		});
	}
);
