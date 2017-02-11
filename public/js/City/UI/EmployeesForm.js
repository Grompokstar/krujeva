Module.define(
	"Base.Form",
	"City.UI.AnimateForm",
	"City.UI.Employees.ListForm",
	"City.UI.Employees.EditForm",
	"City.UI.Employees.ViewForm",
	"City.UI.Employees.EditAdminForm",
	"City.UI.Employees.ViewAdminForm",

	function () {
		NS("City.UI");

		City.UI.EmployeesForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/EmployeesForm",

			pages: {
				list: ["City.UI.Employees.ListForm"],
				edit: ["City.UI.Employees.EditForm", {destroyOnHide: true}],
				view: ["City.UI.Employees.ViewForm", {destroyOnHide: true}],

				editadmin: ["City.UI.Employees.EditAdminForm", {destroyOnHide: true}],
				viewadmin: ["City.UI.Employees.ViewAdminForm", {destroyOnHide: true}],
			},

			defaultPage: "list",

			addClick: function () {
				this.open("edit");
			},

			openNext: function (options) {

				if (options && options.openNext) {
					this.open(options.openNext, options);
				} else {
					this.ParentCall();
				}
			}
		});
	});