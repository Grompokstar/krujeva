Module.define(
	"UI.Widget.ListView",

	function () {
		NS("UI.Widget");

		UI.Widget.GridView = Class(UI.Widget.ListView, {
			template: "UI/Widget/GridView/GridView",
			itemTemplate: "UI/Widget/GridView/GridViewItem",
			pagesTemplate: "UI/Widget/GridView/GridViewPages",
			columnTemplates: "UI/Widget/GridView/Column/",

			columns: [
			],

			actions: [
			],

			showHeader: true,
			showFooter: true,

			filter: null,

			gridLimit: 20,

			gridPage: 0,
			gridCount: 0,

			$header: null,
			$body: null,
			$footer: null,
			$pages: null,
			$count: null,

			initialize: function (parent, options) {
				this.override(["columns", "actions", "showHeader", "showFooter", "gridLimit"], options);

				this.ParentCall();
			},

			insertItem: function () {
				this.ParentCall();

				this.align();
			},

			removeItem: function () {
				this.ParentCall();

				this.align();
			},

			render: function () {
				this.ParentCall();

				this.$header = this.$name("gridview-header", true);
				this.$body = this.$name("gridview-body", true);
				this.$footer = this.$name("gridview-footer", true);
				this.$pages = this.$name("gridview-pages", true);
				this.$count = this.$name("gridview-count", true);

				return this;
			},

			align: function () {
				var self = this;

				if (this.$header && this.$body) {
//					this.$header.css("width", "auto");
//					this.$header.width(this.$body.width());
					setTimeout(function () {
						self.$header.css("width", "auto");
						self.$header.width(self.$body.width());
					}, 0);
				}

				return this;
			},

			page: function (page) {
				var self = this;

				if (page === undefined) {
					page = this.gridPage;
				}

				var offset = page * this.gridLimit;
				var limit = this.gridLimit;

				this.gridData(offset, limit, function (data) {
					var count = data.count;
					var items = data.items;

					self.gridPage = page;
					self.gridCount = count;

					self.update(items);
					self.updatePages();

					if (self.$count) {
						self.$count.html(self.gridCount);
					}

					self.align();
				});

				return this;
			},

			reset: function () {
				this.page(0);

				return this;
			},

			gridColumns: function () {
				return this.columns;
			},

			findColumn: function (object) {
				var result = null;

				object = Util.object(object);

				if (Object.keys(object)) {
					Util.each(this.columns, function (column) {
						for (var name in object) {
							if (object.hasOwnProperty(name)) {
								if (column[name] != object[name]) {
									return;
								}
							}
						}

						result = Util.clone(column);
						return false;
					});
				}

				return result;
			},

			gridColumnContent: function (column, item) {
				if (!column.type) {
					column.type = "Text";
				}

				switch (column.type) {
					case "template":
						return UI.Template.render(column.template, { widget: this, column: column, item: item });
					case "callback":
						return column.callback.call(this, item, column);
					default:
						return UI.Template.render(this.columnTemplates + column.type, { widget: this, column: column, item: item });
				}
			},

			gridData: function (offset, limit, callback) {
				if (Util.isFunction(callback)) {
					callback({
						count: 0,
						items: []
					});
				}

				return this;
			},

			gridActions: function () {
				return this.actions;
			},

			updatePages: function () {
				if (this.$pages) {
					var html = UI.Template.render(this.pagesTemplate, { widget: this });

					this.$pages.empty().append(html);
				}

				return this;
			},

			onPageChange: function (caller) {
				this.page(+$(caller).val());
			},

			onNextPageClick: function () {
				if (this.gridPage < ~~Math.ceil(this.gridCount / this.gridLimit) - 1) {
					this.page(this.gridPage + 1);
				}
			},

			onPrevPageClick: function () {
				if (this.gridPage > 0) {
					this.page(this.gridPage - 1);
				}
			},

			onRefreshPageClick: function () {
				this.page();
			},

			onItemSelectClick: function (caller, args) {
				var $column = $(args.target);

				if (!$column.hasClass("gridview-column")) {
					$column = $(args.target).parents(".gridview-column").first();
				}

				var columnName = $column.data("column-name");
				var index = this.indexOfElement(caller);
				var item = this.items[index];

				if (item) {
					this.emit("selectItem", { item: item, column: columnName });
				}
			},

			onItemCreateClick: function () {
				this.emit("createItem");

				return false;
			},

			onItemEditClick: function (caller) {
				var index = this.indexOfElement(caller);
				var item = this.items[index];

				if (item) {
					this.emit("editItem", { item: item });
				}

				return false;
			},

			onItemRemoveClick: function (caller) {
				var index = this.indexOfElement(caller);
				var item = this.items[index];

				if (item) {
					this.emit("removeItem", { item: item });
				}

				return false;
			},

			onItemCustomClick: function (caller) {
				var index = this.indexOfElement(caller);
				var item = this.items[index];
				var name = $(caller).data("name");

				if (name && name.length) {
					this.emit(name, { item: item, caller: caller });
				}

				return false;
			}
		});
	}
);
