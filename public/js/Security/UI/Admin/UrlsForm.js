Module.define(
	"Glonass.UI.Admin.Form",
	"Security.Data.Urls",
	"Security.Data.Keys",
	"Security.AccessMode",

	function () {
		NS("Security.UI.Admin");

		Security.UI.Admin.UrlsForm = Class(Glonass.UI.Admin.Form, {
			title: "UrlsForm",

			filter: {
				name: null,
				description: null
			},

			initialize: function () {
				this.source = new Security.Data.Urls();

				this.ParentCall();

				this.grid.columns = [
					{ name: "id", title: "ID", width: 50},
					{ name: "url", title: "Url"},
					{ name: "key", title: "Ключ"},
					{ name: "access", title: "Уровень доступа", type: "Enum", enum: Security.AccessMode }
				];

				this.editor = [
					{ title: "ID", type: "label", target: "item.id" },

					{ title: "Url", type: "text", target: "item.url" },

					{ title: "Ключ", type: "data-autocomplete", target: "item.key", bind: "item.keyid:id",
						source: "Security.Data.Keys", column: "name", columns: "name,description", strict: "yes" },

					{ title: "Уровень доступа", type: "enum-select", enum: "Security.AccessMode", target: "item.access" }
				];
			},

			onFilterClick: function () {
				this.grid.filter = Util.clone(this.filter);
				this.grid.reset();
			}
		});
	}
);
