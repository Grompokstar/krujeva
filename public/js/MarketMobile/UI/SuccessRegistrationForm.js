Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.SuccessRegistrationForm = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/SuccessRegistrationForm",

			backClick: function () {
				application.backOpenPage();
			},

			nextClick: function () {

				application.cartForm._clearStorageData();

				application.open('provider', {animate: true});
			}
		});
	}
);