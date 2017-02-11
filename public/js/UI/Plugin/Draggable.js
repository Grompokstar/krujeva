Module.define(
	"UI.Plugin.Base",

	function () {
		NS("UI.Plugin");

		UI.Plugin.Draggable = Class(UI.Plugin.Base, {
			handle: null,
			callback: null,
			backups: [],

			initialize: function () {
				var self = this;

				this.ParentCall();

				if (!this.options.handle) {
					this.options.handle = this.element.$element;
				}

				this.handle = $(this.options.handle);

				this.backupCss(this.element.$element, ["position", "left", "top"]);
				this.backupCss(this.handle, ["cursor"]);

				this.element.$element.css("position", "absolute");
				this.handle.css("cursor", "move");

				this.callback = function (args) {
					if (!~["SELECT", "INPUT", "BUTTON"].indexOf(args.target.tagName)) {
						self.Class.element = self.element;
						self.Class.dragging = true;
					}
				};

				this.handle.on("mousedown", this.callback);
			},

			destroy: function () {
				this.handle.off("mousedown", this.callback);

				this.restoreCss();

				if (this.Class.element == this.element) {
					this.Class.element = null;
				}

				this.handle = null;
				this.callback = null;

				this.ParentCall();

				return this;
			},

			backupCss: function ($element, properties) {
				var backup = {
					$element: $element,
					properties: {}
				};

				for (var i = 0; i < properties.length; i++) {
					var name = properties[i];

					backup.properties[name] = $element.css(name);
				}

				this.backups.push(backup);

				return this;
			},

			restoreCss: function () {
				for (var i = 0; i < this.backups.length; i++) {
					var backup = this.backups[i];

					Util.each(backup.properties, function (value, name) {
						backup.$element.css(name, value);
					});
				}

				this.backups = [];
			}
		});

		UI.Plugin.Draggable.Static({
			dragging: false,
			element: null,
			position: null,

			initialize: function () {
				var self = this;

				var element = $(window);

				element.on("mousedown", function (args) {
					self.position = self.getPosition(args);

					if (self.dragging) {
						$("body").css("-webkit-user-select", "none");
					}
				});

				element.on("mousemove", function (args) {
					if (self.dragging && self.element) {
						var position = self.getPosition(args);

						var diff = {
							x: position.x - self.position.x,
							y: position.y - self.position.y
						};

						self.position = position;

						var pos = self.element.$element.position();

						self.element.$element.css("left", pos.left + diff.x);
						self.element.$element.css("top", pos.top + diff.y);
					}
				});

				element.on("mouseup", function () {

					if (self.dragging) {
						$("body").css("-webkit-user-select", "auto");
					}

					self.dragging = false;
					self.element = null;
				});
			},

			getPosition: function (args) {
				return {
					x: args.clientX,
					y: args.clientY
				};
			}
		});
	}
);
