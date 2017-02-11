Module.define(
	"UI.Widget.Base",

	function () {
		NS("UI.Widget");

		UI.Widget.Notify = Class(UI.Widget.Base, {

			formData: function(args) {
				return args;
			},

			init: function(args) {
				this.render({data: this.formData(args)});

				return this;
			}

		});
});
