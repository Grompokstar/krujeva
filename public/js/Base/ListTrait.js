Module.define(
	"Base.InfinityScroll",

	function () {

	NS("Base");

	Base.ListTrait = Class({
		listItemTemplate: '',

		listGroupTemplate: '',

		offset: 0,

		limit: 40,

		countItems: 0,

		loadMethod: "page",

		issetitems: true,

		isload: false,

		$list: null,

		listsource: null,

		objectListItems: {},

		loadOptions: {},

		filter: {},

		groupItems: false,

		listDestroy: function () {
			this.$list = null;
		},

		listenScroll: function () {

			this.$list = this.$name('list-items');

			var scroll = this.$list;

			if (this.$name('infinity-scroll').html()) {
				scroll = this.$name('infinity-scroll');
			}

			new Base.InfinityScroll(scroll, {
				event: {object: this, method: this.onScrolledList},
				lowerLimit: 1000
			});

		},

		onScrolledList: function (cb) {

			if (!this.issetitems) {
				cb();
				return;
			}

			this.loadList(this.offset, function (error, items) {
				if (error) {
					return false;
				}

				if (this.groupItems) {

					this.insertGroupItems(items);
				} else {

					this.insertItems(items);
				}

				cb();
			}.bind(this));
		},

		filterChanged: function (caller) {
			var name = $(caller).data('name') || $(caller).attr('name');
			var value = $(caller).val();

			this.filterChange(name, value);
		},

		filterChange: function (name, value) {

			if (name) {

				if (value) {

					this.filter[name] = value;

				} else {

					delete this.filter[name];
				}
			}

			this.refreshList();
		},

		refreshList: function (callback) {
			this.issetitems = true;

			this.loadList(0, function (error, items) {

				if (error) {
					console.log("error get list items");
					return;
				}

				if (this.groupItems) {

					this.insertGroupItems(items, true);
				} else {

					this.insertItems(items, true);
				}

				this.setCountItems();

				if (Util.isFunction(callback)) {
					callback(items);
				}

			}.bind(this));
		},

		loadList: function (offset, callback) {
			this.offset = (typeof offset == 'undefined') ? 0 : offset;

			if (this.loadrequest) {
				this.loadrequest.abort();
				this.loadrequest = null;
			}

			this.loadrequest = this.listsource[this.loadMethod]({
				offset: this.offset,
				limit: this.limit,
				options: Util.merge(this.loadOptions, {filter: this.filter})
			}, function (result) {

				this.loadrequest = null;

				this.countItems = 0;

				result = result || {};

				this.onLoadItems(result);

				if (!result.items) {

					if (Util.isFunction(callback)) {
						callback(true, null);
					}

					return;
				}

				this.countItems = result.count;

				if (!result.items.length) {
					this.issetitems = false;
				}

				if (Util.isFunction(callback)) {
					callback(false, result.items, result);
				}

			}.bind(this));
		},

		onLoadItems: function () {},

		setCountItems: function () {},

		insertGroupItems: function (items, refresh, prepend) {
			var $html = $([]);

			var containerGroup = [];
			var groupIndex = {};
			var grIndex = 0;

			if (refresh) {
				this.objectListItems = {};

				this.$list.scrollTop(0);
			}

			this.offset += items.length;

			var index = Object.keys(this.objectListItems).length + 1;

			for (var i in items) if (items.hasOwnProperty(i)) {
				var item = items[i];

				var groupvalue = item[item.groupkey];
				var groupname = Util.crc32(groupvalue);

				if (typeof groupIndex[groupname] == 'undefined') {

					groupIndex[groupname] = grIndex;

					grIndex++;
				}

				if (!containerGroup[groupIndex[groupname]]) {

					containerGroup[groupIndex[groupname]] = {
						groupname: groupname,
						groupkey: item.groupkey,
						groupvalue: groupvalue,
						items: $([])
					};

				}

				var $element = this.createListItem(item);

				this.objectListItems[item.id] = {
					index: ++index,
					item: item,
					$element: $element
				};

				containerGroup[groupIndex[groupname]]['items'] = containerGroup[groupIndex[groupname]]['items'].add($element);
			}

			if (refresh) {
				this.$list.empty();
			}

			//@append on containers
			for (var i in containerGroup) if (containerGroup.hasOwnProperty(i)) {
				var container = containerGroup[i];

				//@ isset on DOM
				var $groupContainer = this.$name("groupname-" + container.groupname);

				if ($groupContainer.length) {

					container['items'].appendTo($groupContainer);

				} else {

					//render
					$groupContainer = $(UI.Template.render(this.listGroupTemplate, {
						widget: this,
						groupkey: container.groupkey,
						groupvalue: container.groupvalue,
						groupname: container.groupname
					}));

					container['items'].appendTo($groupContainer.find('[name=list-items]'));

					if (prepend) {
						$groupContainer.prependTo(this.$list);
					} else {
						$groupContainer.appendTo(this.$list);
					}
				}
			}

			if (!Object.keys(this.objectListItems).length) {

				var $emptyTemplate = UI.Template.$render(this.emptyTemplate, {widget: this});

				$emptyTemplate.appendTo(this.$list);
			}

		},

		insertItems: function (items, refresh, prepend) {
			var $html = $([]);

			if (refresh) {
				this.objectListItems = {};
			}

			var index = Object.keys(this.objectListItems).length + 1;

			this.offset += items.length;

			for (var i in items) if (items.hasOwnProperty(i)) {

				var item = items[i];

				var $element = this.createListItem(item);

				this.objectListItems[item.id] = {
					index: ++index,
					item: item,
					$element: $element
				};

				$html = $html.add($element);
			}

			if (refresh) {
				this.$list.empty();
			}

			if (prepend) {
				$html.prependTo(this.$list);
			} else {
				$html.appendTo(this.$list);
			}
		},

		createListItem: function (item) {
			return UI.Template.$render(this.listItemTemplate, {item: item, widget: this});
		},

		onItemRemove: function (args) {

			var item = this.objectListItems[args.id];

			if (!item) {
				return;
			}

			item['$element'].remove();

			delete this.objectListItems[args.id];

			this.countItems--;

			this.setCountItems();
		},

		onItemInsert: function (args) {
			this.insertItems([args.item], false, true);

			this.countItems++;

			this.setCountItems();
		},

		onItemUpdate: function (args) {

			var item = this.objectListItems[args.item.id];

			if (!item) {
				return;
			}

			var $element = this.createListItem(args.item);

			item.$element.replaceWith($element);

			item.$element.remove();

			this.objectListItems[args.item.id] = {
				index: item['index'],
				item: args.item,
				$element: $element
			};
		}

	});


});