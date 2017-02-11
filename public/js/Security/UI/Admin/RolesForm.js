Module.define(
	"Glonass.UI.Admin.Form",
	"Security.Data.Roles",
	"Security.UI.Admin.RoleKeysForm",

	function () {
		NS("Security.UI.Admin");

		Security.UI.Admin.RolesForm = Class(Glonass.UI.Admin.Form, {
			filterTemplate: "Security/Admin/RolesForm/Filter",

			title: "Роли",

			editKeysForm: null,

			filter: {
				name: null,
				description: null,
			},

			initialize: function () {
				this.source = new Security.Data.Roles();

				this.ParentCall();

				this.grid.actions.unshift({ css: "icon-key", title: "Ключи", "on-click": "onItemCustomClick", name: "editKeys", width: 50 });

				this.grid.source = new Security.Data.Roles();

				this.grid.columns = [
					{ name: "id", title: "ID", width: 50 },
					{ name: "name", title: "Название" },
					{ name: "description", title: "Описание" }
				];

				this.grid.on("editKeys", this.onEditKeys, this);

				this.editor = [
					{ title: "ID", type: "label", target: "item.id" },
					{ title: "Название", type: "text", target: "item.name" },
					{ title: "Описание", type: "text-area", target: "item.description" }
				]
			},

			onEditKeys: function (args) {
				var self = this;
				var item = args.item;

				if (!item) {
					return;
				}

				this.grid.source.get(item, function (result) {
					var position = null;

					if (self.editKeysForm) {
						position = self.editKeysForm.position();

						self.editKeysForm.destroy();
					}

					if (!self.isDestroyed) {
						self.editKeysForm = new Security.UI.Admin.RoleKeysForm(self, {
							item: result.item,
							title: function () { return this.item.name }
						}).render().appendTo(self.container()).on("destroy", function () {
								self.editKeysForm = null;
							}).on("save", function (args) {
								self.grid.source.update(args.item, function (item) {
									if (item) {
										if (!self.isDestroyed) {
											if (self.editKeysForm) {
												if (args.close) {
													self.editKeysForm.destroy();
												} else {
													var position = self.editKeysForm.position();

													self.editKeysForm.item = Util.clone(item);
													self.editKeysForm.render();

													self.editKeysForm.position(position);
												}
											}

											self.grid.updateItem(item);
										}
									} else {
										alert("Не удалось сохранить запись.");
									}
								});
							});

						if (position) {
							self.editKeysForm.position(position);
						}
					}
				});
			},

			onFilterClick: function () {
				this.grid.filter = Util.clone(this.filter);
				this.grid.reset();
			}
		});
	}
);
