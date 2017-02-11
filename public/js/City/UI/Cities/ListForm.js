Module.define(
	"Base.Form",
	"Base.ListTrait",

	function () {
		NS("City.UI.Cities");

		City.UI.Cities.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "City/Cities/ListForm",
			listItemTemplate: "City/Cities/ListItemForm",

			initialize: function () {
				this.ParentCall();

				this.listsource = new City.Data.Cities();

				City.Events.on("City.Cities.Remove", this.onItemRemove, this);
				City.Events.on("City.Cities.Insert", this.onItemInsert, this);
				City.Events.on("City.Cities.Update", this.onItemUpdate, this);
			},

			afterRender: function () {
				this.ParentCall();

				this.listenScroll();

				this.refreshList();
			},

			destroy: function () {
				this.listDestroy();

				City.Events.off("City.Cities.Remove", this.onItemRemove, this);
				City.Events.off("City.Cities.Insert", this.onItemInsert, this);
				City.Events.off("City.Cities.Update", this.onItemUpdate, this);

				this.ParentCall();
			},

			setCountItems: function () {
				var title = Util.declOfNum(this.countItems, [' город', ' города', ' городов']);
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

				var source = new City.Data.Cities();

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