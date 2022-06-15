@extends('frontend.layout.main')
@section('content')
    <div class="container">
        <a href="{{ route('frontend.alert.threshold') }}" class="btn btn-primary my-2">Edit threshold</a>
        <a href="{{ route('frontend.alert.emailsForAlert') }}" class="btn btn-primary my-2">Manager email for alert</a>
        <div class="row">
            @foreach ($alerts as $alert)
                <div class="col-12 col-md-6 col-lg-4 alert-item my-2">
                    @include('mails.notify', ['data' => $alert])
                </div>
            @endforeach
        </div>
        {!! $alerts->links() !!}
    </div>
@endsection
