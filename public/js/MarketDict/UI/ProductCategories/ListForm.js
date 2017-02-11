Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("KrujevaDict.UI.ProductCategories");

		KrujevaDict.UI.ProductCategories.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDict/ProductCategories/ListForm",
			listItemTemplate: '',

			fields: {
				brandid: 'brandid'
			},

			tree: {},

			loadMethod: "tree",

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDict.Data.ProductCategories();
			},

			setCountItems: function () {

				var title = Util.declOfNum(this.countItems, ['категория','категории','категорий']);

				this.$name('count-items').html(this.countItems+ ' '+ title);
			},

			afterRender: function () {
				this.ParentCall();

				this.$list = this.$name('list-items');

				this.refreshList();
			},

			onChangeBrand: function () {
				this.setFieldsValue();

				this.filter = Util.merge(this.filter, this.getRecord(), true);

				this.refreshList();
			},

			insertItems: function (items, refresh, prepend) {

				if (refresh) {
					this.objectListItems = {};
					this.tree = {};
				}

				this.addTree(items);

				var $html = UI.Template.$render("KrujevaDict/ProductCategories/Tree", {items: items});

				if (refresh) {
					this.$list.empty();
				}

				if (prepend) {
					$html.prependTo(this.$list);
				} else {
					$html.appendTo(this.$list);
				}
			},

			addTree: function (items) {

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					this.tree[item.id] = item;

					if (item.items) {

						this.addTree(item.items);
					}

				}
			},

			onParentNodeClick: function (caller) {

				var $parent = $(caller).parent();

				if ($parent.hasClass('hide')) {

					$parent.removeClass('hide');

				} else {

					$parent.addClass('hide');
				}
			},

			onNodeClick: function (caller) {

				var id = $(caller).data('id');

				var item = this.tree[id];

				if (!item) {
					return;
				}

				this.parent.addClick(item);
			},
		});
	});