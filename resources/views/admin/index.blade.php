@extends('admin.layout.main')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/home.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <div class="btn btn-primary post-unit">Post all unit to mysql</div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.post-unit').click(function() {
                console.log('click');
                $.ajax({
                    url: '{{ route('admin.postAllUnit') }}',
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
