Module.define(
	"UI.Widget.Base",
	"System.NumSeq",

	function () {
		NS("UI.Widget");

		UI.Widget.List = Class(UI.Widget.Base, {
			itemTemplate: null,

			items: [],
			elements: [],
			index: {},

			filterCallback: null,

			selectedClass: "selected",
			listItemClass: "ui-widget-list",

			$list: null,

			visibleItems: {},

			initialize: function (parent, options) {
				this.Parent(parent, options);

				this.override(["itemTemplate"], options);

				this.$list = this.$element;
			},

			render: function () {
				this.ParentCall();

				this.$list = this.$element;

				return this;
			},

			refresh: function () {
				this.update(this.items);

				return this;
			},

			update: function (items) {
				var self = this;
				var length = items.length;
				var gridLength = this.items.length;
				var maxLength = Math.max(length, gridLength);
				var i, count;

				for (i = 0, count = Math.min(length, gridLength); i < count; i++) {
					this.updateItem(items[i], i);
				}

				if (length < gridLength) {
					for (i = length; i < gridLength; i++) {
						this.removeItem(null, length);
					}
				} else if (length > gridLength) {
					for (i = gridLength; i < length; i++) {
						this.insertItem(items[i], i);
					}
				}

				this.updateIndex();

				//for (var i = 0; i < maxLength; i++) {
				//	if (i < length) {
				//		if (i < gridLength) {
				//			this.updateItem(items[i], i);
				//		} else {
				//			this.insertItem(items[i]);
				//		}
				//	} else {
				//		this.removeItem(null, length);
				//	}
				//}

				this.emit("update");

				return this;
			},

			clear: function () {
				for (var i = this.elements.length - 1; i >= 0; i--) {
					this.elements[i].remove();
				}

				this.elements = [];
				this.items = [];

				return this;
			},

			/**
			 *
			 * @param {Object} item
			 * @param {String} mode "single", "multiple"
			 */
			selectItem: function (item, mode) {
				mode = Util.coalesce(mode, "single");

				if (this.selectedClass) {
					if (mode == "single") {
						this.deselectItem();
					}

					var index = this.getItemIndex(item);

					if (~index) {
						var $element = this.elements[index];

						$element.addClass(this.selectedClass);
					}
				}
			},

			deselectItem: function (item) {
				if (this.selectedClass) {
					if (item) {
						var index = this.getItemIndex(item);

						if (~index) {
							var $element = this.elements[index];

							$element.removeClass(this.selectedClass);
						}
					} else {
						this.$("." + this.selectedClass).removeClass(this.selectedClass);
					}
				}
			},

			createItem: function (item) {
				var $item = UI.Template.$render(this.setting("itemTemplate"), { item: item, widget: this });

				$item.addClass(this.listItemClass);
				$item.data("index-key", this.indexKey(item));

				return $item;
			},

			getItemIndex: function (object) {
				//return this.indexOf(object);
				return this.getIndex(object);
			},

			getItem: function (object) {
				var index = this.getItemIndex(object);

				return ~index ? this.items[index] : null;
			},

			findItem: function (object) {
				for (var i = 0; i < this.items.length; i++) {
					if (Util.equal(object, this.items[i])) {
						return this.items[i];
					}
				}

				return null;
			},

			getElementItem: function (element) {
				var index = this.indexOfElement(element);

				return ~index ? this.items[index] : null;
			},

			insertItem: function (item, index) {
				var $element = this.createItem(item);

				if (index == 0) {
					if (this.elements.length) {
						$element.insertBefore(this.elements[0]);
					} else {
						$element.appendTo(this.$list);
					}

					this.items.unshift(item);
					this.elements.unshift($element);
				} else if (index < this.elements.length) {
					$element.insertBefore(this.elements[0]);

					this.items.splice(index, 0, item);
					this.elements.splice(index, 0, $element);
				} else {
					index = this.items.length;

					$element.appendTo(this.$list);
					this.items.push(item);

					this.elements.push($element);
				}

				this.appendIndex(item, index);

				this.refilter(item, index);

				return $element;
			},

			updateItem: function (item, index) {
				if (index === undefined) {
					index = this.getItemIndex(item);
				}

				if (this.elements[index] === undefined) {
					return null;
				}

				var $element = this.createItem(item);
				var element = this.elements[index];

				element.replaceWith($element);
				element.remove();

				this.elements[index] = $element;

				this.removeIndex(this.items[index], false);

				this.items[index] = item;

				this.appendIndex(item, index, false);

				this.refilter(item, index);

				return $element;
			},

			removeItem: function (item, index) {
				if (index === undefined) {
					index = this.getItemIndex(item);
				}

				if (this.elements[index] === undefined) {
					return null;
				}

				if (!item) {
					item = this.items[index];
				}

				this.showItem(item, index);

				var element = this.elements[index];

				this.removeIndex(item, false);

				this.elements.splice(index, 1);
				this.items.splice(index, 1);

				element.remove();

				this.updateIndex(index);

				return true;
			},

			showItem: function (item, index) {
				if (index === undefined) {
					index = this.getItemIndex(item);
				}

				var element = this.elements[index];

				if (element === undefined) {
					return false;
				}

				var display = element.css("display");

				if (display == "none") {
					element.css("display", "");
				}

				this.visibleItems[this.indexKey(item)] = true;

				return true;
			},

			hideItem: function (item, index) {
				if (index === undefined) {
					index = this.getItemIndex(item);
				}

				var element = this.elements[index];

				if (element === undefined) {
					return false;
				}

				var display = element.css("display");

				if (display != "none") {
					element.css("display", "none");
				}

				delete this.visibleItems[this.indexKey(item)];

				return true;
			},

			prependItem: function (item) {
				return this.insertItem(item, 0);
			},

			appendItem: function (item) {
				return this.insertItem(item);
			},

			setItem: function (item, callback) {
				var ret = null;
				var index = this.getItemIndex(item);

				if (~index) {
					ret = this.updateItem(item, index);
				} else {
					if (Util.isFunction(callback)) {
						index = this.getIndexFor(callback);

						ret = this.insertItem(item, index);
					} else {
						if (Util.isObject(callback)) {
							var options = callback;
							
							if (options.prepend) {
								ret = this.prependItem(item);
							} else if (options.append) {
								ret = this.appendItem(item);
							}
						}

						if (!ret) {
							ret = this.insertItem(item);
						}
					}
				}

				return ret;
			},

			setFilter: function (callback) {
				this.filterCallback = Util.isFunction(callback) ? callback : null;

				return this;
			},

			hasFilter: function () {
				return this.filterCallback !== null;
			},

			refilter: function (item, index) {
				var self = this;
				var items;

				if (item) {
					items = {};
					items[index] = item;
				} else {
					items = this.items;
				}

				if (this.hasFilter()) {
					Util.each(items, function (item, index) {
						if (self.filterCallback.call(self, item)) {
							self.showItem(item, index);
						} else {
							self.hideItem(item, index);
						}
					});
				} else {
					if (item) {
						this.showItem(item, index);
					} else {
						this.showItems();
					}
				}

				return this;
			},

			filterItems: function (callback) {
				var self = this;

				this.setFilter(callback);

				this.refilter();

				return this;
			},

			showItems: function () {
				var self = this;

				this.filterCallback = null;

				Util.each(this.items, function (item, index) {
					self.showItem(item, index);
				});

				return this;
			},

			getTotalCount: function () {
				return Object.keys(this.items).length;
			},

			getVisibleCount: function () {
				return Object.keys(this.visibleItems).length;
			},

			getIndexFor: function (callback) {
				var length = this.items.length;

				if (!length) {
					return 0;
				}

				for (var i = 0; i < length; i++) {
					if (callback(this.items[i])) {
						return i;
					}
				}

				return length;
			},

			indexOf: function (item) {
				for (var i = 0; i < this.items.length; i++) {
					if (this.indexOfCheck(item, this.items[i])) {
						return i;
					}
				}

				return -1;
			},

			indexOfElement: function (element) {
				element = $(element);

				if (!element.hasClass(this.listItemClass)) {
					element = element.parents("." + this.listItemClass).first();
				}

				if (!element.length) {
					return -1;
				}

				var key = element.data("index-key");

				if (Util.isDefined(key)) {
					var index = this.index[key];

					if (index !== undefined) {
						return index;
					}
				} else {
					element = element[0];

					for (var i = 0; i < this.elements.length; i++) {
						if (this.elements[i][0] == element) {
							return i;
						}
					}
				}

				return -1;
			},

			indexOfCheck: function (item, listItem) {
				return this.indexKey(item) == this.indexKey(listItem);
			},

			indexKey: function (item) {
				return item.id;
			},

			getIndex: function (item) {
				var index = this.index[this.indexKey(item)];

				return index !== undefined ? index : -1;
			},

			appendIndex: function (item, index, update) {
				update = Util.coalesce(update, true);

				this.index[this.indexKey(item)] = index;

				if (update) {
					this.updateIndex(index);
				}
			},

			removeIndex: function (item, update) {
				update = Util.coalesce(update, true);

				var index = this.getIndex(item);

				delete this.index[this.indexKey(item)];

				if (update && Util.isDefined(index)) {
					this.updateIndex(index);
				}

				return index;
			},

			updateIndex: function (from) {
				var items = this.items;
				from = Util.coalesce(from, 0);

				for (var i = from, count = items.length; i < count; i++) {
					this.appendIndex(items[i], i, false);
				}
			}
		});

		UI.Widget.List.Static({
			itemTemplate: null
		});
	}
);
