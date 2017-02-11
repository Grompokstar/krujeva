Module.define(
	"Events",
	"UI.Plugin.Base",

	function () {
	NS("UI");

	UI.Element = Class(Events, {
		element: null,
		$element: null,
		plugins: [],
		options: null,

		isRendered: false,
		isDestroyed: false,

		initialize: function (options) {
			this.options = Util.object(options);

			if (this.options.element) {
				this.html(this.options.element);
			} else if (this.options.html) {
				this.html(this.options.html);
			}
		},

		destroy: function () {
			this.remove();

			this.isDestroyed = true;

			this.emit("destroy");

			this.off();

			return this;
		},

		render: function (options) {
			options = Util.object(options);

			if (options.html) {
				this.html(options.html);
			}

			return this;
		},

		remove: function () {
			this.unuse();

			if (this.$element) {
				this.$element.remove();
			}

			this.isRendered = false;

			this.$element = null;
			this.element = null;

			this.emit("remove");

			return this;
		},

		unlink: function () {
			this.unuse();

			this.isRendered = false;

			this.$element = null;
			this.element = null;

			this.emit("unlink");

			return this;
		},

		html: function (html) {
			if (!html) {
				return this.element ? this.element.outerHTML : null;
			} else {
				this.unuse();

				var element = $(html);

				if (this.element) {
					this.$element.replaceWith(element);
					this.$element.remove();
				}

				this.$element = element;
				this.element = this.$element[0];

				this.isRendered = true;

				return this;
			}
		},

		innerHtml: function () {
			return this.element ? this.element.innerHTML : null;
		},

		outerHtml: function () {
			return this.element ? this.element.outerHTML : null;
		},

		show: function () {
			if (this.$element) {
				this.$element.show();
			}

			return this;
		},

		hide: function () {
			if (this.$element) {
				this.$element.hide();
			}

			return this;
		},

		toggle: function () {
			if (this.visible()) {
				this.hide();
			} else {
				this.show();
			}

			return this;
		},

		visible: function () {
			if (this.$element) {
				return this.$element.is(":visible");
			} else {
				return false;
			}
		},

		position: function (position) {
			if (!this.$element) {
				return false;
			}

			if (position) {
				if (Util.isObject(position)) {
					if (position.left !== undefined) {
						this.$element.css("left", position.left);
					}

					if (position.top !== undefined) {
						this.$element.css("top", position.top);
					}
				}

				return true;
			} else {
				return {
					left: this.$element.css("left"),
					top: this.$element.css("top")
				}
			}
		},

		appendTo: function (target) {
			if (this.$element) {
				this.$element.appendTo(this.Class.$element(target));
			}

			return this;
		},

		prependTo: function (target) {
			if (this.$element) {
				this.$element.prependTo(this.Class.$element(target));
			}

			return this;
		},

		append: function (element) {
			if (this.$element) {
				this.$element.append(this.Class.$element(element));
			}

			return this;
		},

		prepend: function (element) {
			if (this.$element) {
				this.$element.prepend(this.Class.$element(element));
			}

			return this;
		},

		$: function (selector, nullable) {
			var element = this.$element ? $(selector, this.$element) : $();

			if (nullable && element && !element.length) {
				element = null;
			}

			return element;
		},

		$name: function (name, nullable) {
			return this.$("[name='" + name + "']", nullable);
		},

		use: function (classObject, options) {
			var plugin = null;

			if (classObject.Is(UI.Plugin.Base)) {
				plugin = new classObject(this, options);

				this.plugins.push(plugin);
			}

			return plugin;
		},

		unuse: function (classObject) {
			for (var i = 0; i < this.plugins.length; i++) {
				if (!classObject || this.plugins[i].Is(classObject)) {
					this.plugins[i].destroy();
					this.plugins.splice(i, 1);
					i--;
				}
			}

			return this;
		},

		plugin: function (classObject) {
			for (var i = 0; i < this.plugins.length; i++) {
				if (this.plugins[i].Is(classObject)) {
					return this.plugins[i];
				}
			}

			return null;
		},

		container: function () {
			return this.$element.parent();
		},

		setting: function (name, value) {
			value = Util.coalesce(value, this[name], this.Class[name]);

			if (value === null || value === undefined) {
				var parents = this.Class.Parents;

				for (var i = parents.length - 1; i >= 0; i--) {
					var val = parents[i][name];

					if (val !== null && val !== undefined) {
						return val;
					}
				}
			}

			return value;
		}
	});

	UI.Element.Static({

		$element: function (element) {
			if (Class.Is(element, UI.Element)) {
				return element.$element;
			}

			return $(element);
		},

		initEvent: function (event, name, element, object) {
			var selector = "[" + name + "]";

			var callback = function (args) {
				var handler = $(this).attr(name);

				if (Util.isFunction(object[handler])) {
					if (object[handler].call(object, this, args) === false) {
						args.stopPropagation();
					}
				}
			};

			if (element) {
				element.filter(selector).on(event, callback);
				element.on(event, selector, callback);
			}
		},

		relativePosition: function (element, relativeTo, position) {
			var $element = $(element);
			var $relativeTo = $(relativeTo);
			var elementOffset = $element.offset();
			var relativeOffset = $relativeTo.offset();
			var relativePosition = {
				left: elementOffset.left - relativeOffset.left,
				top: elementOffset.top - relativeOffset.top
			};

			if (position) {
				relativePosition.left += position.left;
				relativePosition.top += position.top;
			}

			return relativePosition;
		}
	});
});
