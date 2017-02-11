Module.define(
	"Base.Form",
	"GIS.UI.Map",
	"GIS.UI.Layer.GOSM",

	function () {
		NS("Base");

		Base.MapForm = Class(Base.Form, {
			template: "Transport/MapForm",

			map: null,

			$map: null,

			layers: {},

			initialize: function () {
				this.ParentCall();

				this.map = new GIS.UI.Map(this, {map: {zoomControl: false, doubleClickZoom: false}});

				this.map.on("zoom.end", this.onChangeZoom, this);

				this.parent.on('show', this.onParentShow, this);
			},

			destroy: function () {
				this.map.off("zoom.end", this.onChangeZoom, this);

				this.parent.off('show', this.onParentShow, this);

				this.layers = {};

				this.map = null;

				this.$map = null;

				this.ParentCall();
			},

			afterRender: function () {
				this.ParentCall();

				this.$map = this.$element;

				this.createMap();
			},

			onParentShow: function () {

				if (this.map.map) {
					this.map.map.invalidateSize();
				}
			},

			createMap: function () {
				this.map.render().appendTo(this.$map);

				this.map.setCenter(GIS.Geog.point(49.112319, 55.786419), 12);

				new GIS.UI.Layer.GOSM(this.map, {
					url: "http://188.40.75.212/tiles/{z}/{x}/{y}.png",
					tileSize: 1024,
					maxZoom: 18,
				 	minZoom: 5
				});

				this.map.map.invalidateSize();
			},

			changeZoom: function (caller) {
				var type = $(caller).attr("type");

				switch (type) {
					case "in":
						this.map.map.zoomIn();
						break;

					case "out":
						this.map.map.zoomOut();
						break;
				}
			},

			disableZoom: function (type) {

				var $control = type == "min" ? this.$name("out-zoom") : this.$name("in-zoom");

				$control.addClass("disable");
			},

			enableZoom: function (type) {

				var $control = type == "min" ? this.$name("out-zoom") : this.$name("in-zoom");

				$control.removeClass("disable");
			},

			onChangeZoom: function () {

				var minzoom = this.map.map.getMinZoom();

				var maxzoom = this.map.map.getMaxZoom() == 'Infinity' ? 40 : this.map.map.getMaxZoom();

				var currentzoom = this.map.map.getZoom();

				if (minzoom == currentzoom) {
					this.disableZoom("min");
				} else {
					this.enableZoom("min");
				}

				if (maxzoom == currentzoom) {
					this.disableZoom("max");
				} else {
					this.enableZoom("max");
				}
			}

		});
	});