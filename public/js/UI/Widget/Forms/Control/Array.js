Module.define(
	"UI.Widget.Forms.Control.Element",
	"UI.Widget.ListView",
	"UI.Widget.Text",

	function () {
		NS("UI.Widget.Forms.Control");

		UI.Widget.Forms.Control.Array = Class(UI.Widget.Forms.Control.Element, {
			listTemplate: "UI/Widget/Forms/Control/Array/Control",
			itemTemplate: "UI/Widget/Forms/Control/Array/Item",

			$list: null,
			$items: null,
			$input: null,

			createElement: function () {
				this.$element = $("<div>");
			},

			insertElement: function () {
				var self = this;

				this.ParentCall();

				var handler = this.$control.attr("on-change");

				this.$list = UI.Template.$render(this.listTemplate).appendTo(this.$element);
				this.$items = $("[name='list-items']", this.$list);

				this.$items.on("click", "[name='remove-button']", function () {
					var index = $(this).data("index");
					console.log("index", index);
					var items = self.getValue();

					items.splice(index, 1);

					self.setValue(items);

					$(this).parents(".array-control-list-item").first().remove();

					if (self.parent && Util.isFunction(self.parent[handler])) {
						self.parent[handler]({ items: items });
					}
				});

				this.$input = $("<input type='text' />").appendTo(this.$element);

				this.$input.on("keydown", function (args) {
					switch (args.keyCode) {
						case KeyCode.Enter:
							var value = self.$input.val();
							var values = self.getValue();

							if (!values) {
								values = [];
							}

							values.push(value);

							self.setValue(values);

							self.$input.val("");

							UI.Template.$render(self.itemTemplate, { item: value, index: values.length - 1 }).appendTo(self.$items);

							break;
					}
				});
			},

			updateElement: function (value) {
				if (!Util.isArray(value)) {
					value = [];
				}

				this.$items.empty();

				for (var i = 0; i < value.length; i++) {
					UI.Template.$render(this.itemTemplate, { item: value[i], index: i }).appendTo(this.$items);
				}

				this.$input.val("");
			},

			removeElement: function () {
				this.$input.remove();
				this.$items.remove();
				this.$list.remove();

				this.$input = null;
				this.$items = null;
				this.$list = null;

				this.ParentCall();
			}
		});
	}
);
