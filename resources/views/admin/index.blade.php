@extends('admin.layout.main')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/home.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <div class="btn btn-primary init-unit">Init unit in mysql</div>
        <div class="btn btn-primary init-threshold">Init Threshold in mysql</div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.init-unit').click(function() {
                $.ajax({
                    url: '{{ route('admin.initAllUnit') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        alert('success' + data);
                    },
                    error: function(data) {
                        alert('error' + data);
                    }
                });
            });
            $('.init-threshold').click(function() {
                $.ajax({
                    url: '{{ route('admin.initThreshold') }}',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        alert('success' + data);
                    },
                    error: function(data) {
                        alert('error' + data);
                    }
                });
            });
        });
    </script>
@endsection
