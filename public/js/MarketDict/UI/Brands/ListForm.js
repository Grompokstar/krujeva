Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("KrujevaDict.UI.Brands");

		KrujevaDict.UI.Brands.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDict/Brands/ListForm",
			listItemTemplate: '',

			tree: {},

			loadMethod: "tree",

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDict.Data.Brands();
			},

			setCountItems: function () {

				var title = Util.declOfNum(this.countItems, ['бренд','бренда','брендов']);

				this.$name('count-items').html(this.countItems+ ' '+ title);
			},

			afterRender: function () {
				this.ParentCall();

				this.$list = this.$name('list-items');

				this.refreshList();
			},

			insertItems: function (items, refresh, prepend) {

				if (refresh) {
					this.objectListItems = {};
					this.tree = {};
				}

				this.addTree(items);

				var $html = UI.Template.$render("KrujevaDict/Brands/Tree", {items: items});

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