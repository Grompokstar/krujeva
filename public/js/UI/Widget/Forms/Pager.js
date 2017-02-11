Module.define(
	function () {
		NS("UI.Widget.Forms");

		UI.Widget.Forms.Pager = Class({
			pagerContainer: null,
			pagerTemplates: null,
			pagerCurrentPage: null,

			pagerShow: function (page) {
				if (page) {
					this.pagerCurrentPage = page;
				}

				this.pagerContainer.empty();

				if (!this.pagerTemplates.match(/\/$/)) {
					this.pagerTemplates += "/";
				}

				return UI.Template.$render(this.pagerTemplates + this.pagerCurrentPage, { widget: this }).appendTo(this.pagerContainer);
			}
		});
	}
);
