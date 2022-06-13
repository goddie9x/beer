@extends('frontend.layout.main')
@section('content')
<div class="container">
    <div class="select-threshold">
        <div class="form-group my-2">
            <label for="threshold">Threshold</label>
            <select class="form-control select2 without-location" id="threshold" name="object">
                <option value="">Select threshold</option>
                @foreach ($thresholds as $threshold)
                    <option value="{{ $threshold->id }}">{{ $threshold->Dev_Name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="set-threshold-option">
        <div class="form-group my-2">
            <label for="threshold_ceil">Threshold ceil</label>
            <input type="number" class="form-control" id="threshold_ceil" name="t_ceil" placeholder="Nhập giá trị threshold">
        </div>
        <div class="form-group my-2">
            <label for="threshold_delta_ceil">Threshold delta ceil</label>
            <input type="number" class="form-control" id="threshold_delta_ceil" name="t_delta_ceil" placeholder="Nhập giá trị threshold">
        </div>
        <div class="form-group my-2">
            <label for="threshold_floor">Threshold floor</label>
            <input type="number" class="form-control" id="threshold_floor" name="t_floor" placeholder="Nhập giá trị threshold">
        </div>
        <div class="form-group my-2">
            <label for="threshold_delta_floor">Threshold delta floor</label>
            <input type="number" class="form-control" id="threshold_delta_floor" name="t_delta_floor" placeholder="Nhập giá trị threshold">
        </div>
    </div>
    <div class="btn btn-primary set-threshold-btn">Set</div>
</div>
@endsection
@section('js')
<script>
    let thresholdsInfo = <?php echo json_encode($thresholds); ?>;
    $(document).ready(function () {
        $('.set-threshold-option').hide();
        $('.select2').select2();
        $('#threshold').change(function () {
            let index = $(this).val()-1;
            if (index>=0) {
                $('.set-threshold-option').show();
                $('#threshold_ceil').val(thresholdsInfo[index].t_ceil);
                $('#threshold_delta_ceil').val(thresholdsInfo[index].t_delta_ceil);
                $('#threshold_floor').val(thresholdsInfo[index].t_floor);
                $('#threshold_delta_floor').val(thresholdsInfo[index].t_delta_floor);
            } else {
                $('.set-threshold-option').hide();
            }
        });
        $('.set-threshold-btn').click(function () {
            let index = $('#threshold').val()-1;
            if (index>=0) {
                $.ajax({
                    url: '{{ route('frontend.alert.setThreshold') }}',
                    type: 'POST',
                    data: {
                        id: thresholdsInfo[index].id,
                        t_ceil: $('#threshold_ceil').val(),
                        t_delta_ceil: $('#threshold_delta_ceil').val(),
                        t_floor: $('#threshold_floor').val(),
                        t_delta_floor: $('#threshold_delta_floor').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: async function (res) {
                        if (res.success) {
                            thresholdsInfo[index].t_ceil = $('#threshold_ceil').val();
                            thresholdsInfo[index].t_delta_ceil = $('#threshold_delta_ceil').val();
                            thresholdsInfo[index].t_floor = $('#threshold_floor').val();
                            thresholdsInfo[index].t_delta_floor = $('#threshold_delta_floor').val();
                            showToast({
                                title: 'Thành công',
                                message: res.message,
                                type: 'success'
                            });
                        } else {
                            showToast(
                                {
                                    title: 'Lỗi',
                                    message: res.message,
                                    type: 'error'
                                }
                            );
                        }
                    }
                });
            }
        });
    });
</script>
@endsection