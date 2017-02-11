Module.define(
	"Base.Page",

	function () {

		Popup = Static({
			template: "Utils/PopupForm",

			$element: null,
			element: null,

			show: function(object, html, options) {
				options = options || {};
				this.init();

				var $html = $(html);

				if (Class.Is(object, Base.Page)) {
					this.bindEvents(object, $html);
				}

				this.$element.find("[name=container]").empty().append($html);

				if (options.title) {
					this.$element.find("[name=title]").html(options.title);
				}

				if (options.bodyclass) {
					this.$element.addClass(options.bodyclass);
				}

				this.$element.find("[name=close-btn]").on("click", function () {
					this.hide();
				}.bind(this));

				$('#main-container').addClass("blur");

				$('body').addClass("opened-popup");

				this.$element.appendTo($('body'));
			},

			hide: function() {
				this.destroy();
			},

			init: function() {
				if (this.$element) {
					return;
				}

				var html = UI.Template.render(this.template);

				var element = $(html);

				if (this.element) {
					this.$element.replaceWith(element);
					this.$element.remove();
				}

				this.$element = element;
				this.element = this.$element[0];
			},

			destroy: function() {
				if (this.$element) {
					this.$element.remove();
				}

				this.$element = null;
				this.element = null;

				$('#main-container').removeClass("blur");
				$('body').removeClass("opened-popup");
			},

			$: function (selector, nullable) {
				var element = this.$element ? $(selector, this.$element) : $();

				if (nullable && element && !element.length) {
					element = null;
				}

				return element;
			},

			$name: function (name, nullable) {
				return this.$("[name='" + name + "']", nullable);
			},

			bindEvents: function(object, element) {
				element.off();

				object.Class.initEvent("click", "on-click", element, object);
				object.Class.initEvent("change", "on-change", element, object);
				object.Class.initEvent("keyup", "on-keyup", element, object);
				object.Class.initEvent("keydown", "on-keydown", element, object);
				object.Class.initEvent("contextmenu", "on-contextmenu", element, object);
			}
		});
	}
);
