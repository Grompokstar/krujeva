Module.define(
	"Base.Form",

	function () {
		NS("City.UI.Employees");

		City.UI.Employees.ViewForm = Class(Base.Form, {
			template: "City/Employees/ViewForm",

			item: null,

			playgrounds: {},

			destroy: function () {
				this.item = null;

				this.playgrounds = {};

				this.ParentCall();
			},

			render: function (options) {

				if (options && options.item) {
					this.item = options.item;
				}

				this.getPlaygrounds();

				return this.ParentCall();
			},

			backClick: function () {
				this.parent.open('list');
			},

			getPlaygrounds: function () {
				if (!this.item){
					return;
				}

				var source = new City.Data.Employees();

				var loader = Loader.start(this.$name('form-loader'));

				source.playgrounds({id: this.item.userid}, function (playgrounds) {
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
					backTo: 'employee.view',
					item: playground
				});
			}
		});
	});