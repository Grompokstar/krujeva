Module.define(
	"Base.Form",

	function () {
		NS("KrujevaDict.UI.Products");

		KrujevaDict.UI.Products.EditForm = Class(Base.Form, {
			template: "KrujevaDict/Products/EditForm",

			//@ базовые поля
			fields : {
				id: 'id',
				oldid: 'oldid',
				productcategoryid: 'Категория товара',
				producttaskid: 'Решаемая задача',
				brandid: 'Бренд'
			},

			rules: {
				edit: {
					productcategoryid: ['require'],
					brandid: ['require']
				}
			},

			//@ здесь храним свойства и ссылки на эти элементы DOM
			propertyElements: {},

			//@ храним значения свойств
			propertyValues: {},

			//@ здесь лежит текстарея блоки - которые увеличиваются в размере от контента
			resizeElements: {},

			//@ ссылки на списки
			selectChoosen: {},

			//@ здесь лежит дата по обрамлению изображения (превью)
			cropData: {},

			//@ здесь хранятся варианты списков - если поле это список
			propertyListOptions: {},

			//@oldPhoto
			oldPhoto: null,

			defaultProps: {
				name: 'Название товара',
				brandid: 'Бренд',
				price: '1200'
			},

			newFile: null,

			destroy: function () {

				this.propertyElements = {};

				this.resizeElements = {};

				this.selectChoosen = {};

				this.propertyValues = {};

				this.propertyListOptions = {};

				this.ParentCall();
			},

			onChangeSelectCategory: function () {
				this.setFieldsValue();

				this.getPropertiesByCategory(this.getValue('productcategoryid'));
			},

			onChangeBrand: function () {
				this.setFieldsValue();

				var brandid = this.getValue('brandid');

				var source = new KrujevaDict.Data.Brands();

				var item = source.cachedData(brandid);

				this.propertyValues['brandid'] = item['name'];

				this.onChangeProperty('brandid', this.propertyValues['brandid']);

				//@refresh Category fields
				this.setValue('productcategoryid', null, true);

				this.propertyElements = {};

				this.resizeElements = {};

				this.selectChoosen = {};

				this.propertyListOptions = {};

				this.$name('dynamic-fields').empty();

				this.getChoosenSelect('productcategoryid').chosenSelect();
			},

			render: function () {

				if (this.getArgumentString() && !this.isAppended()) {

					var id = this.getArgumentString();

					this.getProductPack(id);
				}

				this.ParentCall();

				if (this.newFile) {

					this.onFileChange(this.newFile);
				}

				return this;
			},

			afterRender: function (options) {
				this.ParentCall();

				if (options && options.item) {

					var iscopy = typeof options.copy == 'undefined' ? false : options.copy;

					this.getProductPack(options.item['id'], iscopy);

				}

				this.drawPropertiesByElements();
			},

			getProductPack: function (id, iscopy) {

				var loader = Loader.start(this.$name('form-loader'));

				var source = new KrujevaDict.Data.Products();

				var self = this;

				source.pack({id: id}, function (result) {

					for (var i in result['values']) if (result['values'].hasOwnProperty(i)) {

						var item = result['values'][i];

						self.propertyValues[item['productpropertyid']] = item['value'];
					}

					self.setRecord(result);

					self.propertyListOptions = result['listoptions'];

					self.drawProperties(result.properties);

					self.oldPhoto = result.photo;

					self.render();

					if (!iscopy) {

						self.setArgumentString(id);

					} else {

						self.setValue('oldid', id, true);

						self.setValue('id', null, true);

					}

				});

			},

			getPropertiesByCategory: function (categoryid) {

				var source = new KrujevaDict.Data.ProductProperties();

				source.properties({categoryid: categoryid}, function (result) {

					if (result && result.listoptions) {

						for (var i in result.listoptions) if (result.listoptions.hasOwnProperty(i)) {

							this.propertyListOptions[i] = result.listoptions[i].slice();
						}
					}

					if (result && result.props) {

						this.drawProperties(result.props);
					}

				}.bind(this));
			},

			drawProperties: function (properties) {

				this.propertyElements = {};

				var $html = $([]);

				for (var i in properties) if (properties.hasOwnProperty(i)) {

					var prop = properties[i];

					var template = null;

					switch (+prop.datatype) {

						case KrujevaDict.PropertyDataType.String:
							template = "String";
							break;
						case KrujevaDict.PropertyDataType.Int:
							template = "String";
							break;
						case KrujevaDict.PropertyDataType.Float:
							template = "String";
							break;
						case KrujevaDict.PropertyDataType.Text:
							template = "Text";
							break;
						case KrujevaDict.PropertyDataType.CheckBox:
							template = "CheckBox";
							break;
					}

					if (!template) {
						continue;
					}

					var $element = UI.Template.$render("KrujevaDict/Products/Properties/" + template, {prop: prop, widget: this});

					$html = $html.add($element);

					this.propertyElements[prop.id] = {
						property: prop,
						$element: $element
					};
				}

				this.$name('dynamic-fields').empty();

				$html.appendTo(this.$name('dynamic-fields'));

				this.bindProperties(this.propertyElements);
			},

			drawPropertiesByElements: function () {
				var props = [];

				for (var i in this.propertyElements) if (this.propertyElements.hasOwnProperty(i)) {

					props.push(this.propertyElements[i]['property']);

					//@off elements
					this.propertyElements[i]['$element'].find('input, textarea, select').off();
				}

				this.drawProperties(props);
			},

			bindProperties: function (properties) {

				for (var i in properties) if (properties.hasOwnProperty(i)) {

					var prop = properties[i];

					var property = prop.property;

					//@enum
					if (property.isenum) {
						continue;
					}

					switch (+property.datatype) {

						case KrujevaDict.PropertyDataType.Text:

							if (this.resizeElements[property.id]) {

								this.resizeElements[property.id].destroy();

								delete this.resizeElements[property.id];
							}

							this.resizeElements[property.id] = new FluidTextArea(prop['$element'].find('textarea').get(0));

							break;


						case KrujevaDict.PropertyDataType.Int:
						case KrujevaDict.PropertyDataType.Float:

							prop['$element'].find('input').keydown(function (e) {

								if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: Ctrl+A
									(e.keyCode == 65 && e.ctrlKey === true) || // Allow: Ctrl+C
									(e.keyCode == 65 && e.keyCode === 91) || // Allow: Ctrl+C
									(e.keyCode == 67 && e.ctrlKey === true) || // Allow: Ctrl+X
									(e.keyCode == 88 && e.ctrlKey === true) || // Allow: home, end, left, right
									(e.keyCode >= 35 && e.keyCode <= 39)) {
									return;
								}

								if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
									e.preventDefault();
								}

							});

							break;
					}

				}

				var self = this;

				//@change properties - auto save values
				for (var j in properties) if (properties.hasOwnProperty(j)) {

					var prop = properties[j];

					var property = prop.property;

					switch (+property.datatype) {

						case KrujevaDict.PropertyDataType.String:
						case KrujevaDict.PropertyDataType.Text:
						case KrujevaDict.PropertyDataType.Int:
						case KrujevaDict.PropertyDataType.Float:

							var $element = prop['$element'].find('input, textarea, select');

							if (property.isenum) {

								if (self.selectChoosen[property.id]) {

									self.selectChoosen[property.id].destroy();

									delete self.selectChoosen[property.id];
								}

								self.selectChoosen[property.id] = new UI.Widget.Text(this, {element: $element});

								var value = self.propertyValues[property.id] ? self.propertyValues[property.id] : null;

								var selectOptions = self.propertyListOptions[property.id] ? self.propertyListOptions[property.id] : [];

								self.selectChoosen[property.id].use(UI.Plugin.ChosenSelect, {
									sourcefield: 'value',
									value: value,
									selectOptions: selectOptions
								});

								$element.on('change', function () {

									var $el = $(this);

									self.propertyValues[$el.data('id')] = $el.val();

									self.onChangeProperty($el.data('id'), $el.val());
								});

							} else {

								$element.on('keyup', function () {

									var $el = $(this);

									self.propertyValues[$el.data('id')] = $el.val();

									self.onChangeProperty($el.data('id'), $el.val());
								});

							}
							break;


						case KrujevaDict.PropertyDataType.CheckBox:

							prop['$element'].find('input').on('change', function () {

								var $el = $(this);

								self.propertyValues[$el.data('id')] = $el.is(':checked');

								self.onChangeProperty($el.data('id'), self.propertyValues[$el.data('id')]);
							});
							break;
					}

				}

			},

			getValueByPropertyCode: function (code, defaultValue) {

				defaultValue = typeof defaultValue == 'undefined' ? true: defaultValue;

				if (this.propertyValues[code]) {

					return this.propertyValues[code];
				}

				var property = null;

				for (var i in this.propertyElements) if (this.propertyElements.hasOwnProperty(i)) {

					var prop = this.propertyElements[i]['property'];

					if (prop['code'] == code) {

						property = prop;

						break;
					}
				}

				if (property && this.propertyValues[property['id']]) {

					return this.propertyValues[property['id']];
				}

				if (!defaultValue) {
					return null;
				}

				return this.defaultProps[code] ? this.defaultProps[code] : '';
			},

			onChangeProperty: function (id, value) {

				var prop = this.propertyElements[id];

				if (prop) {

					var code = prop['property']['code'];

				} else {

					var code = id;

				}

				var defaultProps = this.defaultProps;

				for (var i in defaultProps) if (defaultProps.hasOwnProperty(i)) {

					if (i != code) {
						continue;
					}

					var defaultValue = value ? value : defaultProps[code];

					this.$name('preview-'+ code).html(defaultValue);
				}
			},

			onFileChange: function (input, ev) {
				var inputfile = input;

				if (!inputfile.files.length) {
					return;
				}

				var files = [];

				for (var i = 0, j = inputfile.files.length; i < j; i++) {
					files.push(inputfile.files[i]);
				}

				if (!files.length) {
					return;
				}

				var file = files[0];

				var reader = new FileReader();

				var preview = $('<img>');

				reader.onloadend = function () {
					preview.attr('src', reader.result);

					var $cnt = this.$name('photo-product-preview').empty();

					preview.appendTo($cnt);

					$cnt.css('width', preview.width());


					this.newFile = inputfile;

					this.oldPhoto = null;

					this.useCropImage(preview);

				}.bind(this);

				if (file) {
					reader.readAsDataURL(file);
				} else {
					preview.attr('src', '');
				}
			},

			useCropImage: function (img) {

				var self = this;

				img.cropper({
					aspectRatio: 1,
					zoomOnTouch: false,
					zoomOnWheel: true,
					viewMode: 0,
					minCropBoxWidth: 120,
					minContainerWidth: 100,
					scalable: false,

					preview: this.$name('preview-image').empty(),

					crop: function (e) {

						//@current Width
						var currentWidth = self.$name('photo-product-preview').width();

						var $cropper = self.$name('photo-product-preview').find('.cropper-canvas');

						var $cropperBox = self.$name('photo-product-preview').find('.cropper-crop-box');

						var imageWidth = $cropper.width();

						var offsetLeft = parseInt($cropper.css('left').replace('px', ''));

						var offsetTop = parseInt($cropper.css('top').replace('px', ''));



						self.cropData['x'] = parseInt($cropperBox.css('left').replace('px', ''));

						self.cropData['y'] = parseInt($cropperBox.css('top').replace('px', ''));

						//self.cropData['y'] = e.y < 0 ? 0 : parseInt(e.y);
						//self.cropData['x'] = e.x < 0 ? 0 : parseInt(e.x);

						self.cropData['offsetLeft'] = offsetLeft;

						self.cropData['offsetTop'] = offsetTop;

						self.cropData['scale'] = parseInt(imageWidth * 100 / currentWidth);

						self.cropData['currentWidth'] = currentWidth;


						self.cropData['width'] = parseInt($cropperBox.width());

						//нам же квадрат нужен ебты
						self.cropData['height'] = self.cropData['width'];

						//self.cropData['height'] = e.height < 0 ? 0 : parseInt(e.height);
					}

				});

				if (Object.keys(self.cropData).length) {

					img.cropper('setData', self.cropData)
				}
			},

			addSelectValueClick: function (caller) {

				var id = $(caller).data('id');

				var value = prompt('Введите значение списка');

				if (!value) {
					return;
				}

				//@change value type
				var property = this.propertyElements[id];

				if (!property) {
					return;
				}

				switch (+property['property']['datatype']) {

					case KrujevaDict.PropertyDataType.Int:
					case KrujevaDict.PropertyDataType.CheckBox:
						value = parseInt(value);
						break;

					case KrujevaDict.PropertyDataType.Float:
						value = parseFloat((''+value).replace(/,/g, '.'));
						break;
				}

				if (!value) {
					return;
				}

				//@add Options
				var tempId = DateTime.getCurrentDate('X');

				if (!this.propertyListOptions[id]) {
					this.propertyListOptions[id] = [];
				}

				this.propertyListOptions[id].push({
					value: value,
					tempId: tempId
				});

				//@set value
				this.propertyValues[id] = tempId;

				//@todo refresh list
				if (this.selectChoosen[id]) {

					var choosen = this.selectChoosen[id].plugin(UI.Plugin.ChosenSelect);

					choosen.selectOptions = this.propertyListOptions[id];

					choosen.chosenSelect(tempId);
				}
			},

			getNewListItems: function () {

				var resultOptions = {};

				for (var i in this.propertyElements) if (this.propertyElements.hasOwnProperty(i)) {

					var property = this.propertyElements[i]['property'];

					if (!property.isenum) {
						continue;
					}

					var listOptions = this.propertyListOptions[property.id];

					if (!listOptions) {
						continue;
					}

					for (var j in listOptions) if (listOptions.hasOwnProperty(j)) {

						var option = listOptions[j];

						if (option['id']) {
							continue;
						}

						if (!resultOptions[property['id']]) {
							resultOptions[property['id']] = [];
						}

						resultOptions[property['id']].push(option);
					}
				}

				return resultOptions;
			},

			getRecord: function () {
				var record = this.ParentCall();

				record['listitems'] = Util.clone(this.getNewListItems());

				record['properties'] = Util.clone(this.propertyValues);

				record['cropdata'] = Util.clone(this.cropData);

				return record;
			},

			validate: function (rule) {
				var valid = this.ParentCall();

				var record = this.getRecord();

				if (!record.id && !record.oldid && !this.newFile) {
					this.setError('photo', 'Загрузите фотографию');
					valid = false;
				}

				//@name code
				if (!this.getValueByPropertyCode('name', false)) {
					this.setError('name', 'Укажите название товара');
					valid = false;
				}

				return valid;
			},

			removeClick: function () {

				if (!confirm('Вы действительно хотите удалить товар?')) {

					return;
				}

				var source = new KrujevaDict.Data.Products();

				var record = this.getRecord();

				var loader = Loader.start(this.$name('form-loader'));

				var self = this;

				source.remove({id: record['id']}, function (result) {

					Loader.end(loader, function () {

						if (result && result.item) {

							Alert.success('Успешно удалено');

							self.parent.open('list');

						} else {

							Alert.error('Не удалось удалить');
						}

					});

				});
			},

			setFieldsValue: function () {
				this.ParentCall();

				//@save by fields
				for (var i in this.propertyElements) if (this.propertyElements.hasOwnProperty(i)) {

					var prop = this.propertyElements[i];

					var $el = prop['$element'].find('input, select, textarea');

					var property = prop['property'];

					var value = $el.val();

					if (property['datatype'] == KrujevaDict.PropertyDataType.CheckBox) {

						value = $el.is(':checked');
					}

					this.propertyValues[property['id']] = value;
				}
			},

			saveClick: function () {
				this.setFieldsValue();

				if (!this.validate('edit')) {

					this.render();

					return;

				} else {

					this.hideErrors();
				}

				var source = new KrujevaDict.Data.Products();

				var record = this.getRecord();

				var method = (record.id) ? 'update' : 'insert';

				var files = this.newFile ? [
					{name: 'file', file: this.newFile}
				] : [];

				var loader = Loader.start(this.$name('form-loader'));

				var self = this;

				Xhr.upload({
					url: source.url + method,
					files: files,
					data: {item: JSON.stringify(record)}
				}, function (result, response) {

					result = result.data;

					Loader.end(loader, function () {

						if (result && result.item) {

							Alert.success('Успешно сохранено');

							self.parent.open('list');

						} else {

							Alert.error('Не удалось сохранить');
						}

					});

				}.bind(this));
			},

			cancelClick: function () {
				this.parent.open('list');
			}
		});
	});