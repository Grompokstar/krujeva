Module.define(
	"Base.Form",
	"KrujevaDict.PropertyDataType",

	function () {
		NS("KrujevaDict.UI");

		KrujevaDict.UI.PropertiesForm = Class(Base.Form, {
			template: "KrujevaDict/PropertiesForm",
			templateCategories: "KrujevaDict/Properties/Categories",

			categories: null,
			tree: {},
			categoryProperties: {}, //@индекс что бы можно было удалить легко
			filter: {},

			editableProperty: {}, //@все измененные и созданые свойства тут
			removedProperty: {},//@все удаленные свойства тоже тут

			fields: {
				brandid: 'brandid'
			},

			onChangeBrand: function () {
				this.setFieldsValue();

				this.filter = Util.merge(this.filter, this.getRecord(), true);

				//@clear all properties
				this.categories = null;
				this.tree = {};
				this.categoryProperties = {};
				this.editableProperty = {};
				this.removedProperty = {};

				this.loadCategories();
			},

			destroy: function () {

				this.categories = null;
				this.tree = {};
				this.categoryProperties = {};
				this.filter = {};
				this.editableProperty = {};
				this.removedProperty = {};

				this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				if (!this.categories) {
					this.loadCategories();
				}
			},

			filterChanged: function (caller) {

				var name = $(caller).attr('name');

				var value = $(caller).val();

				if (!name) {
					return;
				}

				if (value) {
					this.filter[name] = value;
				} else {
					delete this.filter[name];
				}

				this.loadCategories();
			},

			loadCategories: function () {

				var source = new KrujevaDict.Data.ProductProperties();

				var self = this;

				source.categories({options: {filter: this.filter}}, function (res) {

					if (res.items) {

						self.categories = {};

						self.tree = {};

						self.treeCategories(res.items);

						var html = UI.Template.render(self.templateCategories, {items: res.items, widget: self});

						self.$name('categories').html(html);
					}

				})
			},

			treeCategories: function (items) {

				for (var i in items) if (items.hasOwnProperty(i)) {

					var item = items[i];

					this.categories[item['id']] = item;

					if (item['parentid']) {

						if (!this.tree[item.parentid]) {

							this.tree[item.parentid] = [];
						}

						this.tree[item.parentid].push(item['id']);
					}

					if (item['properties']) {

						for (var j in item['properties']) if (item['properties'].hasOwnProperty(j)) {

							var property = item['properties'][j];

							this.addProperty(property['categoryid'], false, property, false);
						}

					}

					if (item.items) {

						this.treeCategories(item.items);
					}

				}

			},

			getChildrenCategoryIds: function (parentid, result) {

				result = result || [];

				if (!this.tree[parentid]) {
					return result;
				}

				for (var i in this.tree[parentid]) if (this.tree[parentid].hasOwnProperty(i)) {

					var id = this.tree[parentid][i];

					result.push(id);

					this.getChildrenCategoryIds(id, result);
				}

				return result;
			},

			addPropertyClick: function (caller) {

				var categoryid = $(caller).data('id');

				if (!categoryid) {
					return;
				}

				this.addProperty(categoryid);
			},

			removePropertyClick: function (caller) {

				var categoryid = $(caller).data('categoryid');

				var propertyid = $(caller).data('id');

				if (!categoryid || !propertyid) {
					return;
				}

				this.removeProperty(categoryid, propertyid);
			},

			addProperty: function (categoryid, isNewProperty, data, updateDOM) {

				isNewProperty = typeof isNewProperty == 'undefined' ? true : isNewProperty;

				data = typeof data == 'undefined' ? {} : data;

				updateDOM = typeof updateDOM == 'undefined' ? true : updateDOM;

				var categories = this.getChildrenCategoryIds(categoryid);

				var propertyData = Util.merge({
					tempId: DateTime.getCurrentDate('X') + System.NumSeq.next('Category.Property'),
					categoryid: categoryid,
					datatype: KrujevaDict.PropertyDataType.String,
					isenum: 0,
					isNewProperty: isNewProperty, //Если из сервера пришло это свойство - тогда не новое
				}, data, true);

				//@index for Remove Propery
				if (!this.categoryProperties[propertyData.categoryid]) {
					this.categoryProperties[propertyData.categoryid] = [];
				}

				this.categoryProperties[propertyData.categoryid].push(propertyData);

				//@editable property
				if (propertyData['isNewProperty']) {

					this.editableProperty[propertyData['tempId']] = propertyData;
				}

				if (!updateDOM) {
					return;
				}

				console.log('go end');

				//@Add main property
				var html = UI.Template.render("KrujevaDict/Properties/MainProperty", {categoryid: propertyData.categoryid, data: propertyData});

				this.$name('main_properies_list_'+ categoryid).append(html);

				//@ add Child property
				for (var i in categories) if (categories.hasOwnProperty(i)) {

					html = UI.Template.render("KrujevaDict/Properties/ChildrenProperty", {categoryid: propertyData.categoryid, data: propertyData});

					this.$name('children_properies_list_' + categories[i]).append(html);
				}
			},

			removeProperty: function (categoryid, propertyid) {

				this.$name('propery_id_'+ propertyid).remove();

				if (!this.categoryProperties[categoryid]) {
					return;
				}

				var propertyData = this.editableProperty[propertyid];

				var list = this.categoryProperties[categoryid];

				if (propertyData) {

					if (!propertyData.isNewProperty) {

						this.removedProperty[propertyid] = Util.clone(propertyData);
					}

					delete this.editableProperty[propertyid];

				} else {

					if (!this.categoryProperties[categoryid]) {
						return;
					}

					propertyData = null;

					for (var j in list) if (list.hasOwnProperty(j)) {

						if (list[j]['tempId'] == propertyid) {
							propertyData = list[j];
							break;
						}

					}

					if (propertyData) {

						this.removedProperty[propertyid] = Util.clone(propertyData);
					}
				}

				var index = null;

				for (var i in list) if (list.hasOwnProperty(i)) {

					if (list[i]['tempId'] == propertyid) {
						index = i;
						break;
					}

				}

				if (index !== null) {
					this.categoryProperties[categoryid].splice(index, 1);
				}

				if (!this.categoryProperties[categoryid].length) {
					delete this.categoryProperties[categoryid];
				}

				console.log(this.categoryProperties);
				console.log(this.editableProperty);
				console.log(this.removedProperty);
			},

			onChangeProperty: function (caller) {

				var categoryid = $(caller).data('categoryid');

				var propertyid = $(caller).data('id');

				if (!this.categoryProperties[categoryid]) {
					return;
				}

				var propertyData = this.editableProperty[propertyid];

				if (!propertyData) {

					var list = this.categoryProperties[categoryid];

					propertyData = null;

					for (var j in list) if (list.hasOwnProperty(j)) {

						if (list[j]['tempId'] == propertyid) {
							propertyData = list[j];
							break;
						}

					}

					if (propertyData) {

						this.editableProperty[propertyid] = Util.clone(propertyData);
					}
				}

				if (!propertyData) {
					return;
				}

				var value = $(caller).is(':checkbox') ? $(caller).is(':checked'): $(caller).val();

				this.editableProperty[propertyid][$(caller).attr('name')] = value;

				this.editableProperty[propertyid]['isNewProperty'] = true;

				//@main property add isNew
				var $mainprop = this.$name('propery_id_'+ propertyData['tempId']);

					$mainprop.find('.main-new-property-icon').remove();

					$mainprop.prepend('<div class="main-new-property-icon"></div>');

				//@render Children Property
				var categories = this.getChildrenCategoryIds(categoryid);

				for (var i in categories) if (categories.hasOwnProperty(i)) {

					var $html = UI.Template.$render("KrujevaDict/Properties/ChildrenProperty", {categoryid: categoryid, data: this.editableProperty[propertyid]});

					var $element = this.$name('children_properies_list_' + categories[i]).find('[name=propery_id_'+ propertyData['tempId']+']');

					$element.replaceWith($html);

					$element.remove();
				}

			},

			getParentProperties: function (childid, properies) {

				var category = this.categories[childid];

				if (!category) {
					return properies;
				}

				if (!category.parentid) {
					return properies;
				}

				var props = this.categoryProperties[category.parentid];

				if (props && Util.isArray(props)) {

					for (var i in props) if (props.hasOwnProperty(i)) {

						properies.push(props[i]);

					}

				}

				return this.getParentProperties(category.parentid, properies);
			},

			saveClick: function () {

				var loader = Loader.start(this.$name('form-loader'));

				var source = new KrujevaDict.Data.ProductProperties();

				source.insert({
						editableProperty: this.editableProperty,
						removedProperty: this.removedProperty},
						function (res) {

						Loader.end(loader, function () {

							if (res) {
								Alert.success('Сохранено');

								this.editableProperty = {};
								this.removedProperty = {};

								this.categories = null;
								this.tree = {};
								this.categoryProperties = {};

								this.render();
							}

						}.bind(this));


				}.bind(this));


			}


		});
	});