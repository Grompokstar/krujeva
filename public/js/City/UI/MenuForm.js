Module.define(
	"Base.Form",

	function () {
		NS("City.UI");

		City.UI.MenuForm = Class(Base.Form, {
			template: "City/MenuForm",

			currentPage: null,

			initialize: function () {
				this.ParentCall();
				this.parent.on("Page.Show", this.onPageShow, this);

				application.on('Context.Update', this.onContextUpdate, this);
			},

			destroy: function () {
				this.parent.off("Page.Show", this.onPageShow, this);

				application.off('Context.Update', this.onContextUpdate, this);

				this.stopInterval();

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

				this.addItem("playgrounds", "Площадки", null, "Data.City.Playgrounds");
				this.addItem("statistics", "Статистика", null, "Data.City.Statistics");
				this.addItem("employees", "Cотрудники", null, "Data.City.Employees");
				//this.addItem("mail", "Входящие");
				this.addItem("franchisees", "Франчайзи", null, "Data.City.Franchisees");
				this.addItem("cities", "Города", null, "Data.City.Cities");
			},

			addItem: function (name, title, svg, securityKey) {

				if (!securityKey || check(securityKey, Security.AccessMode.Execute)) {

					var item = {name: name, title: title, svg: svg};

					var options = {};

					if (this.currentPage == name) {

						options.class = 'active';
					}

					var html = UI.Template.render("City/MenuForm/MenuItemForm", {
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

				application.security.signOut(function () {
					window.location.reload(true);
				});
			},

			editProfileClick: function () {
				this.parent.open('profile');
			},

			onContextUpdate: function () {

				var s = context('user.surname') + ' ' + context('user.name');

				this.$name('context').html(s);
			}
			
		});
	});