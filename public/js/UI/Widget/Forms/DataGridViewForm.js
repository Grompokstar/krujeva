Module.define(
	"UI.Widget.Forms.GridViewForm",
	"UI.Widget.DataGridView",
	"UI.Widget.Forms.DataViewForm",
	"UI.Widget.Forms.DataEditForm",

	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.DataGridViewForm = Class(UI.Widget.Forms.GridViewForm, {
			draggable: false,

			viewFormClass: UI.Widget.Forms.DataViewForm,
			editFormClass: UI.Widget.Forms.DataEditForm,

			initialize: function () {
				this.grid = new UI.Widget.DataGridView(this);

				this.ParentCall();
			},

			createItem: function () {
				return this.grid.source.create();
			},

			updateItem: function (item, callback) {
				this.grid.source.get(this.getUpdateItemOptions(item), function (result) {
					callback(result.item);
				});
			},

			getUpdateItemOptions: function (item) {
				return {
					id: item.id
				};
			},

			onGridSelect: function (args) {
				if (!this.viewFormClass) {
					return;
				}

				var self = this;
				var parentMethod = this.ParentMethod();

				this.updateItem(args.item, function (item) {
					if (item && !self.isDestroyed) {
						args.item = item;

						self.CallMethod(parentMethod, args);
					}
				});
			},

			onGridEdit: function (args, callback) {
				if (!this.editFormClass) {
					return;
				}

				var self = this;
				var parentMethod = this.ParentMethod();

				if (args.item.id) {
					this.updateItem(args.item, function (item) {
						if (item && !self.isDestroyed) {
							args.item = item;

							self.CallMethod(parentMethod, args);

							if (Util.isFunction(callback) && self.editForm) {
								callback(self.editForm);
							}
						}
					});
				} else {
					this.Parent(args);

					if (Util.isFunction(callback) && this.editForm) {
						callback(this.editForm);
					}
				}
			},

			onGridRemove: function (args, callback) {
				var self = this;
				var parentMethod = this.ParentMethod();

				if (confirm("Действительно удалить запись?")) {
					this.grid.source.remove(args.item, function (result) {
						if (result && result.item) {
							if (!self.isDestroyed) {
								self.CallMethod(parentMethod, args);
							}

							if (Util.isFunction(callback)) {
								callback({ item: result.item });
							}
						} else {
							alert("Не удалось удалить запись.");
						}
					});
				}
			},

			onSaveItem: function (args, callback) {
				var self = this;
				var action = args.item.id ? "update" : "insert";
				var method = args.item.id ? "updateItem" : "prependItem";

				this.grid.source[action](args.item, function (result) {
					if (result && result.item) {
						if (!self.isDestroyed) {
							if (self.editForm) {
								if (args.close) {
									self.editForm.destroy();
								} else {
									var position = self.editForm.position();

									self.editForm.item = Util.clone(result.item);
									self.editForm.render();

									self.editForm.position(position);
								}
							}

							self.grid[method](result.item);
						}

						if (Util.isFunction(callback)) {
							callback({ item: result.item, action: action });
						}
					} else {
						alert("Не удалось сохранить запись.");
					}
				});
			}
		});
	}
);
