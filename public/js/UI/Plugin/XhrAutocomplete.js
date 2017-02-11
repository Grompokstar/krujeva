Module.define(
	"UI.Plugin.Autocomplete",

	function () {

	NS("UI.Plugin");

	UI.Plugin.XhrAutocomplete = Class(UI.Plugin.Autocomplete, {
		url: null,
		sourceOptions: null,

		initialize: function (element, options) {
			var self = this;

			this.Parent(element, options);

			if (options.sourceOptions) {
				this.sourceOptions = options.sourceOptions;
			}

			this.url = this.source;

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

				Xhr.call(self.url, {
					options: options
				}, function (response) {
					if (response.success) {
						callback(response.data.items);
					}
				});
			}
		}
	});
});
