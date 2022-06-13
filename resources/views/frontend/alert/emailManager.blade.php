@extends('frontend.layout.main')
@section('content')
    <div class="container">
        <div class="row">
            @foreach ($emails as $key => $email)
                <div class="col-12 col-md-6">
                    <div class="email-wrapper m-1  bg-secondary bg-gradient p-1">
                        <label for="email" class="col-9">{{ $email->name }}</label>
                        <label for="active-{{ $key }}">Active</label>
                        <input type="checkbox" class="active-checkbox" email-id="{{ $email->id }}"
                            id="active-{{ $key }}" name="active" {{ $email->active ? 'checked' : '' }}>
                            <div class="btn btn-danger delete-email">
                                <i class="fa fa-trash"></i>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
        {!! $emails->links() !!}
        <a href="{{ route('frontend.alert.addEmailForAlert') }}" class="btn btn-primary my-2">Add email</a>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('active-checkbox').change(function() {
                var email_id = $(this).attr('email-id');
                var active = $(this).is(':checked');
                $.ajax({
                    url: '/',
                    type: 'POST',
                    data: {
                        email_id: email_id,
                        active: active,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
