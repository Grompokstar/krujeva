Module.define(
	"GIS.UI.Control.Base",
	"UI.Util.Popup",
	"UI.Util.DOM",

	function () {
		NS("GIS.UI.Control");

		GIS.UI.Control.Layers = Class(GIS.UI.Control.Base, {
			template: "GIS/Control/Layers",
			$control: null,
			$selector: null,
			hover: null,
			base: {},
			layers: {},
			widgets: {},

			initialize: function (map, options) {
				this.Parent(map, options);

				this.render();
			},

			destroy: function () {
				if (this.hover) {
					UI.Util.Popup.destroyHover(this.hover);
					this.hover = null;
				}

				Util.each(this.widgets, function (widget) {
					widget.destroy();
				});

				this.$selector = null;
				this.$control.remove();

				return this.ParentCall();
			},

			render: function () {
				var self = this;

				this.$control = UI.Template.$render(this.template, { control: this });
				this.$control.appendTo(this.map.$element);
				this.$selector = $("[name=selector]", this.$control);
				this.$selector.hide();

				this.$control.on("mouseenter", function (args) {
					var elements = [];

					Util.each(self.widgets, function (widget) {
						var element = widget.element;

						if (element) {
							elements.push(element);
						}
					});

					for (var i = 0; i < elements.length; i++) {
						if (UI.Util.DOM.isParent(args.target, elements[i])) {
							return;
						}
					}

					if (!self.hover) {
						self.$selector.show();

						self.hover = UI.Util.Popup.createHover(self.$control, self.$selector, function () {
							self.$selector.hide();
							self.hover = null;
						});
					}
				});

				UI.Element.initEvent("click", "on-click", this.$control, this);

				this.refreshControls();
			},

			refreshControls: function () {
				var self = this;

				Util.each(this.base, function (options, name) {
					var layer = options.layer;

					if (layer) {
						self.$control.find("[base='" + name + "'] input[type=radio]", self.$control).prop("checked", self.layerVisible(layer));
					}
				});

				Util.each(this.layers, function (options, name) {
					var layer = options.layer;

					if (layer) {
						self.$control.find("[layer='" + name + "'] input[type=checkbox]", self.$control).prop("checked", self.layerVisible(layer));
					}
				});
			},

			onBaseSelect: function (caller) {
				var self = this;

				var selected = $(caller).data("base");

				Util.each(this.base, function (options, name) {
					var layer = options.layer;

					if (name == selected) {
						self.layerShow(layer);
					} else {
						self.layerHide(layer);
					}
				});
			},

			onLayerCheck: function (caller) {
				var name = $(caller).data("layer");
				var checked = $(caller).prop("checked");

				var layer = this.layers[name].layer;

				if (this.layerVisible(layer)) {
					this.layerHide(layer);
				} else {
					this.layerShow(layer);
				}
			},

			onWidgetClick: function (caller) {
				var self = this;

				var name;
				var options;

				if ($(caller).data("base")) {
					name = $(caller).data("base");
					options = this.base[name];
				} else {
					name = $(caller).data("layer");
					options = this.layers[name];
				}

				var layer = options.layer;

				if (options && options.widget && layer) {
					this.$selector.hide();

					if (this.hover) {
						UI.Util.Popup.destroyHover(this.hover);
						this.hover = null;
					}

					if (this.widgets[name]) {
						this.widgets[name].destroy();
					}

					var widget = new options.widget(null, {
						layer: layer
					});

					widget.render().appendTo(this.$control);

					widget.on("destroy", function () {
						delete self.widgets[name];
					});

					this.widgets[name] = widget;

					return false;
				}
			},

			layerVisible: function (layer) {
				return this.map.map.hasLayer(layer);
			},

			layerShow: function (layer) {
				if (!this.layerVisible(layer)) {
					this.map.addLayer(layer);
				}
			},

			layerHide: function (layer) {
				if (this.layerVisible(layer)) {
					this.map.removeLayer(layer);
				}
			}
		});
	}
);
