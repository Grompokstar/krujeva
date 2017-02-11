Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Franchisees");

		City.UI.Franchisees.ViewForm = Class(Base.Form, {
			template: "City/Franchisees/ViewForm",

			item: null,

			playgrounds: {},

			isProfile: null,

			destroy: function () {
				this.item = null;

				this.playgrounds = {};

				this.ParentCall();
			},

			render: function (options) {

				if (options && options.item) {
					this.item = options.item;
				}

				if (options && options.isProfile) {
					this.isProfile = true;
				}

				if (!this.isProfile) {

					this.getPlaygrounds();
				}

				return this.ParentCall();
			},

			backClick: function () {
				this.parent.open('list');
			},

			getPlaygrounds: function () {
				if (!this.item || !this.item.owner) {
					return;
				}

				var source = new City.Data.Employees();

				var loader = Loader.start(this.$name('form-loader'));

				source.playgrounds({id: this.item.owner.userid}, function (playgrounds) {
					Loader.end(loader);

					if (playgrounds && Util.isArray(playgrounds)) {

						this.renderPlaygrounds(playgrounds)
					}

				}.bind(this))
			},

			renderPlaygrounds: function (items) {
				var $html = UI.Template.$render('City/Employees/PlaygroundsList', {items: items});

				for (var i in items) if (items.hasOwnProperty(i)) {
					this.playgrounds[items[i]['id']] = items[i];
				}

				$html.appendTo(this.$name('playgrounds-list'));
			},

			editClick: function () {

				if (this.isProfile) {
					return;
				}

				this.parent.open('edit', {item: this.item});
			},

			viewPlaygroundClick: function (caller) {
				var id = $(caller).data('id');

				var playground = this.playgrounds[id];

				if (!playground) {
					return;
				}

				application.open('playgrounds', {
					openNext: 'view',
					backTo: 'franchisees.view',
					item: playground
				});
			}
		});
	});