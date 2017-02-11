Module.define(
	"Base.Form",
	"Base.ListTrait",
	"City.Data.Cities",

	function () {
		NS("City.UI.Playgrounds");

		City.UI.Playgrounds.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "City/Playgrounds/ListForm",
			listItemTemplate: "City/Playgrounds/ListItemForm",

			initialize: function () {
				this.ParentCall();

				this.listsource = new City.Data.Playgrounds();

				City.Events.on("City.Playgrounds.Remove", this.onItemRemove, this);
				City.Events.on("City.Playgrounds.Insert", this.onItemInsert, this);
				City.Events.on("City.Playgrounds.Update", this.onItemUpdate, this);
			},

			afterRender: function () {
				this.ParentCall();

				this.listenScroll();

				this.refreshList();
			},

			destroy: function () {
				this.listDestroy();

				City.Events.off("City.Playgrounds.Remove", this.onItemRemove, this);
				City.Events.off("City.Playgrounds.Insert", this.onItemInsert, this);
				City.Events.off("City.Playgrounds.Update", this.onItemUpdate, this);

				this.ParentCall();
			},

			setCountItems: function () {
				var title = Util.declOfNum(this.countItems, [' площадка', ' площадки', ' площадок']);
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

			viewClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.open('view', {item: item['item']});
			}
		});
	});