Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDealer.UI");

		KrujevaDealer.UI.MenuForm = Class(Base.Form, {
			template: "KrujevaDealer/MenuForm",

			currentPage: null,

			initialize: function () {
				this.ParentCall();
				this.parent.on("Page.Show", this.onPageShow, this);
				application.data.on('neworders.change', this.onNewOrdersChange, this);
			},

			destroy: function () {
				this.parent.off("Page.Show", this.onPageShow, this);
				application.data.off('neworders.change', this.onNewOrdersChange, this);

				this.ParentCall();
			},

			onNewOrdersChange: function (args) {

				if (args.count) {

					this.$name('new-orders-count').html('<div class="new-orders-count">'+ args.count +'</div>');
				} else {

					this.$name('new-orders-count').empty();
				}
			},

			afterRender: function () {
				this.ParentCall();

				this.currentPage = Base.Hash.getHash(this.parent.hashLevel + 1);

				this.onPageShow();
			},

			onPageShow: function (args) {

				this.currentPage = Base.Hash.getHash(this.parent.hashLevel + 1);

				this.$(".menu-item").removeClass("active");


				if (this.currentPage == 'orders') {

					var page = application.openedPages['orders'];

					if (page) {

						this.$('[name='+ this.currentPage+'][data-args='+ page.currentArg+']').addClass("active");
					}

				} else {
					this.$name(this.currentPage).addClass("active");
				}


			},

			onMenuItemClick: function (caller) {
				var page = $(caller).attr('name');

				var args = $(caller).data('args');

				this.parent.open(page, {args: args});
			},

			logoutClick: function () {

				if(!confirm('Вы действительно хотите выйти?')) {
					return;
				}

				var source = new KrujevaDealer.Data.Users();

				source.logout(null, function () {
					window.location.reload(true);
				});
			},

			
		});
	});