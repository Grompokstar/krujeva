Module.define(
	"UI.Plugin.Base",
	"Base.FieldForm",

	function () {
		NS("UI.Plugin");

		UI.Plugin.ChosenSelect = Class(UI.Plugin.Base, {

			source: null,

			sourceOptions: {},

			selectOptions: [],

			sourcefield: null,

			fieldClass: null,

			fieldBlock: null,

			blockSetValue: null,

			select: null,

			initialize: function (parent, options) {
				this.ParentCall();

				options = options || {};

				this.source = options.source;

				this.sourcefield = options.sourcefield;

				this.fieldClass = options.fieldClass;

				this.select = this.element.$element;

				if (options.sourceOptions) {

					this.sourceOptions = options.sourceOptions;
				}

				if (options.selectOptions) {

					this.selectOptions = options.selectOptions;
				}

				if (options.blockSetValue) {

					this.blockSetValue = options.blockSetValue;
				}

				if (options.block) {

					this.fieldBlock = options.block;
				}

				this.chosenSelect(options.value);

				var self = this;

				this.onChangeSelect = function (args) {
					self.onSelectChange(this, args);
				};

				this.select.on('change', this.onChangeSelect);
			},

			destroy: function () {
				this.select.off('change', this.onChangeSelect);

				this.source = null;

				this.sourcefield = null;

				this.fieldClass = null;

				this.fieldBlock = null;

				this.select = null;

				this.blockSetValue = null;

				this.ParentCall();
			},

			initBlockValue: function () {
				this.chosenSelect(this.getBlockValue());
			},

			getIdField: function () {
				var idField = null;

				for (var field in this.blockSetValue) if (this.blockSetValue.hasOwnProperty(field)) {

					var obj = this.blockSetValue[field];

					if (obj.sourceItem) {

						idField = field;

						break;
					}

				}

				return idField;
			},

			getBlockValue: function () {

				var idField = this.getIdField();

				if (!this.fieldClass) {
					return null;
				}

				var record = this.fieldClass.getRecord();

				if (!record[this.fieldBlock]) {
					return;
				}

				var items = record[this.fieldBlock];

				var ids = [];

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					ids.push(item[idField]);
				}

				return ids;
			},

			chosenSelect: function (value) {

				if (!this.element) {
					return;
				}

				value = value || this.getBlockValue();

				var options = [];

				var self = this;

				if (!this.source && this.selectOptions) {

					options = ['<option></option>'];

					Util.each(this.selectOptions, function (item) {

						var key = item.id ? item.id : item.tempId;

						options.push(Base.FieldForm.optionSelect(item[self.sourcefield], key, value));

					});

					this.element.$element.html(options.join('')).chosen().trigger('chosen:updated');

					return;
				}


				if (!this.source) {
					return;
				}

				var options = {};

				if (typeof this.sourceOptions == 'function') {

					options = this.sourceOptions();

				} else {

					options = this.sourceOptions;
				}

				this.source.load(options, function (items) {

					if (!this.element) {
						return;
					}

					options = ['<option></option>'];

					Util.each(items, function (item) {
						options.push(Base.FieldForm.optionSelect(item[self.sourcefield], item.id, value));
					});

					this.element.$element.html(options.join('')).chosen().trigger('chosen:updated');

				}.bind(this));
			},

			onSelectChange: function (caller, ev) {

				if (!this.fieldBlock) {
					return;
				}

				var value = $(caller).val() || [];

				var idField = this.getIdField();

				var oldRecord = Util.clone(this.fieldClass.getRecord());

				var oldBlock = oldRecord[this.fieldBlock] || [];

				this.fieldClass.clearBlock(this.fieldBlock);

				for (var i in value) if (value.hasOwnProperty(i)) {

					var id = value[i];

					var item = this.getSourceItem(id);

					if (!item) {
						continue;
					}

					var blockItem = {};

					if (this.blockSetValue) {

						for (var field in this.blockSetValue) if (this.blockSetValue.hasOwnProperty(field)) {

							var obj = this.blockSetValue[field];

							if (obj.currentItem) {

								blockItem[field] = this.fieldClass.getValue(obj.currentItem);

							} else if (obj.sourceItem) {

								blockItem[field] = item[obj.sourceItem];
							}

						}

					}

					var oldItem = this.findOldItem(oldBlock, blockItem);

					if (oldItem) {

						blockItem = oldItem;
					}

					this.fieldClass.addBlock(this.fieldBlock, blockItem, [], {render: true});
				}
			},

			findOldItem: function (oldItems, currentItem) {

				if (!Util.isArray(oldItems)) {
					return null;
				}

				var oldItem = null;

				for (var i in oldItems) if(oldItems.hasOwnProperty(i)) {

					var old = oldItems[i];

					var finded = true;

					for (var prop in currentItem) if (currentItem.hasOwnProperty(prop)) {

						if (currentItem[prop] != old[prop]) {
							finded = false;

							break;
						}

					}

					if (finded) {
						oldItem = Util.clone(old);
						break;
					}

				}

				return oldItem;
			},

			getSourceItem: function (id) {
				var item = null;

				var data = this.source.cachedData();

				for (var i in data.items) if (data.items.hasOwnProperty(i)) {

					if (data.items[i]['id'] == id) {

						item = data.items[i];
						break;
					}

				}

				return item;
			}

		});

	});

