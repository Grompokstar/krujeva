Module.define(
	"Base.Form",
	"City.UI.AnimateForm",
	"City.UI.Cities.ListForm",
	"City.UI.Cities.EditForm",

	function () {
		NS("City.UI");

		City.UI.CitiesForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/CitiesForm",

			pages: {
				list: ["City.UI.Cities.ListForm"],
				edit: ["City.UI.Cities.EditForm", {destroyOnHide: true}]
			},

			defaultPage: "list",

			addClick: function () {
				this.open("edit");
			}
		});
	});