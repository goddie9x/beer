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
        chartSeriesValues,
        chartSeriesTimes,
        unit,
    }, chartContainer = '.chart-area') {
        this.chartId = chartId;
        this.chartContainer = chartContainer;
        if (chartSeriesValues.length != chartSeriesTimes.length) {
            console.log('Error: chartSeriesValues and chartSeriesTimes must have the same length');
            return;
        }
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.initChart();
        this.chartSeriesXY = chartSeriesValues.map((item, index) => {
            return [chartSeriesTimes[index], parseFloat(item)];
        });
        this.unit = unitList[unit].unit;
        this.unitName = unitList[unit].name;
        this.initChart();
        this._this = this;
    }
    initChart() {
        if ($(this.chartId).length <= 0) {
            $(this.chartContainer).prepend('<div id="' + this.chartId + '""></div>');
        }
        this.currentChart = Highcharts.chart(this.chartId, {
            chart: {
                type: 'spline',
                scrollablePlotArea: {
                    minWidth: 600,
                    scrollPositionX: 1
                }
            },
            title: {
                text: 'Templature',
                align: 'left'
            },
            subtitle: {
                text: 'For today',
                align: 'left'
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    overflow: 'justify'
                }
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
                    //pointInterval: 60000, // one minute
                    //pointStart: Date.UTC(2021, 11, 15, 3, 59, 0),
                    relativeXValue: true
                }
            },
            series: [{
                name: 'Hestavollane',
                data: this.chartSeriesXY
            }],
            navigation: {
                menuItemStyle: {
                    fontSize: '10px'
                }
            }
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
            currentChart.series[index].setData(item);
        });
        currentChart.redraw();
    }
    removeChartData(index) {
        currentChart.series[index].remove(false);
        currentChart.redraw();
    }
    switchChartType(type) {
        currentChart.update({
            chart: {
                type: type
            }
        });
    }
    removeAllSeries = function() {
        currentChart.series.forEach(function(item, index) {
            currentChart.series[index].remove(false);
        });
        currentChart.redraw();
    }
    remove() {
        currentChart.destroy();
        $(chartId).remove();
    }
}