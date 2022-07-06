const PeriodTypes = ['hour', 'day', 'week', 'month', 'year'];
const PeriodByTimestampMilisecond = [3600000, 86400000, 604800000, 2592000000, 31536000000];
const unitList = {
    "oC": {
        name: "Templarature",
        unit: "°C"
    },
    "%": {
        name: "Percentage",
        unit: "%",
    },
    "bar": {
        name: "Pressure",
        unit: "bar"
    },
    "l/h": {
        name: "Flow",
        unit: "l/h",
    }
};
class Chart {
    constructor(chartId, data, chartContainer = '.chart-area') {
        this.chartId = chartId;
        this.chartContainer = chartContainer;
        let isSetDataSuccess = this.setChartData(data);
        if (!isSetDataSuccess) return;
        this.optionPeriodElement = document.createElement('select');
        this.optionPeriodElement.className = 'period-option form-select';
        this.optionPeriodElement.setAttribute('forChartId', this.chartId);
        this.zoomOutBtn = document.createElement('div');
        this.zoomOutBtn.className = 'zoom-out-btn position-absolute btn';
        this.zoomOutBtn.innerHTML = '<i class="fa-light fa-magnifying-glass-minus"></i>';
        this.chartWrapper = document.querySelector(this.chartId);
        this.chartDiv = document.createElement('div');
        this.chartDiv.className = 'chart-item';
        this.chartDiv.id = this.chartId;
        /* 
                this.threshold = threshold; */

        this.initOption();
    }
    initOption() {
        let _this = this;
        let titleOption = document.createElement('option');
        titleOption.value = -1;
        this.container = document.querySelector(this.chartContainer);

        titleOption.innerHTML = 'select period';
        _this.optionPeriodElement.appendChild(titleOption);
        PeriodTypes.forEach(function(item, index) {
            let optionElement = document.createElement('option');
            optionElement.setAttribute('value', index);
            optionElement.innerHTML = item;
            _this.optionPeriodElement.appendChild(optionElement);
        });
        if (!this.chartWrapper) {
            this.chartWrapper = document.createElement('div');
            this.chartWrapper.className = 'chart-wrapper position-relative my-2 chart-wrapper-' + this.chartId;
            this.container.appendChild(this.chartWrapper);
        }
        this.optionPeriodElement.addEventListener('change', function(e) {
            if (e.target.value < 0) return;
            let period = PeriodByTimestampMilisecond[e.target.value];
            let newDateStart = Date.parse(_this.dateStart) + 25200000;
            let newDateEndTimestamp = newDateStart + period;
            let newDateEnd = new Date(newDateEndTimestamp);
            newDateStart = new Date(newDateStart);
            _this.handleChangePeriod(newDateStart, newDateEnd);
        });
        this.zoomOutBtn.addEventListener('click', function(e) {
            //the timeInterval is the part of interval in xAsix, by default it's 1/12 time of period
            let period = _this.timeInterval * 144;
            if (period <= 31536000000) {
                let newDateStart = Date.parse(_this.dateStart) + 25200000 - period / 2;
                let newDateEndTimestamp = newDateStart + period;
                let newDateEnd = new Date(newDateEndTimestamp);
                newDateStart = new Date(newDateStart);
                _this.handleChangePeriod(newDateStart, newDateEnd);
            }
        });
        this.chartWrapper.appendChild(this.optionPeriodElement);
        this.chartWrapper.appendChild(this.chartDiv);
        this.chartWrapper.appendChild(this.zoomOutBtn);
        this.currentChart = this.initChart();
    }
    handleChangePeriod(dateStart, dateEnd) {
        const _this = this;
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
        _this.showLoading();
        fetch('/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                timeStart: dateStart.toISOString(),
                timeEnd: dateEnd.toISOString(),
                device: _this.deviceId,
                _token: csrf_token,
            })
        }).then((response) => {
            return response.json();
        }).then((data) => {
            _this.setChartData(data[_this.name]);
            _this.currentChart = _this.initChart();
            //_this.currentChart.redraw();
            _this.currentChart.update({});
            _this.hideLoading();
        }).catch(function(error) {
            _this.hideLoading();
            showToast({
                type: AlertTypes.DANGER,
                title: 'Lỗi',
                message: 'Không thể lấy dữ liệu',
            });
            console.log(error);
        });
    }
    getChartSerialXYWithTimeAndValue(values, times) {
        return values.map((item, index) => {
            return [times[index], parseFloat(item)];
        });
    }
    initChart() {
        let _this = this;
        let containerWidth = this.container.offsetWidth;
        this.chartPerRow = Math.floor(containerWidth / $('.select-grid-view').val()) || containerWidth;
        return Highcharts.chart(this.chartId, {
            chart: {
                type: 'spline',
                scrollablePlotArea: {
                    minWidth: 300,
                    scrollPositionX: 1
                },
                width: this.chartPerRow,
            },
            title: {
                text: this.name,
                align: 'left'
            },
            subtitle: {
                text: this.object,
                align: 'left'
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    overflow: 'justify',
                    format: this.dateFormat,
                },
            },
            yAxis: {
                title: {
                    text: this.unitName + ' ( ' + this.unit + ')'
                },
                visible: true,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: null,
                plotLines: [{
                        //low threshold
                        value: this.floor,
                        width: 2,
                        color: 'blue',
                        label: {
                            text: this.unitName + ' low threshold: ' + this.floor + ' (' + this.unit + ')',
                            align: 'right',
                            y: 12,
                            x: 0
                        },
                    },
                    {
                        //high threshold
                        value: this.ceil,
                        width: 2,
                        color: 'red',
                        label: {
                            text: this.unitName + ' high threshold: ' + this.ceil + ' (' + this.unit + ')',
                            align: 'right',
                            y: 12,
                            x: 0
                        },
                    },
                ],
            },
            tooltip: {
                valueSuffix: this.unit
            },
            plotOptions: {
                spline: {
                    lineWidth: 4,
                    states: {
                        hover: {
                            lineWidth: 5
                        }
                    },
                    marker: {
                        enabled: false
                    },
                    pointInterval: this.timeInterval,
                    pointStart: Date.parse(this.dateStart),
                    //relativeXValue: true,
                },
                series: {
                    cursor: 'pointer',
                    events: {
                        click: function(event) {
                            let period = _this.timeInterval;
                            if (period >= 7200000) {
                                let newDateStart = (new Date(event.point.name)).getTime() + 25200000 - period / 2;
                                let newDateEndTimestamp = newDateStart + period;
                                let newDateEnd = new Date(newDateEndTimestamp);
                                newDateStart = new Date(newDateStart);
                                _this.handleChangePeriod(newDateStart, newDateEnd);
                            }
                        }
                    },
                }
            },
            series: [{
                name: this.description + ' từ ' + new Date(this.dateStart).toLocaleString() + ' đến ' + new Date(this.dateEnd).toLocaleString(),
                data: this.chartSeriesXY
            }],
            navigation: {
                menuItemStyle: {
                    fontSize: '10px'
                }
            },
        });
    }
    showLoading() {
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'backdrop position-absolute d-flex justify-content-center';
        this.loading = document.createElement('div');
        this.loading.className = 'spinner-border text-success align-self-center';
        this.backdrop.appendChild(this.loading);
        this.chartWrapper.appendChild(this.backdrop);
    }
    hideLoading() {
        if (this.backdrop) this.chartWrapper.removeChild(this.backdrop);
    }
    setTitle(title) {
        this.currentChart.setTitle({
            text: title
        });
    }
    setSubtitle(subtitle) {
            this.currentChart.setSubtitle({
                text: subtitle
            });
        }
        //index with replace special location, if not we replace all series in the chart
    setChartData({
        values,
        times,
        unit,
        name,
        deviceId,
        object,
        description,
        ceil,
        floor,
    }) {
        this.name = name;
        this.deviceId = deviceId;
        this.object = object;
        this.description = description;
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.dateStart = times[0];
        this.dateEnd = times[times.length - 1];
        this.period = Date.parse(this.dateEnd) - Date.parse(this.dateStart);
        this.setTimeInterval();
        this.setDateFormat();
        if (values.length != times.length) {
            console.log('Error: values and times must have the same length');
            return false;
        }
        this.chartSeriesXY = this.getChartSerialXYWithTimeAndValue(values, times);
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.ceil = parseFloat(ceil).toFixed(2);
        this.floor = parseFloat(floor).toFixed(2);
        return true;
    }
    setTimeInterval() {
        const period = this.period;
        if (period < 21600000) {
            this.timeInterval = Math.ceil(period / 12);
        } else if (period < 345600000) {
            this.timeInterval = Math.ceil(period / 24);
        } else if (period < 5184000000) {
            this.timeInterval = Math.ceil(period / 30);
        } else {
            this.timeInterval = Math.ceil(period / 12);
        }
    }
    setDateFormat() {
        const period = this.period;
        if (period < 3600000) {
            this.dateFormat = '{value:%l:%M %p}';
        } else if (period < 2419200000) {
            this.dateFormat = '{value:%l:%M %p %e}';
        } else {
            this.dateFormat = '{value:%l:%M %p %e-%b}';
        }
    }
    removeChartData(index) {
        this.currentChart.series[index].remove(false);
        this.currentChart.redraw();
    }
    switchChartType(type) {
        this.currentChart.update({
            chart: {
                type: type
            }
        });
    }
    setWidth(width) {
        this.currentChart.setSize(width);
    }
    removeAllSeries = function() {
        this.currentChart.series.forEach(function(item, index) {
            this.currentChart.series[index].remove(false);
        });
        this.currentChart.redraw();
    }
    redraw() {
        this.currentChart.redraw();
    }
    destroyChart() {
        this.currentChart.destroy();
    }
    remove() {
        this.currentChart.destroy();
        $(this.chartWrapper).remove();
    }
}