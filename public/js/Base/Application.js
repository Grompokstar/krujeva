Module.define(
	"Util",
	"UI.Renderer.EJS",
	"UI.Template",
	"Base.Form",
	"Security.Module",
	"ServerTime",

	function () {
		NS("Base");

		Base.Application = Class(Base.Form, {

			hashLevel: -1,

			security: null,

			initialize: function () {
				this.ParentCall();

				UI.Template.renderer = UI.Renderer.EJS;

				this.security = new Security.Security(new Security.Context());
			},

			$page: function () {
				return $('#main-container');
			},

			init: function (context) {

				if (context) {
					this.security.init(context);

					ServerTime.start();
				}
			}
		});
	});