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
        this.optionPeriodElement.className = 'period-option form-control';
        this.optionPeriodElement.setAttribute('forChartId', this.chartId);
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
            this.chartWrapper.className = 'chart-wrapper my-2 chart-wrapper-' + this.chartId;
            this.container.appendChild(this.chartWrapper);
        }
        this.optionPeriodElement.addEventListener('change', function(e) {
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            let period = PeriodByTimestampMilisecond[e.target.value];
            let newDateStart = (new Date(_this.dateStart)).getTime() + 25200000;
            let newDateEndTimestamp = newDateStart + period;
            let newDateEnd = new Date(newDateEndTimestamp);
            newDateStart = new Date(newDateStart);
            console.log(newDateStart.toISOString(), newDateEnd.toISOString());
            fetch('/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    timeStart: newDateStart.toISOString(),
                    timeEnd: newDateEnd.toISOString(),
                    device: _this.deviceId,
                    _token: csrf_token,
                })
            }).then(function(response) {
                return response.json();
            }).then(function(data) {
                _this.setChartData(data[_this.name]);
                _this.currentChart = _this.initChart();
                _this.currentChart.redraw();
            }).catch(function(error) {
                console.log(error);
            });
        });
        this.chartWrapper.appendChild(this.optionPeriodElement);
        this.chartWrapper.appendChild(this.chartDiv);
        this.currentChart = this.initChart();
    }
    getChartSerialXYWithTimeAndValue(values, times) {
        return values.map((item, index) => {
            return [times[index], parseFloat(item)];
        });
    }
    initChart() {
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
                    format: '{value:%b-%e %l:%M %p }',
                },
                relativeXValue: true
            },
            yAxis: {
                title: {
                    text: this.unitName + ' ( ' + this.unit + ')'
                },
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: null,
                /*plotBands: [{ // Cooler
                    from: -5,
                    to: -3,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: 'Cooler',
                        style: {
                            color: '#606060'
                        }
                    }
                }, { // Normal
                    from: -3,
                    to: -1,
                    color: 'rgba(0, 0, 0, 0)',
                    label: {
                        text: 'Normal',
                        style: {
                            color: '#606060'
                        }
                    }
                }, { // Warm
                    from: -1,
                    to: 3,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: 'Warm',
                        style: {
                            color: '#606060'
                        }
                    }
                }]*/
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
                    //pointStart: new Date(this.dateStart),
                    relativeXValue: true
                }
            },
            series: [{
                name: this.description,
                data: this.chartSeriesXY
            }],
            navigation: {
                menuItemStyle: {
                    fontSize: '10px'
                }
            },
        });
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
        timeInterval,
        //threshold,
    }) {
        this.name = name;
        this.deviceId = deviceId;
        this.object = object;
        this.description = description;
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.timeInterval = timeInterval;
        this.dateStart = times[0];
        if (values.length != times.length) {
            console.log('Error: values and times must have the same length');
            return false;
        }
        this.chartSeriesXY = this.getChartSerialXYWithTimeAndValue(values, times);
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        return true;
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