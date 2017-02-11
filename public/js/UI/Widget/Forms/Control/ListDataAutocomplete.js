Module.define(
	"UI.Widget.Forms.Control.Element",
	"UI.Plugin.DataAutocomplete",
	"UI.Widget.ListView",
	"UI.Widget.Text",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.ListDataAutocomplete = Class(UI.Widget.Forms.Control.Element, {
			listControl: null,
			textControl: null,
			$input: null,
			
			createElement: function () {
				this.$element = $("<div>");
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				var source = Util.create(this.$control.attr("source"));
				var column = this.$control.attr("column");
				var columns = this.$control.attr("columns");
				var strict = !!this.$control.attr("strict");
				var handler = this.$control.attr("on-change");

				if (columns) {
					columns = columns.split(",");
				}

				this.listControl = new UI.Widget.ListView(null, {
					template: "UI/Widget/Forms/Control/ListDataAutocomplete/Control",
					itemTemplate: "UI/Widget/Forms/Control/ListDataAutocomplete/Item"
				});

				this.listControl.columns = columns;

				this.listControl.render().appendTo(this.$element);

				this.listControl.$element.on("click", "[name='remove-button']", function () {
					var item = $(this).data("item");

					self.listControl.removeItem(item);

					var items = Util.clone(self.listControl.items);

					self.setValue(items);

					if (self.parent && Util.isFunction(self.parent[handler])) {
						self.parent[handler]({ items: items });
					}
				});

				this.$input = $("<input type='text' />").appendTo(this.$element);

				this.textControl = new UI.Widget.Text(null, {
					element: this.$input
				});

				this.textControl.use(UI.Plugin.DataAutocomplete, {
					source: source,
					column: column,
					columns: columns,
					strict: strict
				}).on("select", function (args) {
					var item = args.item;

					if (item) {
						self.listControl.appendItem(item);
					}

					var items = Util.clone(self.listControl.items);

					self.setValue(items);

					self.$input.val("");
					self.textControl.plugin(UI.Plugin.DataAutocomplete).read();

					if (self.parent && Util.isFunction(self.parent[handler])) {
						self.parent[handler]({ items: items });
					}
				});
			},

			updateElement: function (value) {
				if (!Util.isArray(value)) {
					value = [];
				}

				this.listControl.update(value);

				this.$input.val("");
				this.textControl.plugin(UI.Plugin.DataAutocomplete).read();
			},

			removeElement: function () {
				this.listControl.destroy();
				this.textControl.destroy();

				this.listControl = null;
				this.textControl = null;
				this.$input = null;

				this.ParentCall();
			}
		});
	}
);
