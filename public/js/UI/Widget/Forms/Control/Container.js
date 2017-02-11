Module.define(
	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Container = Class({
			controls: [],
			$controlsContainer: null,

			$controls: function (selector) {
				return $("control" + selector, this.$controlsContainer);
			},

			eachControl: function (callback) {
				$("control:not([group]):not([control-bind=manual])", this.$controlsContainer).each(function () {
					return callback($(this));
				});
			},

			controlClass: function ($control) {
				var type = $control.attr("type");

				type = type[0].toUpperCase() + type.substr(1);

				type = type.replace(/-\w/g, function (match) {
					return match.substr(1).toUpperCase();
				});

				return "UI.Widget.Forms.Control." + type;
			},

			createControl: function ($control) {
				return Util.create(this.controlClass($control), [this, $control]);
			},

			findControl: function ($control, recursive) {
				$control = $($control);
				recursive = Util.coalesce(recursive, true);

				var element = $control[0];
				var control = null;

				for (var i = 0; i < this.controls.length; i++) {
					if (this.controls[i].control == element) {
						control = this.controls[i];
						break;
					} else if (recursive) {
						if (this.controls[i].Is(UI.Widget.Forms.Control.Container)) {
							if (control = this.controls[i].findControl($control)) {
								break;
							}
						}
					}
				}

				return control;
			},

			bindControl: function ($control) {
				var self = this;

				if (!$control) {
					this.eachControl(function ($control) {
						self.bindControl($control);
					});
				} else {
					var control = this.createControl($control);

					control.bind();

					control.once("unbind", function () {
						var index = self.controls.indexOf(control);

						if (~index) {
							self.controls.splice(index, 1);
						}
					});

					this.controls.push(control);
				}
			},

			unbindControl: function ($control) {
				var self = this;

				if (!$control) {
					this.eachControl(function ($control) {
						self.unbindControl($control);
					});
				} else {
					var control = this.findControl($control);

					if (control) {
						control.unbind();
					}
				}
			},

			updateControl: function ($control) {
				var self = this;

				if (!$control) {
					this.eachControl(function ($control) {
						self.updateControl($control);
					});
				} else {
					var control = this.findControl($control);

					if (control) {
						control.update();
					}
				}
			},

			rebindControl: function ($control, update) {
				update = Util.coalesce(update, true);

				this.unbindControl($control);
				this.bindControl($control);

				if (update) {
					this.updateControl($control);
				}
			},

			controlGroupIndex: function (element, group) {
				var $element = $(element);

				if (!group) {
					group = $element.data("group");
				}

				if (!group) {
					return -1;
				}

				var selector = "[control-group=\"" + group + "\"]";
				var $group = $element.parentsUntil(selector).last().parent();

				return $group.parent().children().index($group);
			},

			onControlPreSetValue: function (args) {
			},

			onControlSetValue: function (args) {
			}
		});
	}
);
