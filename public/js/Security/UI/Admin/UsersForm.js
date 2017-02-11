Module.define(
	"Glonass.UI.Admin.Form",
	"Security.Data.Users",
	"Security.Data.Roles",

	function () {
		NS("Security.UI.Admin");

		Security.UI.Admin.UsersForm = Class(Glonass.UI.Admin.Form, {
			filterTemplate: "Security/Admin/UsersForm/Filter",

			title: "Пользователи",

			filter: {
				id: null,
				login: null,
				name: null,
				roleid: null
			},

			initialize: function () {
				this.source = new Security.Data.Users();

				this.ParentCall();

				this.grid.columns = [
					{ name: "id", title: "ID", width: 50 },
					{ name: "login", title: "Логин" },
					{ name: "name", title: "Имя" },
					{ name: "rolename", title: "Роль" }
				];

				this.editor = [
					{ title: "ID", type: "label", target: "item.id" },
					{ title: "Логин", type: "text", target: "item.login" },
					{ title: "Пароль", type: "password", target: "item.password" },
					{ title: "Имя", type: "text", target: "item.name" },
					{ title: "Роль", type: "data-autocomplete", target: "item.rolename", bind: "item.roleid:id", source: "Security.Data.Roles", columns: "name", column: "name", strict: true },
					{ title: "Доп. роли", type: "list-data-autocomplete", target: "item.roles", source: "Security.Data.Roles", columns: "id,name", column: "name", strict: true },
					{ title: "Отпечаток ЭЦП", type: "text", target: "item.edsthumbprint" }
				];
			},

			onGridEdit: function (args) {
				if (args.item.password && !args.item.password.length) {
					args.item.password = null;
				}

				this.ParentCall();
			},

			onFilterClick: function () {
				this.grid.filter = Util.clone(this.filter);
				this.grid.reset();
			}
		});
	}
);
