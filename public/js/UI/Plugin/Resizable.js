Module.define("UI.Plugin.Base", function () {
	NS("UI.Plugin");

	UI.Plugin.Resizable = Class(UI.Plugin.Base, {
		template: "UI/Plugin/Resizable/Resizable",

		div: null,
		point: null,

		initialize: function () {
			var self = this;

			this.ParentCall();

			this.div = UI.Template.$render(this.template).appendTo(this.element.$element);

			this.div.on("mousedown", function () {
				self.start();
			}).on("mouseup", function () {
					self.stop();
				});
		},

		destroy: function () {
			this.div.remove();

			this.ParentCall();

			return this;
		},

		start: function () {
			this.point = Util.clone(this.Class.point);

			Events.on("UI.Plugin.Resizable.Move", this.onMove, this);

			return this;
		},

		stop: function () {
			Events.off("UI.Plugin.Resizable.Move", this.onMove, this);

			return this;
		},

		onMove: function (args) {
			var point = Util.clone(args.point);
			var dx = point.x - this.point.x;
			var dy = point.y - this.point.y;
			var width = this.element.$element.width();
			var height = this.element.$element.height();

			this.element.$element.width(width + dx);
			this.element.$element.height(height + dy);

			this.element.align();

			this.point = point;
		}
	});

	UI.Plugin.Resizable.Static({
		point: {
			x: 0,
			y: 0
		},

		initialize: function () {
			var self = this;

			$(window).on("mousemove", function (args) {
				self.point.x = args.clientX;
				self.point.y = args.clientY;

				Events.emit("UI.Plugin.Resizable.Move", {
					point: self.point
				});
			});
		}
	});
});
