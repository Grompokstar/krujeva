Module.define(
	"UI.Widget.Form",
	"UI.Widget.Forms.ViewForm",
	"UI.Widget.Forms.EditForm",

	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.GridViewForm = Class(UI.Widget.Form, {
			formTemplate: "UI/Widget/Forms/GridViewForm/GridViewForm",
			filterTemplate: null,

			grid: null,

			$formGrid: null,
			$formFilter: null,

			gridStyles: [],
			filterStyles: [],

			viewFormClass: UI.Widget.Forms.ViewForm,
			viewFormOptions: null,
			viewForm: null,

			editFormClass: UI.Widget.Forms.EditForm,
			editFormOptions: null,
			editForm: null,

			initialize: function (parent, options) {
				this.override(["grid"], options);

				this.ParentCall();

				this.grid.on("selectItem", this.onGridSelect, this);
				this.grid.on("createItem", this.onGridCreate, this);
				this.grid.on("editItem", this.onGridEdit, this);
				this.grid.on("removeItem", this.onGridRemove, this);
			},

			render: function () {
				var self = this;

				this.grid.remove();

				this.ParentCall();

				this.$formGrid = this.$name("form-grid");
				this.$formFilter = this.$name("form-filter");

				this.grid.render().appendTo(this.$formGrid).reset();

				if (this.gridStyles) {
					Util.each(this.gridStyles, function (style) {
						self.$formGrid.addClass(style);
					});
				}

				if (this.filterTemplate) {
					UI.Template.$render(this.filterTemplate).appendTo(this.$formFilter);

					if (this.filterStyles) {
						Util.each(this.filterStyles, function (style) {
							self.$formFilter.addClass(style);
						});
					}
				}

				return this;
			},

			createItem: function () {
				return {};
			},

			onGridSelect: function (args) {
				var self = this;
				var position = null;

				if (!this.viewFormClass) {
					return;
				}

				if (this.viewForm) {
					position = this.viewForm.position();

					this.viewForm.destroy();
				}

				var options = Util.merge(this.viewFormOptions, {
					item: Util.clone(args.item)
				}, true);

				this.viewForm = new this.viewFormClass(this, options).
					render().appendTo(this.container()).on("destroy", function () {
						self.viewForm = null;
					});

				if (position) {
					this.viewForm.position(position);
				}
			},

			onGridCreate: function () {
				this.onGridEdit({
					item: this.createItem(),
					allowMany: true
				});
			},

			onGridEdit: function (args) {
				var self = this;
				var position = null;

				if (!this.editFormClass) {
					return;
				}

				if (!args.allowMany && this.editForm) {
					position = this.editForm.position();

					this.editForm.destroy();
				}

				var options = Util.merge(this.editFormOptions, {
					item: Util.clone(args.item)
				}, true);

				this.editForm = new this.editFormClass(this, options).
					render().appendTo(this.container()).on("destroy", function () {
						self.editForm = null;
					}).on("save", function (args) {
						self.onSaveItem(args);
					});

				if (position) {
					this.editForm.position(position);
				}
			},

			onGridRemove: function (args) {
				this.grid.removeItem(args.item);
			},

			onSaveItem: function (args) {
			}
		});
	}
);
