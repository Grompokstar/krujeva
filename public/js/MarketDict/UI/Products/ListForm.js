Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("KrujevaDict.UI.Products");

		KrujevaDict.UI.Products.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDict/Products/ListForm",
			listItemTemplate: 'KrujevaDict/Products/ListItemForm',

			fields: {
				brandid: 'brandid',
				productcategoryid: 'Категория'
			},

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDict.Data.Products();
			},

			onChangeBrand: function () {
				this.setFieldsValue();

				//@refresh Category fields
				this.setValue('productcategoryid', null, true);

				this.getChoosenSelect('productcategoryid').chosenSelect();

				this.onChangeFilterList();
			},

			onChangeFilterList: function () {
				this.setFieldsValue();

				this.filter = Util.merge(this.filter, this.getRecord(), true);

				this.refreshList();
			},

			setCountItems: function () {

				var title = Util.declOfNum(this.countItems, ['товар','товара','товаров']);

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