Module.define(
	"UI.Renderer.Base",

	function () {

		NS("UI.Renderer");

		UI.Renderer.EJS = new Static(UI.Renderer.Base, {
			path: "templates/",

			render: function (name, args) {
				args = Util.coalesce(args, {});

				//try {
					return new EJS({url: this.path + name + ".ejs"}).render(args);
				//} catch (e) {
				//	console.log("UI.Renderer.EJS.render(" + name + ")", args);

				//	throw e;
				//}
			}
		});
	}
);
