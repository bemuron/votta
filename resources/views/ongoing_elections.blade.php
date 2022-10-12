@extends('layouts.app')

@section('content')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url('{{ asset('images/img/ongoing-elections.jpg' ) }}');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Ongoing Elections</h2>
  </div>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->
<div class="container mt-5">
@if (count($electionsList) > 0)
<section id="blog" class="blog p-0">
      <div class="container" data-aos="fade-up">

      <div class="row gy-5 posts-list">

      @php
          $electionCount = 0;
      @endphp
      @foreach( $electionsList as $election )
        <div class="col-lg-3">
          <article class="d-flex flex-column">

            <div class="post-img">
              <img src="{{ asset('images/elections/'.$election->image ) }}" alt="" class="img-fluid">
            </div>

            <h2 class="title">
            {{ $election->name }}
            </h2>

            <div class="meta-top">
              <ul>
                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <time datetime="2022-01-01">{{ date_format(date_create($election->end_date), "d-M-Y") }}</time></li>
              </ul>
            </div>

            <div class="content p-0">
              <p>
              {{ $election->description }}
              </p>
            </div>

            <div class="read-more mt-auto align-self-end">
              <form method="GET" action="{{ route('election_details') }}">
                <input type="hidden" class="form-control" id="election_id" name="election_id" value="{{ $election->id }}">
                <button type="submit" id="election_button" class="btn btn-info">
                    {{ __(' View') }} <i class="bi bi-arrow-right"></i>
                </button>
              </form>
            </div>

          </article>
        </div><!-- End post list item -->
        @php
          $electionCount++;
        @endphp
        
        @endforeach

        </div><!-- End posts list -->

      </div>
    </section><!-- End Blog Section -->
    @else
      <div class="content tx-center p-0">
      <h3>No ongoing elections at the moment</h3>
      </div>
      
    @endif
</div>
@endsection
