Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("City.UI.Franchisees");

		City.UI.Franchisees.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "City/Franchisees/ListForm",
			listItemTemplate: "City/Franchisees/ListItemForm",

			fields: {
				'cityname': 'cityname'
			},

			initialize: function () {
				this.ParentCall();

				this.listsource = new City.Data.Franchisees();

				City.Events.on("City.Franchisees.Remove", this.onItemRemove, this);
				City.Events.on("City.Franchisees.Insert", this.onItemInsert, this);
				City.Events.on("City.Franchisees.Update", this.onItemUpdate, this);
			},

			afterRender: function () {
				this.ParentCall();

				this.listenScroll();

				this.refreshList();
			},

			destroy: function () {
				this.listDestroy();

				City.Events.off("City.Franchisees.Remove", this.onItemRemove, this);
				City.Events.off("City.Franchisees.Insert", this.onItemInsert, this);
				City.Events.off("City.Franchisees.Update", this.onItemUpdate, this);

				this.ParentCall();
			},

			setCountItems: function () {
				var title = Util.declOfNum(this.countItems, [' франчайзи', ' франчайзи', ' франчайзи']);
				this.$name('count-items').html(this.countItems + title);
			},

			editClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.open('edit', {item: item['item']});
			},

			francheseItemClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.open('view', {item: item['item']});
			},

			removeClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				if (!confirm('Удалить ' + item['item'].name + '?')) {
					return;
				}

				//@loader
				var loader = Loader.customHtml("City/Loader", true, {name: 'main-loader'}, {
					description: 'Удаляем город'
				});

				this.$name('main-loader-cnt').html(loader);

				loader = Loader.start(this.$name('main-loader'));

				var source = new City.Data.Franchisees();

				source.remove({id: id}, function (data, result) {
					Loader.end(loader, function () {

						if (data && data.item) {

							Alert.success('Успешно удалено');

						} else {

							Alert.error('Не удалось удалить');
						}

					});
				});
			}
		});
	});