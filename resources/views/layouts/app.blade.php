<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Online Votting System</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/img/favicon.png">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/lib/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/styles2.css') }}"> -->
    
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="page-index">
    <!--show overlay loading spinner-->
    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <!--overlay loading spinner end-->
    
    <!-- <div id="app"> -->
        <header id="header" class="header d-flex align-items-center fixed-top">
            <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('images/img/logo.png' ) }}" alt="logo">
            </a>

            <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
            <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

            <nav id="navbar" class="navbar">
                <ul >
                <li>
                    <a href="{{ route('ongoing_elections') }}">{{ __('Ongoing Elections') }}</a>
                </li>
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li>
                            <a href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <!-- <li>
                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li> -->
                    @endif
                @else

                    @if (Auth::user()->user_role == 1)
                        <li>
                            <a href="{{ route('statistics') }}">{{ __('Dashboard') }}</a>
                        </li>
                    @endif
                        <li>
                            <a href="{{ route('voting_results') }}">{{ __('Voting Results') }}</a>
                        </li>
                        <li class="dropdown"><a href="#"><span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li><a href="{{ route('changepassword') }}"> Change Password</a></li>
                                <li>
                                    <!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> -->
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    <!-- </div> -->
                                </li>
                            </ul>
                        </li>
                @endguest
                </ul>
            </nav><!-- .navbar -->

            </div>
        </header>
        
        <main id="main">
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
            <p class="fs-4 text-center"><strong>Sorry!</strong> Failed to complete action. Try again</p>
            <br>
            </div>
        </main>
    <!-- </div> -->

    <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

<div class="footer-content">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-5 col-md-12 footer-info">
        <a href="index.html" class="logo d-flex align-items-center">
          <span>Votta</span>
        </a>
        <p>Votta online votting system. Handle your organisation's elections or polls the smart way</p>
        <div class="social-links d-flex  mt-3">
          <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bi bi-dash"></i> <a href="{{ route('ongoing_elections') }}">Ongoing Elections</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">About us</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">Services</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">Terms of service</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">Privacy policy</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-6 footer-links">
        <h4>Our Services</h4>
        <ul>
          <li><i class="bi bi-dash"></i> <a href="#">Election Management</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">Online Polls</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">e-Voting</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">e-Ballot</a></li>
          <li><i class="bi bi-dash"></i> <a href="#">Online Elections</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
        <h4>Contact Us</h4>
        <p>
          Some Street <br>
          City, Code<br>
          Country <br><br>
          <strong>Phone:</strong> +256 700 345 556<br>
          <strong>Email:</strong> info@votta.com<br>
        </p>

      </div>

    </div>
  </div>
</div>

</footer><!-- End Footer -->
<!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

    <!-- JS Files -->
    <!-- <script src="{{ asset('assets/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
    <script src="{{ asset('assets/lib/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/lib/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/lib/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/lib/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script> 
    <script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/votta.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
