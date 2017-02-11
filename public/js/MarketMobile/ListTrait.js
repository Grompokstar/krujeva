Module.define(
	"Base.InfinityScroll",

	function () {

	NS("KrujevaMobile");

	KrujevaMobile.ListTrait = Class({

		iScroll: null,

		destroy: function () {
			this.listDestroy();

			this.ParentCall();
		},

		listDestroy: function () {

			if (this.iScroll) {

				this.iScroll.destroy();
			}


			this.$list = null;
		},

		listenScroll: function () {

			this.$list = this.$name('list-items');

			var scroll = this.$list;

			if (this.$name('infinity-scroll').html()) {
				scroll = this.$name('infinity-scroll');
			}

			this.iScroll = new IScroll(scroll.get(0), {
				scrollX: false,
				scrollY: true,
				mouseWheel: false,
				scrollbars: false,
				fadeScrollbars: true,

				//bounce: false
			});

			this.refreshIscroll();

			/*new Base.InfinityScroll(scroll, {
				event: {object: this, method: this.onScrolledList},
				lowerLimit: 1000
			});*/
		},

		addScrollOffset: function () {

			if (!this.$name('offset-list')) {
				return;
			}

			this.$name('offset-list').addClass('pbottom-260');

			this.refreshIscroll();
		},

		refreshIscroll: function (scrollTop) {

			if (this.iScroll && scrollTop) {

				this.iScroll.scrollTo(0, 0);
			}

			setTimeout(function () {

				if (this.iScroll) {

					this.iScroll.refresh();
				}

			}.bind(this), 100);
		},

		onScrolledList: function (cb) {

			if (!this.issetitems) {
				cb();
				return;
			}

			this.loadList(this.offset, function (error, items) {
				if (error) {
					return false;
				}

				this.insertItems(items);
				cb();
			}.bind(this));
		}

	});


});