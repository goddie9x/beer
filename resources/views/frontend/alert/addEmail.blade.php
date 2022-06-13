@extends('frontend.layout.main')
@section('content')
    <div class="container">
        <h2 class="text-center my-4">Add Email</h2>
        <div class="row list-email-element my-2">
        </div>
        <div class="btn-area d-md-flex my-2">
            <div class="col-md-6 col-md-offset-4">
                <div class="btn btn-primary add-email">
                    <i class="fa fa-plus"></i> Add more email
                </div>
            </div>
            <div class="col-md-6 col-md-offset-4">
                <button class="btn btn-primary confitm">
                    Confirm
                </button>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let emailItems = [];
        $(document).ready(function() {
            emailItems.push(genarateEmailItem(0));
            renderEmailItems();
            $('.add-email').click(function() {
                emailItems.push(genarateEmailItem(emailItems.length));
                renderEmailItems();
            });
            $('.confitm').unbind().click(function() {
                let emails = [];
                let names = [];
                let isValid = true;
                $('.email-input').each(function(index) {
                    let email = $(this).val();
                    if(emails.includes(email)) {
                        showToast({
                            type: AlertTypes.DANGER,
                            title: 'Email ' + email + ' is duplicated',
                            message: 'Email ' + email + ' is duplicated in ' + index + ' th email'
                        });
                        isValid = false;
                        return;
                    }
                    else{
                        emails.push($(this).val());
                    }
                });
                if(isValid){
                    $('.name-input').each(function() {
                        names.push($(this).val());
                    });
                    $.ajax({
                        url: '{{ route('frontend.alert.setEmailsForAlert') }}',
                        type: 'POST',
                        data: {
                            emails: emails,
                            names: names,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            showToast({
                                type: 'success',
                                title: 'Success',
                                message: 'Email has been set'
                            });
                            window.location.href = '{{ route('frontend.alert.emailsForAlert') }}';
                        },
                        error: function(data) {
                            showToast({
                                type: AlertTypes.DANGER,
                                title: 'Error',
                                message: 'Email exited or something went wrong'
                            });
                        }
                    });
                }
            });
        });

        function genarateEmailItem(index) {
            let html =
                '<div class="rounded mail-item bg-secondary p-2 bg-gradient d-flex flex-wrap position-relative my-3">' +
                '<label for="name-' + index +
                '" class="name-label col-12 my-1 col-md-4 text-center control-label my-2">Name of person ' + (
                    index + 1) + ':</label>' +
                '<div class="col-12 col-md-6 my-1">' +
                '<input id="name-' + index +
                '" type="text" class="form-control name-input" name="name" required>' +
                '</div>' +
                '<label for="email-' + index +
                '" class="email-label col-12 my-1 col-md-4 text-center control-label my-2">Email of person ' + (
                    index + 1) + ':</label>' +
                '<div class="col-12 col-md-6 my-1">' +
                '<input id="email-' + index +
                '" type="email" class="email-input form-control" name="email" required>' +
                '</div>' +
                '<div class="btn btn-danger remove-email-element position-absolute top-0 end-0 m-2" onclick="removeEmailElement(event)">' +
                '<i class="fa fa-times"></i>' +
                '</div>' +
                '</div>';
            return $(html);
        }

        function renderEmailItems() {
            $('.list-email-element').html('');
            emailItems.forEach(function(item, index) {
                $('.list-email-element').append(item);
            });
            reRangeEmailItems();
        }

        function removeEmailElement(event) {
            if (emailItems.length > 1) {
                let index = $(event).data('index');
                emailItems.splice(index, 1);
                renderEmailItems();
            } else {
                showToast({
                    type: 'warning',
                    title: 'Warning',
                    message: 'You must have at least one email'
                });
            }
        }

        function reRangeEmailItems() {
            emailItems.forEach(function(item, index) {
                item.find('.name-label').text('Name of person ' + (index + 1) + ':');
                item.find('.email-label').text('Email of person ' + (index + 1) + ':');
                item.find('.name-input').attr('name', 'name-' + index);
                item.find('.email-input').attr('name', 'email-' + index);
                item.find('.name-input').attr('id', 'name-' + index);
                item.find('.email-input').attr('id', 'email-' + index);
            });
        }
    </script>
@endsection
