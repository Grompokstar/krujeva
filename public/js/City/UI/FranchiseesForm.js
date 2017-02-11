Module.define(
	"Base.Form",
	"City.UI.AnimateForm",
	"City.UI.Franchisees.ListForm",
	"City.UI.Franchisees.EditForm",
	"City.UI.Franchisees.ViewForm",

	function () {
		NS("City.UI");

		City.UI.FranchiseesForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/FranchiseesForm",

			pages: {
				list: ["City.UI.Franchisees.ListForm"],
				edit: ["City.UI.Franchisees.EditForm", {destroyOnHide: true}],
				view: ["City.UI.Franchisees.ViewForm", {destroyOnHide: true}],
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