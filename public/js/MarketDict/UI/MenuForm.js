Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.MenuForm = Class(Base.Form, {
			template: "KrujevaDict/MenuForm",

			currentPage: null,

			initialize: function () {
				this.ParentCall();
				this.parent.on("Page.Show", this.onPageShow, this);
			},

			destroy: function () {
				this.parent.off("Page.Show", this.onPageShow, this);

				this.ParentCall();
			},

			initCSS: function (options) {

				if (options && options.animate) {

					this.$element.css({y: -100});

				}
			},

			show: function (options) {
				this.ParentCall();

				if (options && options.animate) {

					this.$element.transition({y: 0}, 250);
				}
			},

			afterRender: function () {
				this.ParentCall();

				this.currentPage = Base.Hash.getHash(this.parent.hashLevel + 1);

				this.addItem("brands", "Бренды", null, "Data.Krujeva.Brands");
				this.addItem("categories", "Категории", null, "Data.Krujeva.ProductCategories");
				//this.addItem("tasks", "Задачи", null, "Data.Krujeva.ProductTasks");
				this.addItem("properies", "Свойства", null, "Data.Krujeva.Properties");
				this.addItem("products", "Товары", null, "Data.Krujeva.ProductsForm");
				this.addItem("dealers", "Дилеры", null, "Data.Krujeva.DealersForm");
				this.addItem("users", "Парикмахеры", null, "Data.Krujeva.UsersForm");
			},

			addItem: function (name, title, svg, securityKey) {

				if (!securityKey || check(securityKey, Security.AccessMode.Execute)) {

					var item = {name: name, title: title, svg: svg};

					var options = {};

					if (this.currentPage == name) {

						options.class = 'active';
					}

					var html = UI.Template.render("KrujevaDict/MenuForm/MenuItemForm", {
						item: item,
						options: options
					});

					this.$name('menu-items').append(html);
				}

				return null;
			},

			onPageShow: function (args) {

				this.currentPage = Base.Hash.getHash(this.parent.hashLevel + 1);

				this.$(".menu-item").removeClass("active");

				this.$name(this.currentPage).addClass("active");
			},

			onMenuItemClick: function (caller) {
				var name = $(caller).attr('name');

				this.parent.open(name);
			},

			logoutClick: function () {

				if(!confirm('Вы действительно хотите выйти?')) {
					return;
				}

				var source = new KrujevaDict.Data.Users();

				source.logout(null, function () {
					window.location.reload(true);
				});
			},

			
		});
	});