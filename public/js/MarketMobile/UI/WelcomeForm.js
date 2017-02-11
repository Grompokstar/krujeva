Module.define(
	"KrujevaMobile.Page",

	function () {

		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.WelcomeForm = Class(KrujevaMobile.Page, {
			template: "KrujevaMobile/WelcomeForm",

			authClick: function () {
				application.open('auth1', {animate: true});
			},

			registrationClick: function () {
                application.open('auth3', {animate: true});
			}
		});
	}
);