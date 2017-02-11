Module.define(
	"UI.Widget.List",

	function () {
		NS("UI.Widget");

		UI.Widget.ListView = Class(UI.Widget.List, {
			initialize: function (parent, options) {
				this.Parent(parent, options);

				this.override(["itemTemplate"], options);

				this.$list = this.$name("list-items", true);
			},

			render: function () {
				this.ParentCall();

				this.$list = this.$name("list-items", true);

				return this;
			}
		});
	}
);
