Module.define(
	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.Shortcuts = Class({
			shortcutsSwitchForm: null,

			shortcutsInit: function (options) {
				options = Util.object(options);

				this.shortcutsSwitchForm = Util.coalesce(options.switchForm, this.shortcutsSwitchForm);
			},

			shortcutsSubscribe: function () {
				var self = this;

				if (!this.shortcutsHandlers) {
					this.shortcutsHandlers = function (args) {
						if (!UI.Widget.Form.isOnTop(Util.coalesce(self.shortcutsSwitchForm, self))) {
							return;
						}

						var shortcut = [];

						if (args.shiftKey) {
							shortcut.push("Shift");
						}

						if (args.ctrlKey) {
							shortcut.push("Ctrl");
						}

						if (args.altKey) {
							shortcut.push("Alt");
						}

						shortcut.push(args.keyCode);

						shortcut = shortcut.join("+");

						var $shortcut = $("[shortcut=\"" + shortcut + "\"]");

						if ($shortcut.length) {
							var $focus = $("*:focus");

							$focus.trigger("change").blur();

							$shortcut.trigger("click");

							$focus.focus();

							args.preventDefault();
						}
					};

					$(window).on("keydown", this.shortcutsHandlers);
				}

				return this;
			},

			shortcutsUnsubscribe: function () {
				if (this.shortcutsHandlers) {
					$(window).off("keydown", this.shortcutsHandlers);

					this.shortcutsHandlers = null;
				}

				return this;
			}
		});
	}
);
