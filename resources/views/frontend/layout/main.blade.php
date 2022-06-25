<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Systems analysis') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/all.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}" type="text/css">
    @yield('css')
</head>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beer information</title>
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}" type="text/css">
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
                            <a class="nav-link" aria-current="page" href="{{ url('/') }}">Triending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.alert') }}">Alert</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('frontend.report')}}">Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Manual input</a>
                        </li>
                        <!-- Right Side Of Navbar -->
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role==1)
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="toasts-container two-k-z-index position-fixed top-0 end-0 p-3"></div>
    @yield('content')
    <script src="{{ URL::asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('js/toastGenerate.js') }}"></script>
    <script src="{{ URL::asset('js/index.js') }}"></script>
    @yield('js')
</body>

</html>
