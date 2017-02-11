Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.UpdateAppForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/UpdateAppForm",

			menuClick: function () {
				application.showMenu();
			},

			afterRender: function () {
				this.listenScroll();
			},

			updateClick: function () {
				KrujevaMobile.MobileUpdate.updateApp();
			}

		});
	}
);