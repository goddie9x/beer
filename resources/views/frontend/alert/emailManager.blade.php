@extends('frontend.layout.main')
@section('content')
    <div class="container">

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emails as $key => $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center"><input type="checkbox" class="active-checkbox"
                                email-id="{{ $item->id }}" id="active-{{ $key }}" name="active"
                                {{ $item->active ? 'checked' : '' }}></td>
                        <td class="text-center">
                            <div class="btn btn-danger delete-email" email-id="{{ $item->id }}">
                                <i class="fa fa-trash"></i>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex">
            {!! $emails->links() !!}
        </div>
        <a href="{{ route('frontend.alert.addEmailForAlert') }}" class="btn btn-primary my-2">Add email</a>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.active-checkbox').change(function() {
                let currentCheckBox = $(this);
                let email_id = currentCheckBox.attr('email-id');
                let active = currentCheckBox.is(':checked');
                currentCheckBox.prop('disabled', true);
                $.ajax({
                    url: '{{route('frontend.alert.emailsForAlert')}}',
                    type: 'POST',
                    data: {
                        id: email_id,
                        active: active,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        showToast({
                            type: 'success',
                            title: 'Success',
                            message: 'Set active email success'
                        });
                        currentCheckBox.prop('disabled', false);
                    },
                    error: function(data) {
                        showToast({
                            type: 'danger',
                            title: 'Failed',
                            message: 'Set active email failed'
                        });
                        currentCheckBox.prop('disabled', false);
                    }
                });
            });
            $('.delete-email').unbind().click(function() {
                let currentDeleteBtn = $(this);
                let email_id = currentDeleteBtn.attr('email-id');
                console.log(email_id);
                currentDeleteBtn.prop('disabled', true);
                $.ajax({
                    url: '{{ route('frontend.alert.deleteEmail') }}',
                    type: 'POST',
                    data: {
                        id: email_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        showToast({
                            type: 'success',
                            title: 'Success',
                            message: 'Deleted email'
                        });
                        location.reload();
                    },
                    error: function(data) {
                        showToast({
                            type: 'danger',
                            title: 'Failed',
                            message: 'Delete email failed'
                        });
                        currentDeleteBtn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
