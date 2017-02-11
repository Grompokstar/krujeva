Module.define(
	"UI.Widget.Form",

	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.ViewForm = Class(UI.Widget.Form, {
			formTemplate: "UI/Widget/Forms/ViewForm/ViewForm",
			columnTemplates: "UI/Widget/Forms/ViewForm/Column/",

			draggable: false,

			columns: [],

			item: null,

			initialize: function (parent, options) {
				var self = this;

				this.override(["columns", "item"], options);

				this.Parent(parent, options);
			},

			columnContent: function (column) {
				if (!column.type) {
					column.type = "Text";
				}

				switch (column.type) {
					case "template":
						return UI.Template.render(column.template, { widget: this, column: column });
					case "callback":
						return column.callback.call(this, this.item, column);
					default:
						return UI.Template.render(this.columnTemplates + column.type, { widget: this, column: column, item: this.item });
				}
			}
		});
	}
);
