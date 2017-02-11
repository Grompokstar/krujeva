Module.define(
	"Events",

	function () {

		Alert = Static(Events, {
			alert: null,
			delay: 3000,

			animation: null,

			animations: {
				down: [ 'bounceInDown', 'bounceOutUp'],
				left: ['bounceInLeft', 'bounceOutRight']
			},

			timeout: null,

			initialize: function (animation) {
				this.ParentCall();
				this.animation = animation || 'down';
			},

			success: function (text) {
				var text = text || '';
				$('body').prepend(this.getTemplate(text));
				this.center().startAnimate();
			},

			error: function (text) {
				var text = text || '';
				$('body').prepend(this.getTemplateError(text));
				this.center().startAnimate();
			},

			getTemplate: function (text) {
				return UI.Template.render("Utils/Alert", {text: text});
			},

			getTemplateError: function (text) {
				return UI.Template.render("Utils/AlertError", {text: text});
			},

			center: function () {
				this.destroy();
				this.alert = $('#alert-message');

				switch (this.animation) {
					case 'left':
						var width = $(window).width() / 2;
						var alertWidth = this.alert.width() / 2;
						this.alert.css({left: (width - alertWidth) });
						break;
				}

				return this;
			},

			startAnimate: function () {
				var self = this;
				this.alert.addClass(this.animations[this.animation][0]);

				this.alert.on("click", function () {
					self.endAnimate();
				});

				if (self.timeout) {
					clearTimeout(self.timeout);
					self.timeout = null;
				}

				this.alert.one("animationend webkitAnimationEnd oanimationend", function () {

					self.timeout = setTimeout(function () {
						self.endAnimate();
					}, self.delay);
				});
			},

			endAnimate: function () {
				var self = this;

				if (self.timeout) {
					clearTimeout(self.timeout);
					self.timeout = null;
				}

				if (!this.alert) {
					return;
				}

				this.alert.removeClass(this.animations[this.animation][0]);

				this.alert.addClass(this.animations[this.animation][1]);

				this.alert.one("animationend webkitAnimationEnd oanimationend", function () {
					self.destroy();
				});
			},

			destroy: function () {
				if (this.alert) {
					this.alert.remove();
					this.alert = null;
				}
			}
		});
	}
);
