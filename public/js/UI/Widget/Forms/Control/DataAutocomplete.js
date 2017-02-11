Module.define(
	"UI.Widget.Forms.Control.Text",
	"UI.Widget.Text",
	"UI.Plugin.DataAutocomplete",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.DataAutocomplete = Class(UI.Widget.Forms.Control.Text, {
			autocomplete: null,

			insertElement: function () {
				var self = this;

				this.ParentCall();

				var source = Util.create(this.$control.attr("source"));
				var column = this.$control.attr("column");
				var columns = this.$control.attr("columns");
				var strict = !!this.$control.attr("strict");
				var handler = this.$control.attr("on-select");
				var sourceOptions = this.$control.data("source-options");
				var binding = this.$control.attr("bind");
				var bindings = null;

				if (columns) {
					columns = columns.split(",");
				}

				if (binding) {
					bindings = binding.split(";");

					for (var i = 0; i < bindings.length; i++) {
						var params = bindings[i].split(":");
						bindings[i] = {
							target: params[0],
							source: params[1]
						}
					}
				}

				this.autocomplete = this.textControl.use(UI.Plugin.DataAutocomplete, {
					source: source,
					column: column,
					columns: columns,
					strict: strict,
					sourceOptions: sourceOptions
				});

				this.autocomplete.on("select", function (args) {
					var item = args.item;
					var textValue = item ? "" + item[column] : "";

					if (bindings) {
						for (var i = 0; i < bindings.length; i++) {
							try {
								var source = bindings[i].source;
								var target = self.parseTarget(bindings[i].target);

								var value = item ? item[source] : null;

								eval("self.parent." + target + " = value;");
							} catch (e) {
								console.log("DataAutocomplete set bound value failed", target, value);
								console.dir(e);
							}
						}
					}

					self.setValue(textValue);

					if (self.parent && Util.isFunction(self.parent[handler])) {
						self.parent[handler](args);
					}
				});
			},

			updateElement: function () {
				this.ParentCall();

				this.textControl.plugin(UI.Plugin.DataAutocomplete).read();
			}
		});
	}
);
