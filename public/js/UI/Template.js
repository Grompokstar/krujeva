Module.define("UI.Renderer.EJS", function () {
	NS("UI");

	UI.Template = new Static({
		renderer: null,

		render: function (name, args) {
			return (this.renderer && name) ? this.renderer.render(name, args) : "";
		},

		$render: function (name, args) {
			return $(this.render(name, args));
		}
	});
});
