Module.define(
	"KrujevaMobile.Page",
	"KrujevaMobile.ListTrait",

	function () {
		NS("KrujevaMobile.UI");

		KrujevaMobile.UI.Auth2Form = Class(KrujevaMobile.Page, KrujevaMobile.ListTrait, {
			template: "KrujevaMobile/Auth2Form",


			backClick: function () {
				application.backOpenPage();
			},

			auth3Click: function () {
				this.parent.open('auth3', {animate: true});
			}
		});
	}
);