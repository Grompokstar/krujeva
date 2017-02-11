Module.define(
	"UI.Widget.GridView",
	"Data.Source",

	function () {
		NS("UI.Widget");

		UI.Widget.DataGridView = Class(UI.Widget.GridView, {
			source: null,
			args: null,

			actions: [
				{ css: "icon-pencil2", title: "Редактировать", "on-click": "onItemEditClick", width: 50 },
				{ css: "icon-remove", title: "Удалить", "on-click": "onItemRemoveClick", width: 50 }
			],

			initialize: function (parent, options) {
				this.override(["source"], options);

				this.ParentCall();
			},

			gridData: function (offset, limit, callback) {
				var options = Util.merge(Xhr.args(this.args), {
					offset: offset,
					limit: limit
				});

				if (this.filter) {
					options.options = Xhr.args({
						filter: this.filter
					}, true);
				}

				this.source.page(options, function (result) {
					if (result) {
						callback({
							count: result.count,
							items: result.items
						});
					}
				});
			}
		});
	}
);
