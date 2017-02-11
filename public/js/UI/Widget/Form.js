Module.define(
	"UI.Widget.Base",
	"UI.Plugin.Draggable",

	function () {
		NS("UI.Widget");

		UI.Widget.Form = Class(UI.Widget.Base, {
			template: "UI/Widget/Form/Form",
			formTemplate: null,

			title: "",

			showTitle: true,

			$body: null,
			$header: null,
			$form: null,

			draggable: false,
			switchable: true,

			initialize: function (parent, options) {
				this.override(["formTemplate", "title", "draggable", "switchable", "showTitle"], options);

				this.Parent(parent, options);

				FORM = this;
			},

			setTitle: function (title) {
				this.title = title;

				this.$name("form-title").text(this.getTitle());

				this.emit("title", {
					title: this.getTitle()
				});

				return this;
			},

			getTitle: function () {
				if (Util.isFunction(this.title)) {
					return this.title();
				} else {
					return this.title;
				}
			},

			render: function () {
				var self = this;

				this.ParentCall();

				this.$header = this.$name("form-header");
				this.$body = this.$name("form-body");

				if (this.draggable) {
					this.use(UI.Plugin.Draggable, {
						handle: this.showTitle ? this.$header : this.$element
					});
				}

				this.renderForm();

				if (this.switchable) {
					UI.Widget.Form.registerSwitchable(this);

					this.$element.on("mousedown", function () {
						UI.Widget.Form.popSwitchable(self);
					});
				}

				return this;
			},

			renderForm: function () {
				if (this.formTemplate) {
					if (this.$form) {
						this.$form.empty();
					}

					this.$form = UI.Template.$render(this.formTemplate, { widget: this }).appendTo(this.$body);
				}
			},

			remove: function () {
				this.ParentCall();

				UI.Widget.Form.unregisterSwitchable(this);
			},

			pop: function () {
				if (this.switchable) {
					UI.Widget.Form.popSwitchable(this);
				}
			},

			onCloseClick: function () {
				this.destroy();
			}
		});

		UI.Widget.Form.Static({
			switchableForms: [],
			switchableZIndex: 1,
			switchableATopZIndex: 100,

			registerSwitchable: function (form) {
				if (!~this.switchableForms.indexOf(form)) {
					this.switchableForms.push(form);
					this.updateSwitchableZIndex();

					this.emit("Switchable.Register", { widget: form });
				}
			},

			unregisterSwitchable: function (form) {
				var index = this.switchableForms.indexOf(form);

				if (~index) {
					this.switchableForms.splice(index, 1);
					this.updateSwitchableZIndex();

					this.emit("Switchable.Unregister", { widget: form });
				}
			},

			popSwitchable: function (form) {
				var index = this.switchableForms.indexOf(form);

				if (~index) {
					this.switchableForms.splice(index, 1);
					this.switchableForms.push(form);
					this.updateSwitchableZIndex();

					this.emit("Switchable.Pop", { widget: form });
				}
			},

			getLast: function () {
				return this.switchableForms.slice(-1)[0];
			},

			isOnTop: function (form) {
				var length = this.switchableForms.length;

				return length && form == this.switchableForms[length - 1];
			},

			updateSwitchableZIndex: function () {
				for (var i = 0; i < this.switchableForms.length; i++) {
					if (this.switchableForms[i].$element) {
						var offset = this.switchableForms[i].alwaysOnTop ? this.switchableATopZIndex : this.switchableZIndex;
						this.switchableForms[i].$element.css("z-index", i + offset);
					}
				}
			}
		});
	}
);
