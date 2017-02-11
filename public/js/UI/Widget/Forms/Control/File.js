Module.define(
	"UI.Template",
	"UI.Widget.Forms.Control.Input",
	"System.NumSeq",

	function() {
		NS("UI.Widget.Forms.Control");

		// todo move mime types to config
		UI.Widget.Forms.Control.File = Class(UI.Widget.Forms.Control.Input, {
			template: "UI/Widget/Forms/Control/File/Control",
			imageTemplate: "UI/Widget/Forms/Control/File/Image",
			fileTemplate: "UI/Widget/Forms/Control/File/File",

			$input: null,

			createElement: function() {
				var attributes = {
					multiple: this.$control.attr("multiple") !== undefined,
					accept: this.$control.attr("accept") || false
				};

				this.$element = UI.Template.$render(this.template, {
					control: this,
					attributes: attributes
				});

				this.$input = this.$element.find("input");
			},

			insertElement: function() {
				var self = this,
					onRemove = this.$control.attr("on-remove");

				this.ParentCall();

				this.$element.on("click", "[name='remove']", function(e) {
					var $elem = $(e.currentTarget).closest("[name='item']"),
						uid = $elem.data("uid"),
						removed;

					self.setValue((self.getValue() || []).filter(function(file) {
						if (file.uid === uid) {
							removed = file;
							return false;
						}

						return true;
					}));

					$elem.remove();

					self.parent && Util.call(self.parent[onRemove], self.parent, [{ file: removed, control: self }]);
				});
			},

			updateElement: function(value) {
				var self = this,
					defers = [],
					files = [],
					urlFunc = this.$control.attr("url-func");

				Util.isArray(value) || (value = []);

				value.forEach(function(file) {
					if (Util.isFunction(self.parent[urlFunc])) {
						file.url = self.parent[urlFunc].call(self.parent, file);
					}

					if (!file.url) {
						return;
					}

					self._setUid(file);

					(function(file) {
						defers.push($.Deferred(function(defer) {
							var xhr = new XMLHttpRequest();
							xhr.responseType = "blob";
							xhr.addEventListener("load", function() {
								var blob = this.response;
								Util.merge(blob, file);
								files.push(blob);
								defer.resolve();
							});
							xhr.addEventListener("error", function() {
								defer.reject();
							});
							xhr.addEventListener("abort", function() {
								defer.reject();
							});
							xhr.open("GET", file.url);
							xhr.send();
						}));
					}(file));
				});

				$.when.apply(null, defers).then(function() {
					self._renderFiles(files);
				});
			},

			_setUid: function(file) {
				file.uid = System.NumSeq.next("UI.Widget.Forms.Control.File");

				return file;
			},

			_renderFiles: function(files) {
				var self = this,
					thumbs = document.createDocumentFragment(),
					list = document.createDocumentFragment(),
					defers = [],
					name = Html.generateName("files"),
					width, height;

				width = (width = this.$control.attr("thumb-w")) != null && Util.isNumeric(width) ? parseInt(width, 10) : 150;
				height = (height = this.$control.attr("thumb-h")) != null && Util.isNumeric(height) ? parseInt(height, 10) : 150;

				files.forEach(function(file) {
					var $elem;

					switch (file.type) {
						case "image/jpeg":
						case "image/jpg":
						case "image/png":
						case "image/bmp":
							(function(file) {
								defers.push($.Deferred(function(defer) {
									self._readFile(file, {
										callback: function(fileData) {
											var img = new Image();
											img.src = fileData;

											self._createThumb(img, {
												width: width,
												height: height,
												callback: function(thumb) {
													file.url || (file.url = fileData);
													$elem = UI.Template.$render(self.imageTemplate, { control: self, file: file, group: name });
													$elem.find("[name='file']")
														.append(thumb);

													thumbs.appendChild($elem[0]);
													defer.resolve();
												}
											});
										}
									});
								}));
							}(file));
							break;
						default:
							$elem = UI.Template.$render(self.fileTemplate, { control: self, file: file, group: name });
							list.appendChild($elem[0]);
							break;
					}
				});

				return $.when.apply(null, defers).then(function() {
					self.$element.find("[name='thumbs']").empty().append(thumbs.cloneNode(true));
					self.$element.find("[name='list']").empty().append(list.cloneNode(true));
				});
			},

			listenElement: function() {
				var self = this,
					mtypes = this.$control.attr("accept"),
					onChange = this.$control.attr("on-change");

				if (mtypes) {
					mtypes = mtypes.split(",").map(function(elem) {
						return elem.trim();
					});
				} else {
					mtypes = ["text/*", "image/*", "application/*"];
				}

				this.$input.on("change", function() {
					var files = Array.prototype.slice.call(this.files, 0);

					files = files.filter(function(file) {
						return !!~mtypes.indexOf(file.type) || !!~mtypes.indexOf(file.type.split("/")[0] + "/*");
					}).map(self._setUid.bind(self));

					//var value = files.map(function(file) {
					//	return {
					//		idx: file.uid,
					//		name: file.name,
					//		size: file.size,
					//		type: file.type,
					//		lastModified: file.lastModified
					//	};
					//});

					var value = files;

					self.setValue(value);
					self._renderFiles(files);

					self.parent && Util.call(self.parent[onChange], self.parent, [{ files: files, control: self }]);
					this.value = null;
					this.files = null;
				});
			},

			_readFile: function(file, options) {
				var self = this,
					reader = new FileReader();

				options || (options = {});

				reader.addEventListener("load", function(e) {
					Util.call(options.callback, self, [e.target.result]);
				});

				reader.readAsDataURL(file);
			},

			_createThumb: function(src, options) {
				var self = this,
					width, height;

				options || (options = {});
				width = options.width != null ? options.width : src.width;
				height = options.height != null ? options.height : src.height;

				self._resizeImage(src, {
					width: options.width,
					height: options.height,
					callback: function(img) {
						self._cropImage(img, { width: width, height: height });

						Util.call(options.callback, self, [img]);
					}
				});
			},

			_resizeImage: function(src, options) {
				var self = this,
					tmp = new Image(),
					dW, dH,
					canv, ctx, cW, cH,
					ratio,
					type, q;

				options || (options = {});

				cW = src.naturalWidth;
				cH = src.naturalHeight;
				dW = options.width != null ? options.width : src.width;
				dH = options.height != null ? options.height : src.height;
				type = options.type || "image/png";
				q = options.q != null ? parseFloat(options.q) : 0.9;
				ratio = cW / cH;

				tmp.src = src.src;

				tmp.addEventListener("load", function() {
					canv = document.createElement("canvas");
					ctx = canv.getContext("2d");

					cW /= 2;
					cH /= 2;

					if (cH < dH) {
						cH = dH;
						cW = cH * ratio;
					}

					if (cW < dW) {
						cW = dW;
						cH = cW / ratio;
					}

					canv.width = cW;
					canv.height = cH;

					ctx.drawImage(tmp, 0, 0, cW, cH);
					src.src = canv.toDataURL(type, q);
					// break recursion
					if (cW <= dW || cH <= dH) {
						Util.call(options.callback, self, [src]);
						return;
					}

					tmp.src = src.src;
				});
			},

			_cropImage: function(src, options) {
				var canv, ctx, sX, sY, width, height;

				options || (options = {});
				width = options.width != null ? options.width : src.width;
				height = options.height != null ? options.height : src.height;

				canv = document.createElement("canvas");
				ctx = canv.getContext("2d");

				canv.width = width;
				canv.height = height;

				if (src.width > src.height) {
					sY = 0;
					sX = Math.floor((src.width - width) / 2);
				} else {
					sX = 0;
					sY = Math.floor((src.height - height) / 2);
				}

				ctx.drawImage(src, sX, sY, width, height, 0, 0, width, height);
				src.src = canv.toDataURL();
			},

			removeElement: function() {
				this.$input = null;

				this.ParentCall();
			}
		});
	}
);