Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("KrujevaDict.UI.Dealers");

		KrujevaDict.UI.Dealers.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDict/Dealers/ListForm",
			listItemTemplate: 'KrujevaDict/Dealers/ListItemForm',

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDict.Data.Dealers();
			},

			setCountItems: function () {

				var title = Util.declOfNum(this.countItems, ['дилер','дилера','дилеров']);

				this.$name('count-items').html(this.countItems+ ' '+ title);
			},

			afterRender: function () {
				this.ParentCall();

				this.$list = this.$name('list-items');

				this.refreshList();

				this.listenScroll();
			}
		});
	});