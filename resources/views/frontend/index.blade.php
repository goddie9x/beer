@extends('frontend.layout.main')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/jquery.datetimepicker.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/home.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <div class="chart-option">
            <div class="row">
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="timeStart">Từ ngày</label>
                        <input type="text" class="form-control date-time-picker" id="timeStart" name="timeStart"
                            placeholder="Nhập ngày bắt đầu"  required>
                        <div class="valid-feedback">
                            Bạn phải nhập thời gian bắt đầu
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="timeEnd">Đến ngày</label>
                        <input type="text" class="form-control date-time-picker" id="timeEnd" name="timeEnd"
                            placeholder="Nhập ngày kết thúc" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="device">Thiết bị</label>
                        <select class="form-control select2 without-location" id="device" name="device">
                            <option value="">Chọn thiết bị</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->DeviceID }}">{{ $device->Dev_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="unit">Đơn vị</label>
                        <select class="form-control select2 without-location" id="unit" name="unit">
                            <option value="">Chọn đơn vị</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->describe_vi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="location">Địa điểm</label>
                        <select class="form-control select2" id="location" name="location">
                            <option value="">Chọn địa điểm</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->LocationID }}">{{ $location->Location }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 my-2">
                    <div class="form-group">
                        <label for="object">Đối tượng</label>
                        <select class="form-control select2 without-location" id="object" name="object">
                            <option value="">Chọn đối tượng</option>
                            @foreach ($objects as $object)
                                <option value="{{ $object->ObjectID }}">{{ $object->Obj_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="confim-container text-center my-2">
                    <div class="btn btn-primary" onclick="testApi(event)">Xác nhận</div>
                </div>
            </div>
        </div>
        <div class="chart-area">
        </div>
    @endsection
    @section('js')
        <script src="{{ URL::asset('js/highcharts.js') }}"></script>
        <script src="{{ URL::asset('js/highcharts-export-data.js') }}"></script>
        <script src="{{ URL::asset('js/highcharts-exporting.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{ URL::asset('js/chartHandle.js') }}"></script>
        <script>
            $(document).ready(function() {
                let location = $('#location');
                let allOptionWithoutLocation = $('.without-location');
                $('.select2').select2();
                $('.date-time-picker').datetimepicker({format:'Y-m-d H:m'});
                location.on('change', function(e) {
                    let selected = location.val();
                    console.log(selected);
                    if (selected) {
                        allOptionWithoutLocation.val('').trigger('change');
                        allOptionWithoutLocation.prop('disabled', true);
                    } else {
                        allOptionWithoutLocation.prop('disabled', false);
                    }
                });
            });
            /* $(document).ready(function() {
                let arrayChartTest = [];
                for(let i = 0; i < 10; i++) {
                    let chartTest = new ChartWithTemplature('chartId'+i, {
                        chartSeriesTemp,
                        chartSeriesTimes
                    });
                    arrayChartTest.push(chartTest);
                }
                arrayChartTest.forEach(function(item, index){
                    
                });
            }); */
            function testApi() {
                const timeStartElement = $('#timeStart');
                const timeEndElement = $('#timeEnd');
                const timeStart = timeStartElement.val();
                const timeEnd = timeEndElement.val();
                if (!timeStart) {
                    return;
                }
                if (!timeEnd) {
                    return;
                }
                const device = $('#device').val();
                const unit = $('#unit').val();
                const location = $('#location').val();
                const object = $('#object').val();
                const url = '/';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        timeStart: timeStart,
                        timeEnd: timeEnd,
                        device: device,
                        unit: unit,
                        location: location,
                        object: object,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        console.log(data);
                        let chartArea = $('.chart-area');
                        chartArea.empty();
                        let chart = new Chart('chartId', {
                            chartSeriesTemp: data.chartSeriesTemp,
                            chartSeriesTimes: data.chartSeriesTimes
                        });
                        chartArea.append(chart.render());
                    }
                });
            }
        </script>
    @endsection
