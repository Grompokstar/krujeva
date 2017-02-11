Module.define(
	"Base.Form",
	"KrujevaDict.UI.Dealers.ListForm",
	"KrujevaDict.UI.Dealers.EditForm",
	"KrujevaDict.UI.Dealers.PriceForm",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.DealersForm = Class(Base.Form, {
			template: "KrujevaDict/DealersForm",

			pages: {
				list: ["KrujevaDict.UI.Dealers.ListForm", {destroyOnHide: true}],
				edit: ["KrujevaDict.UI.Dealers.EditForm", {destroyOnHide: true}],
				price: ["KrujevaDict.UI.Dealers.PriceForm", {destroyOnHide: true}],
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

			priceClick: function (caller) {

				var id = $(caller).data('id');

				var list = this.openedPages['list'];

				if (!list) {
					return;
				}

				var item = list.objectListItems[id];

				if (!item) {
					return;
				}

				this.open('price', {item: item['item']});
			}
		});
	});