;(function (win) {

	'use strict';

	function FluidTextArea (element, params) {
		this.minRows = 2;
		this.maxRows = 15;
		this.area    = element;
		this.params  = params || {};

		this.rows = 0;
		this.pt = 0;
		this.pb = 0;
		this.borderwidth = 0;
		this.preheight = 0;

		this.initialize.apply(this, arguments);
	};

	FluidTextArea.prototype = {
		initialize: function () {
			this.wrapper     = document.createElement('div');
			this.preText     = document.createElement('pre');
			this.preTextSpan = document.createElement('span');

			this.wrapper.className     = 'txt-wrp';
			this.preText.className     = 'txt-pre';
			this.preTextSpan.className = 'txt-prespan';

			this.startInteractions();
		},

		startInteractions: function () {
			this.pt   = this.getTextAreaPropStyle('padding-top');
			this.pb  = this.getTextAreaPropStyle('padding-bottom');
			this.borderwidth = this.getTextAreaPropStyle('border-width');

			this.rows = parseInt(this.area.getAttribute('rows')) || this.minRows;
			this.maxRows = parseInt(this.area.getAttribute('max-rows')) || this.maxRows;

			this.area.parentNode.appendChild(this.wrapper);

			this.preText.appendChild(this.preTextSpan);
			this.preText.appendChild(document.createElement('br'));
			this.preText.style.borderWidth = this.borderwidth+"px";

			this.wrapper.appendChild(this.preText);
			this.wrapper.appendChild(this.area);

			this.preheight = this.preText.clientHeight - this.pt - this.pb;

			this.calcMinHeight();

			this.bind();
		},

		bind: function () {
			this.area.addEventListener('input', this.cloneText.bind(this), false);
			return this;
		},

		unbind: function () {
			this.area.removeEventListener('input', this.cloneText.bind(this), false);
			return this;
		},

		cloneText: function () {
			this.calcMinHeight();

			if (this.textareaRows() > this.maxRows) {
				return;
			}

			this.preTextSpan.textContent = this.area.value;
			return this;
		},

		calcMinHeight: function () {
			var textrows = this.textareaRows();
			this.rows = this.minRows;
			this.rows = this.rows > textrows ? this.rows : textrows;
			this.rows = this.rows > this.maxRows ? this.maxRows : this.rows;

			this.preText.style.minHeight = this.preheight * this.rows + this.pt + this.pb + (this.borderwidth * 2) + 'px';
		},

		textareaRows: function () {
			return (this.area.value.match(new RegExp("\\n", "g")) || []).length + 1;
		},

		getTextAreaPropStyle: function (prop) {
			return +getComputedStyle(this.area).getPropertyValue(prop).replace('px', '');
		},

		destroy: function () {
			this.unbind();
			this.wrapper.remove();
		}
	};

	FluidTextArea.create = function (el, params) {
		return new FluidTextArea(el, params);
	};

	win.FluidTextArea = FluidTextArea;

}) (window);