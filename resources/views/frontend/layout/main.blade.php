<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beer information</title>
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}" type="text/css">
    @yield('css')
</head>

<body>
    <div class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-nav"
                    aria-controls="header-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="header-nav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Triending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Manual input</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    @yield('content')
    <script src="{{ URL::asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('js/index.js') }}"></script>
    @yield('js')
</body>

</html>
