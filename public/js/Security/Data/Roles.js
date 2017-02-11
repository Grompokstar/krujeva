Module.define(
	"Data.ServerSource",
	"Security.AccessMode",

	function () {

	NS("Security.Data");

	Security.Data.Roles = Class(Data.ServerSource, {
		url: "Security/Web/Roles/",

		fields: [
			{ name: "id", title: "ID" },
			{ name: "name", title: "Название" },
			{ name: "description", title: "Описание" },
			{ name: "access" }
		],

		create: function () {
			var item = this.ParentCall();

			item["access"] = [];

			return item;
		}
	});
});
