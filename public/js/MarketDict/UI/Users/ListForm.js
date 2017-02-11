Module.define(
	"Base.Form",
	"Base.ListTrait",
	"KrujevaDict.UserStatus",

	function () {
		NS("KrujevaDict.UI.Users");

		KrujevaDict.UI.Users.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "KrujevaDict/Users/ListForm",
			listItemTemplate: 'KrujevaDict/Users/ListItemForm',

			initialize: function () {
				this.ParentCall();
				this.listsource = new KrujevaDict.Data.Users();
			},

			setCountItems: function () {

				var title = Util.declOfNum(this.countItems, ['пользователь', 'пользователя', 'пользователей']);

				this.$name('count-items').html(this.countItems + ' ' + title);
			},

			afterRender: function () {
				this.ParentCall();

				this.$list = this.$name('list-items');

				this.refreshList();

				this.listenScroll();
			},

			getUserStatusOptions: function () {
				var options = [];

				KrujevaDict.UserStatus.each(function (value) {

					options.push({id:  value, name: KrujevaDict.UserStatus.title(value)});

				});

				return options;
			},

			onNodeClick: function (caller) {

				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.addClick(item.item);
			},
		});
	});