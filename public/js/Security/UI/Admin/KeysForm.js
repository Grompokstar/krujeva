Module.define(
	"Glonass.UI.Admin.Form",
	"Security.Data.Keys",

	function () {
		NS("Security.UI.Admin");

		Security.UI.Admin.KeysForm = Class(Glonass.UI.Admin.Form, {
			filterTemplate: "Security/Admin/KeysForm/Filter",

			title: "Ключи",

			filter: {
				name: null,
				description: null,
			},

			initialize: function () {
				this.source = new Security.Data.Keys();

				this.ParentCall();

				this.grid.columns = [
					{ name: "id", title: "ID", width: 50 },
					{ name: "name", title: "Название" },
					{ name: "description", title: "Описание" }
				];

				this.editor = [
					{ title: "ID", type: "label", target: "item.id" },
					{ title: "Название", type: "text", target: "item.name" },
					{ title: "Описание", type: "text-area", target: "item.description" }
				];
			},

			onFilterClick: function () {
				this.grid.filter = Util.clone(this.filter);
				this.grid.reset();
			}
		});
	}
);
