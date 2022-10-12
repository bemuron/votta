@extends('layouts.app')

@section('content')

<!-- ======= Hero Section ======= -->
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <h2 class="d-flex justify-content-center" data-aos="fade-up">Your Vote Your Voice</h2>
          <blockquote data-aos="fade-up" data-aos-delay="100">
            <h3 class="text-white">Votta Online voting system makes handling of voting or election polls easy. 
              Quickly set up elections and candidates and have users vote. View results of polls.</h3>
          </blockquote>
          <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('login') }}" class="btn-get-started">Get Started</a>
          </div>

        </div>
      </div>
    </div>
  </section><!-- End Hero Section -->

  <!-- ======= Features ======= -->
  <section id="services-list" class="services-list">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Features</h2>

        </div>

        <div class="row gy-5">

          <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
            <div class="icon flex-shrink-0"><i class="bi bi-person-plus" style="color: #f57813;"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">User Management</a></h4>
              <p class="description">As admin you can add, delete, edit all users in the system.</p>
            </div>
          </div>
          <!-- End Service Item -->

          <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="200">
            <div class="icon flex-shrink-0"><i class="bi bi-check2-square" style="color: #15a04a;"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Elections Management</a></h4>
              <p class="description">Create, edit, update, delete elections. Set start and end dates. Add candidates to an election</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="300">
            <div class="icon flex-shrink-0"><i class="bi bi-person-check" style="color: #d90769;"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Candidate Management</a></h4>
              <p class="description">Remove candidates from elections. Add candidates to several elections.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="400">
            <div class="icon flex-shrink-0"><i class="bi bi-card-checklist" style="color: #15bfbc;"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Elective Postions</a></h4>
              <p class="description">Add many elective positions, assign them to different elections.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="500">
            <div class="icon flex-shrink-0"><i class="bi bi-123" style="color: #f5cf13;"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Voting Results</a></h4>
              <p class="description">View results of elections as users vote</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>
    </section><!-- End Features Section -->

    <section id="recent-posts" class="recent-posts pt-0">
      <div class="container" data-aos="fade-up">

      <div class="section-header p-0">
        <h2>Ongoing Elections</h2>
      </div>

      <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('ongoing_elections') }}">View All Elections</a> <i class="bi bi-arrow-right"></i>
      </div>

      <div class="row gy-5">

      @php
          $electionCount = 0;
      @endphp
      @foreach( $electionsList as $election )
        @if ($electionCount < 4)

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="post-box">
            <div class="post-img"><img src="{{ asset('images/elections/'.$election->image ) }}" class="img-fluid" alt=""></div>
            <h3 class="post-title">{{ $election->name }}</h3>
            <div class="meta">
              <span class="post-date"><i class="bi bi-clock"></i> {{ date_format(date_create($election->end_date), "d-M-Y") }}</span>
            </div>
            <p>{{ $election->description }}</p>
            <form method="GET" action="{{ route('election_details') }}">
              <input type="hidden" class="form-control" id="election_id" name="election_id" value="{{ $election->id }}">
              <button type="submit" id="election_button" class="btn btn-info">
                  {{ __(' View') }} <i class="bi bi-arrow-right"></i>
              </button>
            </form>
          </div>
        </div>
        @endif
        @php
          $electionCount++;
        @endphp
        
        @endforeach

        </div>

      </div>
    </section><!-- End Blog Section -->

    <!-- ======= Call To Action Section ======= -->
    <section id="call-to-action" class="call-to-action">
      <div class="container" data-aos="fade-up">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center">
          <blockquote class="blockquote">
            <p>"Regardless of who wins, an election should be a time for optimism and fresh approaches."</p>
            </blockquote>
            <h4 class="text-white">Gary Johnson</h4>
            <a class="cta-btn" href="{{ route('login') }}">{{ __('Get Started') }}</a>
          </div>
        </div>

      </div>
    </section><!-- End Call To Action Section -->
@endsection
