<!DOCTYPE html>
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
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link href="{{ asset('assets/lib/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  </head>
  <body>
    <!--show overlay loading spinner-->
    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <!--overlay loading spinner end-->

    <aside class="aside aside-fixed">
      <div class="aside-header">
      <a href="{{ url('/') }}" class="aside-logo">
                <img src="{{ asset('images/img/dash_logo.png' ) }}" alt="logo">
            </a>
        <!-- <a href="../../index.html" class="aside-logo">dash<span>forge</span></a> -->
        <a href="" class="aside-menu-link">
          <i data-feather="menu"></i>
          <i data-feather="x"></i>
        </a>
      </div>
      <div class="aside-body">
        @guest
          <script>
          // go to login page
          window.location.replace("/login");
          </script>
        @else
          @if (Auth::user()->user_role == 1)
          <div class="aside-loggedin">
            <div class="d-flex align-items-center justify-content-start">
            </div>
            <div class="aside-loggedin-user">
              <div class="d-flex align-items-center justify-content-between mg-b-2">
                  
                <h6 class="tx-semibold mg-b-0">{{ Auth::user()->name }}</h6>

                <a href="{{ route('logout') }}" data-toggle="tooltip" title="Log out"
                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i>
                </a>
              </div>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
              <p class="tx-color-03 tx-12 mg-b-0">Administrator</p>
            </div>
          </div><!-- aside-loggedin -->
          @else
            <script>
            // go to home page if not admin
            window.location.replace("/home");
            </script>
          @endif
          
          @endguest

          <ul class="nav nav-aside">
            <li class="nav-label">Dashboard</li>
            <li class="nav-item"><a href="{{ route('statistics') }}" class="nav-link"><i data-feather="shopping-bag"></i> <span>Home</span></a></li>
            <li class="nav-label mg-t-25">Election Management</li>
            <li class="nav-item active" id="dash-elections"><a href="{{ route('manage_elections') }}" class="nav-link"><i data-feather="list"></i> <span>Elections</span></a></li>
            <li class="nav-item" id="dash-posts"><a href="{{ route('manage_positions') }}" class="nav-link"><i data-feather="clipboard"></i> <span>Posts</span></a></li>
            <li class="nav-item" id="dash-candidates"><a href="{{ route('manage_candidates') }}" class="nav-link"><i data-feather="user-check"></i> <span>Candidates</span></a></li>
            <li class="nav-label mg-t-25">User Management</li>
            <li class="nav-item" id="dash-users"><a href="{{ route('manage_users') }}" class="nav-link"><i data-feather="users"></i> <span>Users</span></a></li>
            <li class="nav-item" id="dash-divisions"><a href="{{ route('manage_divisions') }}" class="nav-link"><i data-feather="git-branch"></i> <span>Divisions</span></a></li>
            <li class="nav-item" id="dash-sub-divisions"><a href="{{ route('manage_sub_divisions') }}" class="nav-link"><i data-feather="grid"></i> <span>Sub Divisions</span></a></li>
            <li class="nav-label mg-t-25">Reports</li>
            <li class="nav-item" id="dash-users"><a href="{{ route('statistics') }}" class="nav-link"><i data-feather="book"></i> <span>All Reports</span></a></li>

            <!-- <li class="nav-label mg-t-25">Pages</li>
            <li class="nav-item with-sub">
              <a href="" class="nav-link"><i class="bi bi-person"></i> <span>User Pages</span></a>
              <ul>
                <li><a href="page-profile-view.html">View Profile</a></li>
                <li><a href="page-connections.html">Connections</a></li>
                <li><a href="page-groups.html">Groups</a></li>
                <li><a href="page-events.html">Events</a></li>
              </ul>
            </li>
            <li class="nav-item with-sub">
              <a href="" class="nav-link"><i data-feather="file"></i> <span>Other Pages</span></a>
              <ul>
                <li><a href="page-timeline.html">Timeline</a></li>
              </ul>
            </li>
            <li class="nav-label mg-t-25">User Interface</li>
            <li class="nav-item"><a href="../../components" class="nav-link"><i data-feather="layers"></i> <span>Components</span></a></li>
            <li class="nav-item"><a href="../../collections" class="nav-link"><i data-feather="box"></i> <span>Collections</span></a></li> -->
          </ul>
      </div>
    </aside>

    <div class="content pd-0">

      <div class="content-body">
        <div class="container pd-x-0">

        @yield('content')
          

        </div>
      </div>

      </div>

    <!-- <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> -->

    <div id="preloader"></div>

    <!-- JS Files -->
    <script src="{{ asset('assets/lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/lib/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/lib/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/lib/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/lib/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script> 
    <script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('assets/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/dashforge.js') }}"></script>
    <script src="{{ asset('js/dashforge.aside.js') }}"></script>
    <script src="{{ asset('js/mainjs.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/users.js') }}"></script>

    </body>
</html>