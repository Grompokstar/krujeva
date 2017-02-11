Module.define(
	"UI.Plugin.Autocomplete",
	"Data.Source",

	function () {

	NS("UI.Plugin");

	UI.Plugin.DataAutocomplete = Class(UI.Plugin.Autocomplete, {
		dataSource: null,
		sourceOptions: null,
		sourceArgs: null,

		initialize: function (element, options) {
			var self = this;

			this.Parent(element, options);

			if (options.sourceOptions) {
				this.sourceOptions = options.sourceOptions;
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

					var sourceArgs = Util.merge(Xhr.args(self.sourceArgs), {
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
		}
	});
});
