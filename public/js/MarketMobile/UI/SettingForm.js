Module.define(
	"KrujevaMobile.Page",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.SettingForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/SettingForm",

			menuClick: function () {
				application.showMenu();
			},

			logoutClick: function () {

				application.context = null;

				LocalStorage.remove('SID');

				this.parent.open('welcome');
			}
		});
	}
);