Module.define(
	"UI.Widget.Base",

	function () {

		NS("UI.Widget");

		UI.Widget.Menu = Class(UI.Widget.Base, {
			template: "UI/Widget/Menu/Menu",
			subTemplate: "UI/Widget/Menu/SubMenu",
			itemTemplate: "UI/Widget/Menu/MenuItem",

			load: function (struct) {
				var self = this;

				if (!this.isRendered) {
					return false;
				}

				this.$element.empty();

				function loadItem(item, target) {
					var $menuItem = UI.Template.$render(self.itemTemplate, item).appendTo(target);

					if (item.items && item.items.length) {
						var $subMenu = UI.Template.$render(self.subTemplate, item).appendTo($menuItem);

						Util.each(item.items, function (item) {
							loadItem(item, $subMenu);
						});
					}
				}

				Util.each(struct, function (item) {
					loadItem(item, self.$element);
				});

				return true;
			},

			mode: function (mode) {
				var self = this;

				if (mode) {
					if (!this.isRendered) {
						return false;
					}

					Util.each(this.Class.Mode, function (item) {
						self.$element.removeClass(item);
					});

					this.$element.addClass(mode);

					return true;
				} else {
					if (!this.isRendered) {
						return null;
					}

					Util.each(this.Class.Mode, function (item) {
						if (self.$element.hasClass(item)) {
							mode = item;

							return false;
						}

						return true;
					});

					return mode;
				}
			},

			onItemClick: function (caller) {
				var name = $(caller).data("name");

				this.emit("click", { name: name });
			}
		});

		UI.Widget.Menu.Static({
			Mode: {
				Vertical: "menu__vertical",
				Horizontal: "menu__horizontal"
			}
		});
	}
);
