GLOBAL.Util = Static({
	clone: function (object, options) {
		if (typeof(options) != "object") {
			options = {};
		}

		options.circular = options.circular === undefined ? true : options.circular;

		if (!object || typeof(object) != "object") {
			return object;
		}

		var result = typeof(object.pop) == "function" ? [] : {};
		var key, value;

		if (!options.replaces) {
			options.replaces = {
				except: [],
				replace: []
			}
		}

		options.replaces.except.push(object);
		options.replaces.replace.push(result);

		for (key in object) {
			if (object.hasOwnProperty(key)) {
				value = object[key];

				if (value && typeof(value) == "object") {
					var idx = options.replaces.except.indexOf(value);

					if (idx != -1) {
						if (options.circular) {
							result[key] = options.replaces.replace[idx];
						}
					} else {
						result[key] = this.clone(value, options);
					}
				} else {
					result[key] = value;
				}
			}
		}

		return result;
	},



	crc32: function (str) {

		str = str + '';

		var table = '00000000 77073096 EE0E612C 990951BA 076DC419 706AF48F E963A535 9E6495A3 0EDB8832 79DCB8A4 E0D5E91E 97D2D988 09B64C2B 7EB17CBD E7B82D07 90BF1D91 1DB71064 6AB020F2 F3B97148 84BE41DE 1ADAD47D 6DDDE4EB F4D4B551 83D385C7 136C9856 646BA8C0 FD62F97A 8A65C9EC 14015C4F 63066CD9 FA0F3D63 8D080DF5 3B6E20C8 4C69105E D56041E4 A2677172 3C03E4D1 4B04D447 D20D85FD A50AB56B 35B5A8FA 42B2986C DBBBC9D6 ACBCF940 32D86CE3 45DF5C75 DCD60DCF ABD13D59 26D930AC 51DE003A C8D75180 BFD06116 21B4F4B5 56B3C423 CFBA9599 B8BDA50F 2802B89E 5F058808 C60CD9B2 B10BE924 2F6F7C87 58684C11 C1611DAB B6662D3D 76DC4190 01DB7106 98D220BC EFD5102A 71B18589 06B6B51F 9FBFE4A5 E8B8D433 7807C9A2 0F00F934 9609A88E E10E9818 7F6A0DBB 086D3D2D 91646C97 E6635C01 6B6B51F4 1C6C6162 856530D8 F262004E 6C0695ED 1B01A57B 8208F4C1 F50FC457 65B0D9C6 12B7E950 8BBEB8EA FCB9887C 62DD1DDF 15DA2D49 8CD37CF3 FBD44C65 4DB26158 3AB551CE A3BC0074 D4BB30E2 4ADFA541 3DD895D7 A4D1C46D D3D6F4FB 4369E96A 346ED9FC AD678846 DA60B8D0 44042D73 33031DE5 AA0A4C5F DD0D7CC9 5005713C 270241AA BE0B1010 C90C2086 5768B525 206F85B3 B966D409 CE61E49F 5EDEF90E 29D9C998 B0D09822 C7D7A8B4 59B33D17 2EB40D81 B7BD5C3B C0BA6CAD EDB88320 9ABFB3B6 03B6E20C 74B1D29A EAD54739 9DD277AF 04DB2615 73DC1683 E3630B12 94643B84 0D6D6A3E 7A6A5AA8 E40ECF0B 9309FF9D 0A00AE27 7D079EB1 F00F9344 8708A3D2 1E01F268 6906C2FE F762575D 806567CB 196C3671 6E6B06E7 FED41B76 89D32BE0 10DA7A5A 67DD4ACC F9B9DF6F 8EBEEFF9 17B7BE43 60B08ED5 D6D6A3E8 A1D1937E 38D8C2C4 4FDFF252 D1BB67F1 A6BC5767 3FB506DD 48B2364B D80D2BDA AF0A1B4C 36034AF6 41047A60 DF60EFC3 A867DF55 316E8EEF 4669BE79 CB61B38C BC66831A 256FD2A0 5268E236 CC0C7795 BB0B4703 220216B9 5505262F C5BA3BBE B2BD0B28 2BB45A92 5CB36A04 C2D7FFA7 B5D0CF31 2CD99E8B 5BDEAE1D 9B64C2B0 EC63F226 756AA39C 026D930A 9C0906A9 EB0E363F 72076785 05005713 95BF4A82 E2B87A14 7BB12BAE 0CB61B38 92D28E9B E5D5BE0D 7CDCEFB7 0BDBDF21 86D3D2D4 F1D4E242 68DDB3F8 1FDA836E 81BE16CD F6B9265B 6FB077E1 18B74777 88085AE6 FF0F6A70 66063BCA 11010B5C 8F659EFF F862AE69 616BFFD3 166CCF45 A00AE278 D70DD2EE 4E048354 3903B3C2 A7672661 D06016F7 4969474D 3E6E77DB AED16A4A D9D65ADC 40DF0B66 37D83BF0 A9BCAE53 DEBB9EC5 47B2CF7F 30B5FFE9 BDBDF21C CABAC28A 53B39330 24B4A3A6 BAD03605 CDD70693 54DE5729 23D967BF B3667A2E C4614AB8 5D681B02 2A6F2B94 B40BBE37 C30C8EA1 5A05DF1B 2D02EF8D';

		var crc = 0;
		var x = 0;
		var y = 0;

		crc = crc ^ (-1);
		for (var i = 0, iTop = str.length; i < iTop; i++) {
			y = (crc ^ str.charCodeAt(i)) & 0xFF;
			x = '0x' + table.substr(y * 9, 8);
			crc = (crc >>> 8) ^ x;
		}

		return crc ^ (-1);
	},

	/*
	* t =  between 0 and 1 = recomended 0.001
	* points = [[45,52], [43,48]]
	*
	*/
	bezier: function (t, points) {
		var n = points.length;
		var tmp = [];

		for (var i = 0; i < n; ++i) {
			tmp[i] = [points[i][0], points[i][1]];
		}

		for (var j = 1; j < n; ++j) {
			for (i = 0; i < n - j; ++i) {
				tmp[i][0] = (1 - t) * tmp[i][0] + t * tmp[parseInt(i + 1, 10)][0];
				tmp[i][1] = (1 - t) * tmp[i][1] + t * tmp[parseInt(i + 1, 10)][1];
			}
		}

		return [tmp[0][0], tmp[0][1]];
	},

	/*
	 * сортировка
	 * extractor - название колонки (если это массив объектов)
	 * reverse - default=false сортировать в обратном порядке
	 * */
	sort: function (array, extractor, reverse) {

		if (!(array instanceof Array)) {
			return array;
		}

		extractor = extractor || null;
		reverse = reverse || false;

		if (extractor) {
			return sortWithExtractor(array, extractor, reverse);
		} else {
			return sort(array, reverse);
		}

		function sort(array, reverse) {
			var data = array.slice().sort();

			if (reverse) {
				data = data.reverse();
			}

			return data;
		}

		function sortWithExtractor(array, element, reverse) {

			var data = array.slice().sort(function (i, ii) {
				if (i[element] > ii[element]) {
					return 1;
				} else if (i[element] < ii[element]) {
					return -1;
				} else {
					return 0;
				}
			});

			if (reverse) {
				data = data.reverse();
			}

			return data;
		}

	},

	object2array: function(object) {
		var array = [];

		for(var i in object) if (object.hasOwnProperty(i)) {
			array.push(object[i]);
		}

		return array;
	},

	limitArray: function (array, limit) {

		if (array.length > limit) {
			array.length = limit;
		}

		return array;
	},

	declOfNum: function(number, titles) {
		var cases = [2, 0, 1, 1, 1, 2];
		return titles[ (number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5] ];
	},

	equal: function (left, right) {
		if (typeof(left) != "object" || typeof(right) != "object" || !left || !right) {
			return left == right;
		}

		var self = this;
		var result = true;

		this.each(left, function (value, name) {
			if (!self.equal(left[name], right[name])) {
				result = false;
				return false;
			}

			return true;
		});

		return result;
	},

	isEmpty: function (object) {
		return !Object.keys(object).length;
	},

	isDefined: function (value) {
		return value !== null && value !== undefined;
	},

	object: function (object, clone) {
		if (object && typeof(object) == "object") {
			return clone ? this.clone(object) : object;
		}

		return {};
	},

	array: function (object, clone) {
		if (object && this.isArray(object)) {
			return clone ? this.clone(object) : object;
		}

		return [];
	},

	merge: function (target, source, clone) {
		if (!target || typeof(target) != "object") {
			target = {};
		}

		if (!source || typeof(source) != "object") {
			source = {};
		}

		for (var key in source) {
			if (source.hasOwnProperty(key)) {
				target[key] = clone ? this.clone(source[key]) : source[key];
			}
		}

		return target;
	},

	defaults: function (defaults, options) {
		var self = this;

		if (!options || typeof(options) != "object") {
			options = {};
		}

		this.each(defaults, function (value, name) {
			options[name] = options[name] === undefined ? value : options[name];
		});

		return options;
	},

	options: function (options, names) {
		var opts = {};
		var isArray = this.isArray(names);

		if (options && typeof(options) == "object" && names && typeof(names) == "object") {
			for (var prop in names) {
				if (names.hasOwnProperty(prop)) {
					var name = names[prop];
					var val = options[isArray ? name : prop];

					if (val !== undefined) {
						opts[name] = val;
					}
				}
			}
		}

		return opts;
	},

	call: function (callback, context, args) {
		if (!args) {
			args = [];
		}

		if (Util.isFunction(callback)) {
			return callback.apply(context ? context : GLOBAL, args);
		}
	},

	coalesce: function () {
		for (var i = 0, count = arguments.length; i < count; i++) {
			var argument = arguments[i];

			if (argument !== null && argument !== undefined) {
				return argument;
			}
		}

		return null;
	},

	log: function () {
		Array.prototype.unshift.call(arguments, (new Date()).toString());

		console.log.apply(console, arguments);
	},

	each: function (object, callback, asArray) {
		if (asArray) {
			for (var i = 0, count = object.length; i < count; i++) {
				if (callback(object[i], i) === false) {
					break;
				}
			}
		} else {
			for (var key in object) {
				if (object.hasOwnProperty(key)) {
					if (callback(object[key], key) === false) {
						break;
					}
				}
			}
		}
	},

	defined: function (name) {
		var nsList = name.split(".");
		var nsType;
		var ns = "";

		for (var i = 0; i < nsList.length; i++) {
			if (ns != "") {
				ns += ".";
			}

			ns += nsList[i];

			eval("nsType = typeof(" + ns + ");");

			if (nsType == "undefined") {
				return false;
			}
		}

		return true;
	},

	isNumber: function (n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	},

	isArray: function (value) {
		return Object.prototype.toString.call(value) === "[object Array]";
	},

	isObject: function (value) {
		return value && typeof(value) == "object";
	},

	isFunction: function (value) {
		return typeof(value) == "function";
	},

	create: function (className, args) {
		var object;
		var cmd = "object = new " + className + "(";

		if (args) {
			for (var i = 0; i < args.length; i++) {
				if (i > 0) {
					cmd += ",";
				}

				cmd += "args[" + i + "]";
			}
		}

		cmd += ");";

		try {
			eval(cmd);
		} catch (e) {
			console.log(className, args);
			throw e;
		}

		return object;
	},

	parseJSON: function (json) {
		try {
			return JSON.parse(json);
		} catch (e) {
			return null;
		}
	},

	async: function (callback, context, timeout) {
		timeout = this.coalesce(timeout, 0);

		return setTimeout(function () {
			if (context) {
				callback.call(context);
			} else {
				callback();
			}
		}, timeout);
	},

	delayed: function (timeout, callback, object) {
		var method = this.delayed;

		if (!method.pending) {
			method.pending = [];
		}

		timeout = this.coalesce(timeout, 100);

		var subscribe = function (pending) {
			pending.id = setTimeout(function () {
				for (var k = 0; k < method.pending.length; k++) {
					if (method.pending.callback == pending.callback && method.pending.object == pending.object) {
						method.pending.splice(k, 1);
						break;
					}
				}

				if (pending.object) {
					pending.callback.call(pending.object);
				} else {
					pending.callback();
				}
			}, pending.timeout);
		};

		for (var i = 0; i < method.pending.length; i++) {
			var pending = method.pending[i];

			if (pending.callback == callback && pending.object == object) {
				clearTimeout(pending.id);
				subscribe(pending);

				return pending.id;
			}
		}

		pending = {
			callback: callback,
			object: object,
			timeout: timeout
		};

		subscribe(pending);

		method.pending.push(pending);

		return pending.id;
	},

	ru2en: function (text) {
		var ru = 'йцукенгшщзхъфывапролджэячсмитьбю'.split('');

		var en = "qwertyuiop[]asdfghjkl;'zxcvbnm,.".split('');

		var res = [];

		text = text.split('');

		for (var i in text) if (text.hasOwnProperty(i)) {
			var symbol = text[i];

			var ruIndex = ru.indexOf(symbol);

			if (ruIndex !== -1) {
				symbol = en[ruIndex];
			}

			res.push(symbol);
		}

		return res.join('');
	},

	priceFormat: function (num, n, x, s, c) {

		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')';

		num = parseFloat(num);

		num = num.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	},

	String: {
		value: function (string, options) {
			options = Util.object(options);

			if (!string || !string.toString().trim()) {
				return "";
			}

			if (options.prepend) {
				string = options.prepend + string;
			}

			if (options.append) {
				string = string + options.append;
			}

			return string;
		},

		if: function (value, string) {
			return value && String(value).trim().length ? string : "";
		},

		prependIf: function (value, prepend) {
			if (value && value.length) {
				return "" + prepend + value;
			}

			return "";
		},

		appendIf: function (value, append) {
			if (value && value.length) {
				return "" + value + append;
			}

			return "";
		},

		coalesce: function () {
			for (var i = 0, count = arguments.length; i < count; i++) {
				var argument = arguments[i];

				if (argument && argument.length) {
					return argument;
				}
			}

			return "";
		},

		join: function (array, delimiter) {
			delimiter = Util.coalesce(delimiter, ", ");
			var items = [];

			Util.each(array, function (item) {
				if (item) {
					item = String(item).trim();

					if (item.length) {
						items.push(item);
					}
				}
			});

			return items.join(delimiter);
		},

		repeat: function (string, count) {
			var result = "";

			while (count--) {
				result += string;
			}

			return result;
		}
	},

	Array: {
		reduce: function (array, callback, initial) {
			var value = initial;

			for (var i = 0, count = array.length; i < count; i++) {
				value = callback(value, array[i]);
			}

			return value;
		},

		sortByField: function (objects, field) {
			objects.sort(function (left, right) {
				return left[field] < right[field] ? -1 : (left[field] > right[field] ? 1 : 0);
			});
		},

		appendStr: function (items, item, options) {
			items = Util.array(items);
			options = Util.object(options);

			if (item && item.toString().trim()) {
				if (options.prepend) {
					item = options.prepend + item;
				}

				if (options.append) {
					item = item + options.append;
				}

				items.push(item);
			}

			return items;
		},

		indexOf: function (array, object) {
			for (var i = 0; i < array.length; i++) {
				if (Util.equal(array[i], object)) {
					return i;
				}
			}

			return -1;
		},

		filter: function filterArray(src, filt) {
			var temp = {}, i, result = [];

			// load contents of filt into object keys for faster lookup
			for (i = 0; i < filt.length; i++) {
				temp[filt[i]] = true;
			}

			// go through src
			for (i = 0; i < src.length; i++) {
				if (!(src[i] in temp)) {
					result.push(src[i]);
				}
			}
			return (result);
		}
	},

	Object: {

		/*
		* Example
		*
		*  Util.Object.value({a:{b:{c:12345}}}, 'a.b.c')  =  12345
		*
		* */
		value: function(object, value) {
			var result = null;

			if (typeof value === "undefined") {
				return result;
			}

			if (!Util.isObject(object)) {
				return result;
			}

			try {

				eval('result = object.' + value);

			} catch (e) {}

			return result;
		}

	},

	Text: {
		distance: function (distance) {
			if (distance) {
				if (distance < 1000) {
					return distance + " м";
				} else if (distance < 10000) {
					return (distance / 1000).toFixed(1) + " км";
				} else {
					return Math.round(distance / 1000) + " км";
				}
			}

			return "";
		}
	}
});