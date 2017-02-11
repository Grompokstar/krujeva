Module.define(
	"Base.Form",
	"Base.ListTrait",
	"City.WeekType",
	"City.Periods",
	"City.Roles",

	function () {
		NS("City.UI.Employees");

		City.UI.Employees.ListForm = Class(Base.Form, Base.ListTrait, {
			template: "City/Employees/ListForm",
			listItemTemplate: "City/Employees/ListItemForm",
			datesContainers: "City/Employees/DatesContainers",

			listWidth: 2920,

			dateWidth: 180,

			selectedDate: null,

			currentDate: null,

			lastIndexMonth: 2,

			translateX: 0,

			sessionTranslateX: 0,

			oneSessionTranslateX: 0,

			startX: null,

			currentX: null,

			$firstDate: null,

			dateItems: {},

			initialize: function () {
				this.ParentCall();

				this.listsource = new City.Data.Employees();

				City.Events.on("City.Employees.Remove", this.onItemRemove, this);
				City.Events.on("City.Employees.Insert", this.onItemInsert, this);
				City.Events.on("City.Employees.Update", this.onItemUpdate, this);
			},

			afterRender: function () {
				this.ParentCall();

				this.listenScroll();

				this.currentDate = DateTime.getCurrentDate('YYYY-MM-DD');

				var dates = this.getDates(this.currentDate);

				this.getEmployeeEnters(dates, this.refreshList.bind(this));

				this.initDraggable();

				this.initWeel();
			},

			getEmployeeEnters: function (dates, callback) {

				var options = {
					dateStart: dates[0]['date'],
					dateEnd: dates[(dates.length - 1)]['date']
				};

				this.listsource.enters(options, function (result) {

					this.setEmployeeEnters(result);

					if (Util.isFunction(callback)) {
						callback();
					}

				}.bind(this));
			},

			setEmployeeEnters: function (data) {

				if (!data || !data.items){
					return;
				}

				//@items
				if (!this.dateItems['items']) {
					this.dateItems['items'] = {};
				}

				var items = data.items;

				for (var i in items) if (items.hasOwnProperty(i)) {
					var item = items[i];

					if (!this.dateItems['items'][i]) {
						this.dateItems['items'][i] = {};
					}

					for (var j in item) if (item.hasOwnProperty(j)) {
						var it = item[j];

						if (!this.dateItems['items'][i][j]) {
							this.dateItems['items'][i][j] = {};
						}

						this.dateItems['items'][i][j] = it;
					}
				}

				//@amount
				if (!this.dateItems['amount']) {
					this.dateItems['amount'] = {};
				}

				var amount = data.amount;

				for (var i in amount) if (amount.hasOwnProperty(i)) {
					var am = amount[i];

					if (!this.dateItems['amount'][i]) {
						this.dateItems['amount'][i] = {};
					}

					for (var j in am) if (am.hasOwnProperty(j)) {
						var it = am[j];

						if (!this.dateItems['amount'][i][j]) {
							this.dateItems['amount'][i][j] = {};
						}

						this.dateItems['amount'][i][j] = it;
					}
				}

			},

			onScrolledList: function (cb, element, args) {
				this.ParentCall();

				var scrollTop = args.scrollTop;

				this.$name('drag-event-item').css({y: scrollTop * -1});
			},

			insertItems: function (items, refresh, prepend) {
				this.ParentCall();

				var $container = this.$name('drag-event-item');

				if (refresh) {
					$container.empty();
				}

				var dates = this.getDates(this.currentDate);

				var issetDates = false;

				if (this.$('#emp-date-id-cnt').html()) {
					issetDates = true;
				}

				var employees = [];

				for (var i in items) if (items.hasOwnProperty(i)) {
					employees.push({item: items[i]});
				}

				var html = UI.Template.render(this.datesContainers, {dates: dates, employees: employees, issetDates: issetDates, widget: this});

				$container.append(html);

				this.$firstDate = this.$('.emp-date-item-date').first();
			},

			getDates: function (currentDate, addCurrent) {
				var count = Math.ceil((this.listWidth / this.dateWidth) + 1);
				addCurrent = typeof addCurrent == 'undefined' ? true : addCurrent;

				var dates = [];
				var dateFormat = 'YYYY-MM-DD';

				if (addCurrent) {
					dates.push(this.dateObject(currentDate, false, true));

					var dateObject = DateTime.momentDate(currentDate, dateFormat);

					if (dateObject.format('E') == 1) {
						dates.unshift(this.dateObject(dateObject.subtract(1, 'd').format(dateFormat), true));
					}
				}

				for (var j = 1; j < count; j++) {
					var beforeDate = DateTime.momentDate(currentDate, dateFormat).subtract(j, 'd');

					dates.unshift(this.dateObject(beforeDate.format(dateFormat)));

					if (beforeDate.format('E') == 1) {

						dates.unshift(this.dateObject(beforeDate.subtract(1, 'd').format(dateFormat), true));
					}

				}

				return dates;
			},

			dateObject: function (date, dayofZP) {
				var dateFormat = 'YYYY-MM-DD';

				return {
					date: date,

					day: DateTime.getDate(date, 'D.MM', dateFormat),

					week: City.WeekType.title(DateTime.getDate(date, 'E', dateFormat)),

					weekyear: DateTime.getDate(date, 'YYYY_W', dateFormat),

					month: DateTime.getDate(date, 'MMMM', dateFormat),

					desc: date == DateTime.getCurrentDate(dateFormat) ? 'сегодня' : '',

					holiday: ['6', '7'].indexOf(DateTime.getDate(date, 'E', dateFormat)) !== -1,

					dayofZP: dayofZP
				}
			},

			destroy: function () {
				this.listDestroy();

				City.Events.off("City.Employees.Remove", this.onItemRemove, this);
				City.Events.off("City.Employees.Insert", this.onItemInsert, this);
				City.Events.off("City.Employees.Update", this.onItemUpdate, this);

				this.ParentCall();
			},

			setCountItems: function () {
				var title = Util.declOfNum(this.countItems, [' сотрудник', ' сотрудника', ' сотрудников']);
				this.$name('count-items').html(this.countItems + title);
			},

			editClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.open('edit', {item: item['item']});
			},

			initDraggable: function () {
				var $container = this.$name('drag-event-item');
				var $maincontainer = $('#main-container');

				this.mouseDown = false;

				$container.on('mousedown', function (ev) {
					this.startX = ev.clientX;

					this.mouseDown = true;

					this.$firstDate = this.$('.emp-date-item-date').first();

					$maincontainer.on('mouseup', this.onMouseUp.bind(this));

					$maincontainer.on('mousemove', this.onMouseMove.bind(this));
				}.bind(this));
			},

			initWeel: function () {

				this.$name('drag-event-item').on('mousewheel', function (event) {

					var moveY = 100;

					if (event.deltaY < 0) {
						this.movePanel(0, moveY);
					} else {
						this.movePanel(moveY, 0);
					}

					this.mouseDown = true;

					this.onMouseMove(event, false);

					this.mouseDown = false;

					this.translateX = this.sessionTranslateX;

				}.bind(this));
			},

			loadindEmployeeEnters: false,

			onMouseMove: function (ev ,movePanel) {
				if (!this.mouseDown) {
					return;
				}

				movePanel = typeof movePanel == 'undefined' ? true : movePanel;

				if (this.$firstDate.offset().left > 0 && !this.loadindEmployeeEnters) {
					this.currentDate = this.$firstDate.data('date');

					this.loadindEmployeeEnters = true;

					var dates = this.getDates(this.currentDate, false);

					this.getEmployeeEnters(dates, function () {

						//@add dates -items
						var dateshtml = [];
						for (var i in dates) if (dates.hasOwnProperty(i)) {
							dateshtml.push(UI.Template.render("City/Employees/DateItem", {date: dates[i]}))
						}

						$('#emp-date-id-cnt').prepend(dateshtml.join(''));

						//emp-item-id-cnt
						var employees = this.objectListItems;
						for (var j in employees) if (employees.hasOwnProperty(j)) {

							var employeeshtml = [];

							for (var i in dates) if (dates.hasOwnProperty(i)) {
								employeeshtml.push(UI.Template.render("City/Employees/EmployeeItem", {date: dates[i], employee: employees[j]['item'], widget: this}));
							}

							$('#emp-item-id-cnt' + employees[j]['item']['id']).prepend(employeeshtml.join(''));
						}

						this.$firstDate = this.$('.emp-date-item-date').first();

						this.loadindEmployeeEnters = false;

					}.bind(this));
				}

				if (movePanel) {
					this.currentX = ev.clientX;

					this.mouseDown = true;

					this.movePanel(this.startX, this.currentX);
				}
			},

			onMouseUp: function () {
				this.mouseDown = false;

				setTimeout(function () {
					this.oneSessionTranslateX = 0;
				}.bind(this), 0);

				this.translateX = this.sessionTranslateX;

				$('#main-container').off('mouseup mousemove');
			},

			movePanel: function (startX, currentX) {
				var diff = currentX - startX + this.translateX;

				if (diff < 0) {
					return;
				}

				this.oneSessionTranslateX = Math.abs(currentX - startX);

				this.sessionTranslateX = diff;

				this.$name('drag-container-item').css({
					x: this.sessionTranslateX
				});
			},

			onViewClick: function (caller) {
				var id = $(caller).data('id');

				var item = this.objectListItems[id];

				if (!item) {
					return;
				}

				this.parent.open("view", {item: item['item']});
			}

		});
	});