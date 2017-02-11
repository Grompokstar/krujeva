Module.define(
	"Base.FieldForm",

	function () {
		NS("Base");

		Base.Form = Class(Base.FieldForm, {
			name: "Form",

			fields: {},

			rules:{},

			blocks:{},

			render: function() {
				this.ParentCall();

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					this.renderBlock(blockName);
				}

				return this;
			},

			renderBlock: function (block) {

				if (!this[block]) {
					return;
				}

				this.$name(block).html("");

				if (Util.isArray(this[block])) {

					for (var i in this[block]) if (this[block].hasOwnProperty(i)) {
						this[block][i].render().appendTo(this.$name(block));
					}

				} else if (Util.isObject(this[block])) {

					this[block].render().appendTo(this.$name(block));

				}
			},

			validate: function (rule) {
				var valid = this.ParentCall();

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					if (!this[blockName]) {
						continue;
					}

					if (Util.isArray(this[blockName])) {

						for (var i in this[blockName]) if (this[blockName].hasOwnProperty(i)) {
							if (!this[blockName][i].validate(rule)) {
								valid = false;
							}
						}

					} else if (Util.isObject(this[blockName])) {

						if (!this[blockName].validate(rule)) {
							valid = false;
						}

					}
				}

				return valid;
			},

			getRecord: function() {

				var record = Util.clone(this.itemvalue);

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					if (!this[blockName]) {
						continue;
					}

					if (Util.isArray(this[blockName])) {

						record[blockName] = [];

						for (var i in this[blockName]) if (this[blockName].hasOwnProperty(i)) {

							record[blockName].push(this[blockName][i].getRecord());
						}

					} else if (Util.isObject(this[blockName])) {
						record[blockName] = this[blockName].getRecord();
					}
				}

				return record;
			},

			setFieldsValue: function () {

				for(var field in this.fields) if(this.fields.hasOwnProperty(field)) {

					var fieldKey = this.getFieldUniqueId(field);

					var $field = this.$name(fieldKey);

					var value = null;

					if (this.customFields[fieldKey]) {

						value = this.customFields[fieldKey].val();

					} else if ($field) {

						if ($field.attr("type") == "checkbox") {

							value = $field.is(":checked");
						} else {

							value = $field.val() || null;

							if (~['true','false'].indexOf(value)) {

								switch (value) {
									case "true": value = true; break;
									case "false": value = false; break;
								}
							}
						}
					}

					this.setValue(field, value);
				}

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					if (!this[blockName]) {
						continue;
					}

					if (Util.isArray(this[blockName])) {

						for (var i in this[blockName]) if (this[blockName].hasOwnProperty(i)) {

							this[blockName][i].setFieldsValue();

						}

					} else if (Util.isObject(this[blockName])) {
						this[blockName].setFieldsValue();
					}
				}
			},

			setRecord: function(attributes, updateDOM, addBlocks) {
				if (typeof attributes != "object") {
					attributes = {};
				}

				addBlocks = typeof addBlocks == "undefined" ? true : addBlocks;

				this.clear();

				for (var field in this.fields) if (this.fields.hasOwnProperty(field)) {
					var value = typeof attributes[field] == "undefined" ? null : attributes[field];
					this.setValue(field, value, updateDOM);
				}

				if (!addBlocks) {
					return;
				}

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					if (!attributes[blockName]) {
						continue;
					}

					if (Util.isArray(attributes[blockName])) {

						for (var i in attributes[blockName]) if (attributes[blockName].hasOwnProperty(i)) {

							this.addBlock(blockName, attributes[blockName][i], [], {render: updateDOM});
						}

					} else if (Util.isObject(attributes[blockName])) {

						this.addBlock(blockName, attributes[blockName], {}, {render: updateDOM});
					}
				}

			},

			addBlock: function (blockName, data, mode, options) {
				options = options || {};

				if (!this.blocks[blockName]) {
					return null;
				}

				var block = Util.create(this.blocks[blockName], [this]);

				block.setRecord(data);

				if (!this[blockName]) {
					this[blockName] = mode;
				}

				if (Util.isArray(this[blockName])) {

					var index = this[blockName].length;

					block.name = block.name + "_" + index;
					block.index = index + 1;

					this[blockName][index] = block;

					block.on("destroy", function () {

						if (Util.isArray(this[blockName])) {

							var blockIndex = null;

							Util.each(this[blockName], function (Block, indexBlock) {

								if (Block == block) {
									blockIndex = indexBlock;
									return false;
								}

							});

							if (blockIndex) {
								this[blockName].splice(blockIndex, 1);
							}

							this.recalculateIndex(blockName);
						}

					}.bind(this));

				} else {

					if (this[blockName] && typeof this[blockName].destroy == 'function') {

						this[blockName].destroy();

						this[blockName] = null;
					}

					this[blockName] = block;

					block.on("destroy", function () {
						if (this[blockName]) {
							this[blockName] = null;
							delete this[blockName];
						}
					}.bind(this));
				}

				if (options.render) {
					block.render().appendTo(this.$name(blockName));
				}

				return block;
			},

			recalculateIndex: function (blockName) {

				Util.each(this[blockName], function (block, index) {

					block.index = parseInt(index) + 1;
				});
			},

			clearBlock: function (blockName) {

				if (!this.blocks[blockName]) {
					return null;
				}

				if (!this[blockName]) {
					return null;
				}

				if (Util.isArray(this[blockName])) {

					for (var i = 0; i < this[blockName].length; i++) {

						var block = this[blockName][i];

						this[blockName].splice(i, 1);

						block.destroy();

						i--;
					}

				} else if (Util.isObject(this[blockName])) {

					this[blockName].destroy();

				}

				delete this[blockName];
			},

			setValue: function(field, value, updateDOM) {

				if (!Util.isArray(value) && !Util.isObject(value) && !~['boolean', 'object'].indexOf(typeof value)) {
					value = Html.decode(value);
				}

				// Util.isNumber([]) = true
				if (!Util.isArray(value) && Util.isNumber(value) && value.match(/^[0-9.]+$/)) {

					//@multi inn
					if ((''+ value).length > 6) {

						value = ('' + value).trim();

					} else {

						value = parseFloat(value);
					}
				}

				this.itemvalue[field] = value;

				if (field == 'id' && value == null) {
					delete this.itemvalue[field];
				}

				if (updateDOM) {

					var fieldKey = this.getFieldUniqueId(field);

					if (this.customFields[fieldKey]) {

						this.customFields[fieldKey].val(value)

					} else {

						var $element = this.$name(fieldKey);

						if ($element) {

							if ($element.is(':checkbox')) {

								$element.prop('checked', value);

								$element.trigger('change');

							} else {

								$element.val(value);

							}

						} else {
							console.log("Error setValue on DOM", field, this.getFieldUniqueId(field), value);
						}

					}
				}
			},

			getValue: function(field, encode) {
				encode = (typeof encode == 'undefined') ? true : encode;

				var value = this.itemvalue[field];

				if (typeof value === 'undefined') {
					return null;
				}

				if (Util.isArray(value) || Util.isObject(value) || typeof value == "boolean") {
					return value;
				}

				if (!encode) {
					return value;
				}

				return Html.encode(value);
			},

			getFieldError: function (field, glue, htmlOptions) {
				htmlOptions = htmlOptions || {};

				var errors = this.getStringErrorsField(field, glue);

				if (!errors) {
					return '';
				}

				if (htmlOptions.class) {
					htmlOptions.class += ' error-field-validate-attributes';
				} else {
					htmlOptions.class = 'error-field-validate-attributes';
				}

				return '<div ' + this.getHtmlOptions(htmlOptions) + '>' + errors + '</div>';
			},

			getSummaryErrors: function (glue, htmlOptions) {
				htmlOptions = htmlOptions || {};

				if (!Object.keys(this.validateerrors).length) {
					return '';
				}

				if (htmlOptions.class) {
					htmlOptions.class += ' error-summary-validate-attributes';
				} else {
					htmlOptions.class = 'error-summary-validate-attributes';
				}

				return '<div ' + this.getHtmlOptions(htmlOptions) + '>' + this.getStringErrors(glue) + '</div>';
			},

			clear: function () {
				this.validateerrors = {};

				for (var field in this.fields) if (this.fields.hasOwnProperty(field)) {
					this.setValue(field, null, true);
				}

				for (var blockName in this.blocks) if (this.blocks.hasOwnProperty(blockName)) {

					if (!this[blockName]) {
						continue;
					}

					if (Util.isArray(this[blockName])) {

						for (var i in this[blockName]) if (this[blockName].hasOwnProperty(i)) {

							this[blockName][i].setRecord();
							this[blockName][i].destroy();
						}

					} else if (Util.isObject(this[blockName])) {
						this[blockName].setRecord();
					}

					this[blockName] = null;
				}
			},

			isNew: function () {
				return !this.getValue('id');
			}
		});
	});