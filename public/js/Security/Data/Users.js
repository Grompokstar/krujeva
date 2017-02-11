Module.define("Data.ServerSource", function () {
	NS("Security.Data");

	Security.Data.Users = Class(Data.ServerSource, {
		url: "Security/Web/Users/",

		fields: [
			{ name: "id", title: "ID" },
			{ name: "roleid", title: "Роль" },
			{ name: "rolename", title: "Роль" },
			{ name: "login", title: "Логин" },
			{ name: "password", title: "Пароль" },
			{ name: "name", title: "Имя" }
		]
	});
});
