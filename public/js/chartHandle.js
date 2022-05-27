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
    constructor(chartId, {
        values,
        times,
        unit,
        name,
        object,
        description,
        timeInterval,
        //threshold,
    }, chartContainer = '.chart-area') {
        this.chartId = chartId;
        this.chartContainer = chartContainer;
        this.name = name;
        this.object = object;
        this.description = description;
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.timeInterval = timeInterval;
        this.dateStart = times[0];
        /* 
                this.threshold = threshold; */

        if (values.length != times.length) {
            console.log('Error: values and times must have the same length');
            return;
        }
        this.initChart();
        this.chartSeriesXY = values.map((item, index) => {
            return [times[index], parseFloat(item)];
        });
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.initChart();
        this._this = this;
    }
    initChart() {
        let container = document.querySelector(this.chartContainer);
        let containerWidth = container.offsetWidth;
        if (!document.querySelector(this.chartId)) {
            let div = document.createElement('div');
            div.id = this.chartId;
            container.appendChild(div);
        }
        this.currentChart = Highcharts.chart(this.chartId, {
            chart: {
                type: 'spline',
                scrollablePlotArea: {
                    minWidth: 300,
                    scrollPositionX: 1
                },
                width: containerWidth,
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
    setChartData(data, index) {
        if (index) {
            this.currentChart.series[index].setData(data);
            this.currentChart.redraw();
        } else {
            data.forEach(function(item, index) {
                currentChart.series[index].setData(item);
            });
        }
    }
    addChartData(series) {
        series.forEach(function(item, index) {
            this.currentChart.series[index].setData(item);
        });
        this.currentChart.redraw();
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
    remove() {
        this.currentChart.destroy();
        $(chartId).remove();
    }
}