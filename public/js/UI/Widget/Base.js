Module.define(
	"UI.Template",
	"UI.Element",

	function () {

		NS("UI.Widget");

		UI.Widget.Base = Class(UI.Element, {
			parent: null,
			template: null,

			css: {},
			styles: [],

			initialize: function (parent, options) {
				var self = this;

				this.override(["template", "css", "styles"], options);

				this.parent = parent;

				this.Parent(options);

				this.subscribeWindowResize();

				if (this.parent && Class.Is(this.parent, Events)) {
					this.parent.on("destroy", this.onParentDestroy, this);
				}
			},

			destroy: function () {
				if (this.parent && Class.Is(this.parent, Events)) {
					this.parent.off("destroy", this.onParentDestroy, this);
				}

				this.unsubscribeWindowResize();

				this.ParentCall();

				return this;
			},

			render: function (options) {
				var self = this;

				options = Util.object(options);

				var template = this.setting("template", options["template"]);

				if (!template) {
					throw "Widget with no template";
				}

				this.unuse();

				var data = Util.object(options["data"]);

				data.widget = this;

				this.Parent({ html: UI.Template.render(template, data) });

				if (this.css) {
					Util.each(this.css, function (value, name) {
						self.$element.css(name, value);
					});
				}

				if (this.styles) {
					Util.each(this.styles, function (name) {
						self.$element.addClass(name);
					});
				}

				setTimeout(function () {
					self.initInteractionEvents();
					self.align()
				}, 0);

				return this;
			},

			initInteractionEvents: function () {
				this.Class.initEvent("click", "on-click", this.$element, this);
				this.Class.initEvent("mouseover", "on-mouseover", this.$element, this);
				this.Class.initEvent("mouseout", "on-mouseout", this.$element, this);
				this.Class.initEvent("change", "on-change", this.$element, this);
				this.Class.initEvent("keyup", "on-keyup", this.$element, this);
				this.Class.initEvent("keydown", "on-keydown", this.$element, this);
				this.Class.initEvent("contextmenu", "on-contextmenu", this.$element, this);
			},

			override: function (fields, options) {
				var current = {};
				var i;

				for (i = 0; i < fields.length; i++) {
					current[fields[i]] = this[fields[i]];
				}

				options = Util.merge(current, options);

				for (i = 0; i < fields.length; i++) {
					this[fields[i]] = options[fields[i]];
				}
			},

			align: function () {
			},

			subscribeWindowResize: function () {
				var self = this;

				this.windowResizeHandler = function () {
					self.onWindowResize();
				};

				$(window).on("resize", this.windowResizeHandler);
			},

			unsubscribeWindowResize: function () {
				if (this.windowResizeHandler) {
					$(window).off("resize", this.windowResizeHandler);

					this.windowResizeHandler = null;
				}
			},

			onWindowResize: function () {
				if (this.isRendered) {
					this.align();
				}
			},

			onParentDestroy: function () {
				this.destroy();
			}
		});

		UI.Widget.Base.Static({
			template: null
		});
	}
);
