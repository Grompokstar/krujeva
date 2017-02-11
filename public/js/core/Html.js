Module.define(function () {
	GLOBAL.Html = Static({
		attribute: function (name, value) {
			return (name && ~(["string", "number"].indexOf(typeof(value)))) ? name + '="' + this.encode(value) + '"' : '';
		},

		attributes: function (attributes) {
			var self = this;
			var items = [];

			if (attributes && typeof(attributes) == "object") {
				Util.each(attributes, function (value, name) {
					items.push(self.attribute(name, value));
				});
			}

			return items.join(" ");
		},

		encode: function (value, options) {
			if (value === null || value === undefined) {
				return "";
			}

			options = Util.object(options);

			var ret = String(value).replace(/&/g, "&amp;")
				.replace(/"/g, "&quot;")
				.replace(/'/g, "&#39;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;");

			if (options.br) {
				ret = this.br(ret);
			}

			if (options.nbsp) {
				ret = this.nbsp(ret);
			}

			if (options.tab) {
				ret = this.tab(ret);
			}

			if (options.urls) {
				ret = this.urls(ret);
			}

			return ret;
		},

		decode: function (value) {
			if (!value) {
				return "";
			}

			return String(value).replace(/&gt;/g, ">")
				.replace(/&lt;/g, "<")
				.replace(/&#39;/g, "'")
				.replace(/&quot;/g, '"')
				.replace(/&amp;/g, "&");
		},

		checked: function (value) {
			return value ? "checked" : "";
		},

		selected: function (value) {
			return value ? "selected" : "";
		},

		generateName: function (name) {
			if (!name) {
				name = "name";
			}

			return name + (+(Math.random() * 1000000));
		},

		br: function (text) {
			return text ? text.replace(/\n/g, "<br>") : "";
		},

		nbsp: function (text) {
			return text ? text.replace(/ {2,}/g, function (match) { return match.replace(/ /g, "&nbsp;"); }) : "";
		},

		tab: function (text) {
			return text ? text.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;") : "";
		},

		urls: function (text) {
			return text ? text.replace(/(http:\/\/[^\s]*)/g, "<a href=\"$1\" target=\"_blank\">$1</a>") : "";
		}
	});
});
