Module.define(
	"Base.Form",
	"City.UI.AnimateForm",

	function () {
		NS("City.UI");

		City.UI.StatisticsForm = Class(Base.Form, City.UI.AnimateForm, {
			template: "City/StatisticsForm",

			fromdate: null,
			todate: null,

			afterRender: function () {
				this.ParentCall();

				this.initDateRangePicker();
			},

			initDateRangePicker: function () {

				this.fromdate = DateTime.getCurrentDate();

				this.todate = DateTime.getCurrentDate();

				this.$name('date-filter').daterangepicker({
					"showDropdowns": true,
					"autoApply": true,
					"autoUpdateInput": true,
					"locale": {
						"format": "DD MMMM YYYY",
						"separator": " — ",
						"applyLabel": "Применить",
						"cancelLabel": "Отменить",
						"fromLabel": "From",
						"toLabel": "To",
						"customRangeLabel": "Custom",
						"daysOfWeek": [
							"Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"
						],
						"monthNames": [
							"Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
						],
						"firstDay": 1
					},

					"startDate": DateTime.getDate(this.fromdate, 'DD MMMM YYYY', 'DD.MM.YYYY'),
					"endDate": DateTime.getDate(this.todate, 'DD MMMM YYYY', 'DD.MM.YYYY'),
					"maxDate": DateTime.getDate(this.todate, 'DD MMMM YYYY', 'DD.MM.YYYY'),
				}, function (start, end, label) {

					this.fromdate = start.format('DD.MM.YYYY');

					this.todate = end.format('DD.MM.YYYY');

					this.$('.date-btn').removeClass('active');

					this.getStatistics();

				}.bind(this));

				this.getStatistics();
			},

			excelClick: function () {

				var args = JSON.stringify({
					fromdate: this.fromdate,
					todate: this.todate,
					excel: true
				});

				var form = $("<form>", {
					"target": "_blank",
					method: "post",
					"action": 'City/Web/Playgrounds/statisticsall'
				});

				form.append('<textarea name="options">' + args + '</textarea>');
				form.submit();
			},

			getStatistics: function () {

				var source = new City.Data.Playgrounds();

				var loader = Loader.start(this.$name('form-loader'));

				source.statisticsall({options: {
					fromdate: this.fromdate,
					todate: this.todate
				}}, function (result) {
					Loader.end(loader);

					if (result && result['labels']) {
						this.summaryDraw(result['summ']);
						this.drawAmountChart(result['labels'], result['amounts']);
						this.drawKidsChart(result['labels'], result['kids']);
					}

				}.bind(this));
			},

			dateFilterClick: function (caller) {
				$(caller).addClass('active').siblings().removeClass('active');

				switch ($(caller).attr('type')) {
					case 'today':
						this.fromdate = this.todate = DateTime.getCurrentDate();
						break;

					case 'yesterday':
						this.fromdate = this.todate = DateTime.momentObject(DateTime.getCurrentDate(), 'DD.MM.YYYY').subtract(1, 'day').format('DD.MM.YYYY');
						break;

					case "week":
						this.todate = DateTime.getCurrentDate();
						this.fromdate = DateTime.momentObject(DateTime.getCurrentDate(), 'DD.MM.YYYY').subtract(6, 'day').format('DD.MM.YYYY');
						break;

					case "month":
						this.todate = DateTime.getCurrentDate();
						this.fromdate = DateTime.momentObject(DateTime.getCurrentDate(), 'DD.MM.YYYY').subtract(31, 'day').format('DD.MM.YYYY');
						break;

					case "quartal":
						this.todate = DateTime.getCurrentDate();
						this.fromdate = DateTime.momentObject(DateTime.getCurrentDate(), 'DD.MM.YYYY').subtract(92, 'day').format('DD.MM.YYYY');
						break;

					case "year":
						this.todate = DateTime.getCurrentDate();
						this.fromdate = DateTime.momentObject(DateTime.getCurrentDate(), 'DD.MM.YYYY').subtract(366, 'day').format('DD.MM.YYYY');
						break;
				}

				this.$name('date-filter').data('daterangepicker').setStartDate(DateTime.getDate(this.fromdate, 'DD MMMM YYYY', 'DD.MM.YYYY'));
				this.$name('date-filter').data('daterangepicker').setEndDate(DateTime.getDate(this.todate, 'DD MMMM YYYY', 'DD.MM.YYYY'));

				this.getStatistics();
			},

			summaryDraw: function (obj) {

				this.$name('count-kids').html(obj.onlinekids + '/' + obj.kids);
				this.$name('amount').html(obj.amount + ' Р');
				this.$name('employees-count').html(obj.employee);
			},

			drawKidsChart: function (times, data) {

				this.$name('kids-cnt').show();

				//render chart
				this.$name('chart-kids').highcharts({
					//colors: ['#FF842C'],
					chart: {
						type: 'area'
					},
					title: {
						text: ''
					},
					xAxis: {
						lineColor: '#e5e5e5',
						minorTickColor: '#e5e5e5',
						minorGridLineColor: '#e5e5e5',
						categories: times,

						labels: {
							style: {
								fontSize: '15px'
							}
						}
					},
					yAxis: {
						tickAmount: 5,
						lineColor: '#e5e5e5',
						title: {
							text: ''
						},
						labels: {
							formatter: function () {
								return this.value;
							},
							style: {
								fontSize: '15px'
							}
						}
					},
					tooltip: {
						pointFormat: '{series.name} - Посетителей <b>{point.y:,.0f}</b>',
						style: {
							fontSize: '15px'
						}
					},
					legend: {
						enabled: true
					},
					plotOptions: {
						area: {
							fillOpacity: .1,
							lineWidth: 4,
							marker: {
								symbol: 'circle',
								radius: 4
							}
						}
					},
					series: data
				});
			},

			drawAmountChart: function (times, data) {

				this.$name('amount-cnt').show();

				//render chart
				this.$name('chart-amount').highcharts({
					//colors: ['#7cb5ec'],
					chart: {
						type: 'area'
					},
					title: {
						text: ''
					},
					xAxis: {
						lineColor: '#e5e5e5',
						minorTickColor: '#e5e5e5',
						minorGridLineColor: '#e5e5e5',
						categories: times,

						labels: {
							style: {
								fontSize: '15px'
							}
						}
					},
					yAxis: {
						tickAmount: 5,
						lineColor: '#e5e5e5',
						title: {
							text: ''
						},
						labels: {
							formatter: function () {
								return this.value + 'р';
							},
							style: {
								fontSize: '15px'
							}
						}
					},
					tooltip: {
						pointFormat: '{series.name} - Выручка <b>{point.y:,.0f} руб.</b>',
						style: {
							fontSize: '15px'
						}
					},
					legend: {
						enabled: true
					},
					plotOptions: {
						area: {
							fillOpacity: .1,
							lineWidth: 4,
							marker: {
								symbol: 'circle',
								radius: 4
							}
						}
					},
					series: data
				});
			},
		});
	});