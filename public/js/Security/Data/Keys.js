Module.define("Data.ServerSource", function () {
	NS("Security.Data");

	Security.Data.Keys = Class(Data.ServerSource, {
		url: "Security/Web/Keys/",

		fields: [
			{ name: "id", title: "ID" },
			{ name: "name", title: "Название" },
			{ name: "description", title: "Описание" }
		]
	});
});
