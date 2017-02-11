Module.define(
	"UI.Widget.Base",
	"UI.Plugin.Draggable",

	function () {
		NS("GIS.UI.VehicleHistory.Log");

		GIS.UI.VehicleHistory.Log = Class(UI.Widget.Base, {
			template: "GIS/VehicleHistory/Log",

			history: null,
			items: [],

			visible: false,

			$container: null,

			render: function () {
				this.ParentCall();

				this.hide();
				this.position({ top: 80, left: 5});

				this.use(UI.Plugin.Draggable, {
					handle: this.$element
				});

				this.$container = this.$name("container");

				return this;
			},

			setData: function (history) {
				this.history = history;

				for (var i = 0, count = this.history.length; i < count; i++) {
					this.items.push(UI.Template.$render("GIS/VehicleHistory/LogItem", {index: i, item: this.history[i]}));
				}

				var $table = $('<table class="content"></table>')
					.append(
						'<col width="260"/>' +
						'<col width="190"/>' +
						'<col width="140"/>' +
						'<col width="130"/>'
				)
					.append(this.items);

				this.$container.append($table);
			},

			highlight: function (index) {
				if (!this.visible) {
					return;
				}

				this.$(".highlight").removeClass("highlight");

				this.items[index].addClass("highlight");

				(function (element, container) {
					var spaceHeight = container.outerHeight();
					var height = element.outerHeight();
					var position = element.position();

					if (position.top + height > spaceHeight) {
						element[0].scrollIntoView(false);
					} else if (position.top < 0) {
						element[0].scrollIntoView();
					}
				})(this.items[index], this.$container);
			},

			clear: function () {
				this.items = [];
				this.$container.empty();
			},

			onItemClick: function (caller, args) {
				var index = $(caller).data("index");
				this.emit("itemClick", { index: index });

				return false;
			}
		});
	}
);
