Module.define(
	"Base.Form",
	"KrujevaDict.UI.ProductCategories.ListForm",
	"KrujevaDict.UI.Widget.EditProductCategoriesForm",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.ProductCategoriesForm = Class(Base.Form, {
			template: "KrujevaDict/ProductCategoriesForm",

			editForm: null,

			pages: {
				list: ["KrujevaDict.UI.ProductCategories.ListForm", {destroyOnHide: true}]
			},

			defaultPage: "list",

			$page: function () {
				return this.$name('list-form');
			},

			getListFilter: function () {
				var filter = {};

				if (!this.openedPages['list']) {
					return filter;
				}

				return this.openedPages['list'].getRecord();
			},

			addClick: function (item) {

				var listFilter = this.getListFilter();

				if (!listFilter['brandid']) {

					Alert.error('Укажите бренд');

					return;
				}

				var oldBlock = null;

				var zIndex = 1;

				if (this.editForm) {

					this.editForm.off('destroy', this.onEditFormDestroy, this);

					this.editForm.off('item:change', this.onItemChange, this);

					zIndex = parseInt(this.editForm.$element.css('zIndex')) + 1;

					oldBlock = this.editForm;
				}

				var $container = this.$name('edit-block');

				item = Util.merge(item, listFilter, true);

				this.editForm = this.showWidget("KrujevaDict.UI.Widget.EditProductCategoriesForm",{
					prependElement: $container,
					item: item,
					animate: true,
					needDestroy: false,
					zIndex: zIndex,
					callback: function () {

						if (oldBlock) {
							oldBlock.destroy();
						}

					}.bind(this)
				});

				this.editForm.on('destroy', this.onEditFormDestroy, this);
				this.editForm.on('item:change', this.onItemChange, this);
			},

			onItemChange: function () {

				if (this.openedPages['list']) {
					this.openedPages['list'].refreshList();
				}
			},

			onEditFormDestroy: function () {
				this.editForm = null;
			}
		});
	});