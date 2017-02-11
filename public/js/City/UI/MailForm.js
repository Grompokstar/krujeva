Module.define(
	"Base.Form",
	"City.UI.AnimateForm",

	function () {
		NS("City.UI");

		City.UI.MailForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/MailForm",
		});
	});