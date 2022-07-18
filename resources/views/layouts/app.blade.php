<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
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

                            @if (Auth::user()->user_role == 1)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manage_elections') }}">{{ __('Elections') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manage_positions') }}">{{ __('Election Positions') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manage_candidates') }}">{{ __('Candidates') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manage_users') }}">{{ __('Manage Users') }}</a>
                                </li>
                            @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Voting Results') }}</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
            </div>
        </nav>

        <main class="py-4">
            @yield('content')

            <!-- Alert message for user to show vote cast successfully-->
            <div id="successAlert" class="modal alert-success h-auto  fade show alert-dismissible" role="document">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <br>
            <p class="fs-4 text-center"><strong>Success!</strong> Action completed successfully.</p>
            <br>
            </div>

            <!-- Alert message for user to show vote not cast-->
            <div id="failedAlert" class="modal alert-danger h-auto fade show alert-dismissible" role="document">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <br>
            <p class="fs-4 text-center"><strong>Danger!</strong> Failed to complete action. Try again</p>
            <br>
            </div>
        </main>
    </div>
    <!-- <script src="{{ asset('js/external/jquery/jquery.js') }}"></script>-->
    <script src="{{ asset('assets/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script> -->
    <script src="{{ asset('js/jquery-ui.js') }}"></script> 
    <script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
