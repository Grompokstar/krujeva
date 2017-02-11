Module.define("UI.Plugin.Autocomplete", "Data.Source",

	function () {

		NS("OSM.Plugin");

		OSM.Plugin.DataAutocomplete = Class(UI.Plugin.Autocomplete, {
			dataSource: null,
			sourceOptions: null,
			sourceArgs: null,
			cssClass: null,
			selectedItem: null,

			initialize: function (element, options) {
				var self = this;

				this.blurHandler = function () {
					self.input.val(self.actualValue);
					self.input.trigger("change");
					self.hide();
				};

				this.Parent(element, options);

				if (options.sourceOptions) {
					this.sourceOptions = options.sourceOptions;
				}

				if (options.cssClass) {
					this.cssClass = options.cssClass;
				}

				if (Class.Is(this.source, Data.Source)) {
					this.dataSource = this.source;

					this.source = function (args, callback) {
						var options = {};

						if (self.sourceOptions) {
							var sourceOptions = null;

							if (Util.isFunction(self.sourceOptions)) {
								sourceOptions = self.sourceOptions();
							} else {
								sourceOptions = self.sourceOptions;
							}

							if (sourceOptions && Util.isObject(sourceOptions)) {
								options = Util.merge(options, sourceOptions);
							}
						}

						options = Util.merge(options, {
							autocomplete: true,
							pattern: args.pattern
						});

						var sourceArgs = Util.merge(Xhr.args(self.formSourceArgs()), {
							autocomplete: true,
							pattern: args.pattern
						});

						sourceArgs = Util.merge(sourceArgs, {
							options: options
						});

						self.dataSource.find(sourceArgs, function (result) {
							if (result) {
								callback(result.items);
							}
						});
					}
				}

				this.on("select", function(args) {
					if (!args.item) {
						self.selectedItem = null;
						return;
					}

					self.selectedItem = args.item;
				});

				this.on("empty", function () {
					self.selectedItem = null;
				});
			},

			formSourceArgs: function () {
				return Util.merge(Xhr.args(this.sourceArgs), {
					selectedItem: this.selectedItem
				});
			},

			align: function () {
				var offset = this.input.offset();
				var height = this.input.outerHeight();

				var css = {
					top : offset.top + height,
					left: offset.left
				};

				if (this.input[0].offsetWidth > 200) {
					css['width'] = this.input[0].offsetWidth;
				}

				if (this.cssClass) {
					this.div.addClass(this.cssClass);
				}

				this.div.css(css);
			}
		});
	});
