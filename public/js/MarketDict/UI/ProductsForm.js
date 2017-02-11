Module.define(
	"Base.Form",
	"KrujevaDict.UI.Products.ListForm",
	"KrujevaDict.UI.Products.EditForm",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.ProductsForm = Class(Base.Form, {
			template: "KrujevaDict/ProductsForm",

			editForm: null,

			pages: {
				list: ["KrujevaDict.UI.Products.ListForm", {destroyOnHide: true}],
				edit: ["KrujevaDict.UI.Products.EditForm", {destroyOnHide: true}]
			},

			defaultPage: "list",

			addItemClick: function () {
				this.open('edit');
			},

			editClick: function (caller) {

				var id = $(caller).data('id');

				var list = this.openedPages['list'];

				if (!list) {
					return;
				}

				var item = list.objectListItems[id];

				if (!item) {
					return;
				}

				this.open('edit', {item: item['item']});
			},

			addCopyClick: function (caller) {

				var id = $(caller).data('id');

				var list = this.openedPages['list'];

				if (!list) {
					return;
				}

				var item = list.objectListItems[id];

				if (!item) {
					return;
				}

				this.open('edit', {item: item['item'], copy: true});
			}

		});
	});