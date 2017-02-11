Module.define(
	"UI.Widget.Forms.Control.Text",
	"UI.Widget.Text",
	"UI.Plugin.XhrAutocomplete",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.XhrDataAutocomplete = Class(UI.Widget.Forms.Control.Text, {
			textControl: null,

			insertElement: function () {
				var self = this;

				this.ParentCall();


				this.textControl = new UI.Widget.Text(this, {
					element: this.$element
				});

				var source =this.$control.attr("url");
				var column = this.$control.attr("column");
				var columns = this.$control.attr("columns").split(",");
				var strict = !!this.$control.attr("strict");
				var handler = this.$control.attr("on-select");
				var sourceOptions = this.$control.data("source-options");
				var binding = this.$control.attr("bind");
				var bindings = null;

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

				this.textControl.use(UI.Plugin.XhrAutocomplete, {
					source: source,
					column: column,
					columns: columns,
					strict: strict,
					sourceOptions: sourceOptions
				}).on("select", function (args) {
					var item = args.item;
					var value = item ? "" + item[column] : "";

					self.setValue(value);

					if (bindings) {
						for (var i = 0; i < bindings.length; i++) {
							try {
								var source = bindings[i].source;
								var target = self.parseTarget(bindings[i].target);

								value = item ? item[source] : null;

								eval("self.parent." + target + " = value;");
							} catch (e) {
								console.log("XhrAutocomplete set bound value failed", target, value);
								console.dir(e);
							}
						}
					}

					if (self.parent && Util.isFunction(self.parent[handler])) {
						self.parent[handler](args);
					}
				});
			},

			updateElement: function () {
				this.ParentCall();

				this.textControl.plugin(UI.Plugin.XhrAutocomplete).read();
			},

			removeElement: function () {
				this.textControl.unlink();
				this.textControl.destroy();

				this.textControl = null;

				this.ParentCall();
			}
		});
	}
);
