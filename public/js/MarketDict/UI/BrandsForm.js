Module.define(
	"Base.Form",
	"KrujevaDict.UI.Brands.ListForm",
	"KrujevaDict.UI.Widget.EditBrandsForm",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.BrandsForm = Class(Base.Form, {
			template: "KrujevaDict/BrandsForm",

			editForm: null,

			pages: {
				list: ["KrujevaDict.UI.Brands.ListForm", {destroyOnHide: true}]
			},

			defaultPage: "list",

			$page: function () {
				return this.$name('list-form');
			},

			addClick: function (item) {

				var oldBlock = null;

				var zIndex = 1;

				if (this.editForm) {

					this.editForm.off('destroy', this.onEditFormDestroy, this);
					this.editForm.off('item:change', this.onItemChange, this);

					zIndex = parseInt(this.editForm.$element.css('zIndex')) + 1;

					oldBlock = this.editForm;
				}

				var $container = this.$name('edit-block');

				this.editForm = this.showWidget("KrujevaDict.UI.Widget.EditBrandsForm",{
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