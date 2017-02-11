Module.define(
	"Base.Page",

	function () {
		NS("Base");

		Base.FormValidate = Class(Base.Page, {
			validateerrors: {},

			itemvalue: {},

			rules: ['require', //обязательное поле [require]
				'min', //Минимум символов  [min=10]
				'max', //Максимум символов [max=5]
				'integerOnly', //Только целое число [integerOnly]
				'floatOnly' //число c плавающей точкой [floatOnly]

				//'email' //проверяем на почту [email]
			], ruleName: null,

			getErrors: function () {
				this.validate();
				return this.validateerrors;
			},

			getStringErrorsField: function (field, glue) {
				var resultArray = [];
				glue = glue || '<br/>';

				var errors = this.getErrors();

				if (typeof errors[field] == "undefined") {
					return '';
				}

				for (var j in errors[field]) if (errors[field].hasOwnProperty(j)) {
					resultArray.push(errors[field][j]);
				}

				return resultArray.join(glue);
			},

			getStringErrors: function (glue) {
				var resultArray = [];
				glue = glue || '<br/>';

				var errors = this.getErrors();
				for (var i in errors) if (errors.hasOwnProperty(i)) {
					for (var j in errors[i]) if (errors[i].hasOwnProperty(j)) {
						resultArray.push(errors[i][j]);
					}
				}

				return resultArray.join(glue);
			},

			validate: function (ruleName) {
				this.validateerrors = {};
				ruleName = ruleName || this.ruleName;

				if (!ruleName) {
					return true;
				}

				this.ruleName = ruleName;

				if (typeof this.rules[ruleName] === 'undefined') {
					console.log('not found rule ' + ruleName, this);
					return true;
				}

				for (var name in this.rules[ruleName]) if (this.rules[ruleName].hasOwnProperty(name)) {
					var attributeRules = this.parseRules(this.rules[ruleName][name]) || [];

					if (!name || !attributeRules.length) {
						continue;
					}

					for (var i in attributeRules) {
						this.validateRule(name, attributeRules[i]);
					}
				}

				if (Object.keys(this.validateerrors).length) {
					return false;
				}

				return true;
			},

			parseRules: function (attributeRules) {
				var rules = [];

				for (var i in attributeRules) if (attributeRules.hasOwnProperty(i)) {
					var rule = attributeRules[i].split('[');
					var error = rule[1] ? rule[1] + "" : "";
					rule = rule[0].split('=') || [];

					if (typeof rule[0] !== 'undefined') {
						rules.push({
							key: rule[0],
							value: ((typeof rule[1] !== 'undefined') ? rule[1] : null),
							error: error.replace(']', '')
						});
					}

				}

				return rules;
			},

			validateRule: function (name, rule) {
				switch (rule.key) {
					case 'require':
						this.requireValidate(name, rule);
						break;

					case 'min':
						this.minValidate(name, rule);
						break;

					case 'max':
						this.maxValidate(name, rule);
						break;

					case 'integerOnly':
						this.integerOnlyValidate(name, rule);
						break;

					case 'floatOnly':
						this.floatOnlyValidate(name, rule);
						break;
				}

			},

			getAttribute: function (name, toLowerCase) {
				toLowerCase = (typeof toLowerCase == 'undefined') ? true : false;
				var attributes = this.fields;

				if (typeof attributes === 'undefined') {
					return name.toLowerCase();
				}

				if (typeof attributes[name] === 'undefined') {
					return name.toLowerCase();
				}

				if (toLowerCase) {
					return attributes[name].toLowerCase();
				}

				return attributes[name];
			},

			getValueField: function (name) {
				var value = this.itemvalue[name];

				if (typeof value === 'undefined') {
					return null;
				}

				if (value === null) {
					return null;
				}

				if (typeof value.length !== 'undefined' && !value.length) {
					return null;
				}

				return value;
			},

			setError: function (name, error) {

				if (!name || !error) {
					return false;
				}

				if (!this.validateerrors[name]) {
					this.validateerrors[name] = [];
				}

				this.validateerrors[name].push(error);
				return true;
			},

			requireValidate: function (name, rule) {
				var value = this.getValueField(name);

				if (value == null) {
					var error = rule.error ? rule.error : 'Поле ' + this.getAttribute(name) + ' обязательно для заполнения';
					this.setError(name, error);
				}
			},

			minValidate: function (name, rule) {
				var value = this.getValueField(name);

				if (Util.isNumber(value)) {
					value = ''+value;
				}

				if (value == null || value.length < rule.value) {
					var error = rule.error ? rule.error : 'Поле ' + this.getAttribute(name) + ' минимальное кол-во символов ' + rule.value;
					this.setError(name, error);
				}
			},

			maxValidate: function (name, rule) {
				var value = this.getValueField(name);

				if (Util.isNumber(value)) {
					value = '' + value;
				}

				if (value !== null && value.length > rule.value) {
					var error = rule.error ? rule.error : 'Поле ' + this.getAttribute(name) + ' максимальное кол-во символов ' + rule.value;
					this.setError(name, error);
				}
			},

			integerOnlyValidate: function (name, rule) {
				var value = this.getValueField(name);

				if (value != null && isNaN(parseInt(value))) {
					var error = rule.error ? rule.error : 'Поле ' + this.getAttribute(name) + ' должно быть целым числом';
					this.setError(name, error);
				}
			},

			floatOnlyValidate: function (name, rule) {
				var value = this.getValueField(name);

				if (value != null && isNaN(parseFloat(value))) {
					var error = rule.error ? rule.error : 'Поле ' + this.getAttribute(name) + ' должно быть числом';
					this.setError(name, error);
				}
			}
		});
	});
