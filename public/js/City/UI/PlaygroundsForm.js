Module.define(
	"Base.Form",
	"City.UI.AnimateForm",
	"City.UI.Playgrounds.ListForm",
	"City.UI.Playgrounds.EditForm",
	"City.UI.Playgrounds.ViewForm",

	function () {
		NS("City.UI");

		City.UI.PlaygroundsForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/PlaygroundsForm",

			pages: {
				list: ["City.UI.Playgrounds.ListForm"],
				edit: ["City.UI.Playgrounds.EditForm", {destroyOnHide: true}],
				view: ["City.UI.Playgrounds.ViewForm", {destroyOnHide: true}],
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