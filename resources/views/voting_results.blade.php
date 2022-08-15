@extends('layouts.app')

@section('content')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url('/images/img/vote-results-header.jpg');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Voting Results</h2>
  </div>
</div>

<div class="container">
<div class="row row-xs">
  @php
    $highestVotes = 0;
  @endphp

@foreach( $electionsList as $election )
  <h3 class="mt-5">{{ $election->name }}</h3>   
  
  
  @if ($election->end_date > NOW()) 
    <p>
    <span class="tx-11 tx-uppercase tx-spacing-1 tx-semibold mg-b-10 tx-success">ends on: </span>
      {{ date_format(new DateTime($election->end_date),"d-M-Y"); }} 
    </p>
  @else
    <p>
    <span class="tx-11 tx-uppercase tx-spacing-1 tx-semibold mg-b-10 tx-danger">ended on: </span>
       {{ date_format(new DateTime($election->end_date),"d-M-Y"); }} 
    </p>
  @endif
  

  @foreach( $postsList as $post )

    @if($post->election_id == $election->id)
       <h4><span class="tx-11 tx-uppercase tx-spacing-1 tx-semibold mg-b-10 tx-primary">Position:</span> {{ $post->post_name }}</h4>

      @foreach( $electionResults as $result )
      @if($result->post_id == $post->id && $result->election_id == $election->id)

        <div class="col-lg-3">
          <article class="d-flex flex-column">

            <div class="post-img">
              <img src="{{ asset('images/candidates/'.$result->image  ) }}" alt="{{ $result->candidate_name }} image" class="img-fluid">
            </div>

            <h2 class="title m-0">
              @if($result->total_votes > $highestVotes)
                @php
                  $highestVotes = $result->total_votes;
                @endphp
                
                <i class="bi bi-stars" style="color: #f5cf13;"></i> <strong><u> {{ $result->candidate_name }} </u></strong><i class="bi bi-stars" style="color: #f5cf13;"></i>

                @else
                {{ $result->candidate_name }}
              @endif
            
            </h2>

            <div class="content p-0">
              <p>
              <h3 class="tx-normal tx-primary mg-b-0 mg-r-5 lh-1">{{ $result->total_votes }}</h3> Vote(s) received
              </p>
            </div>
          </article>
        </div>

        <!-- <div class="col-6 col-sm-4 col-md-3 col-xl mg-t-10 mg-md-t-0">
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="{{ asset('images/candidates/'.$result->image ) }}" alt="{{ $result->candidate_name }} image" style="width:100%">
          <div class="card-body">
            <h4 class="card-title">{{ $result->candidate_name }}</h4>
            <p class="card-text"><h3 class="tx-normal tx-primary mg-b-0 mg-r-5 lh-1">{{ $result->total_votes }}</h3> Vote(s) received</p>
          </div>
        </div>
      </div> -->
      @endif
      @endforeach
      
    @endif
  @endforeach

    @php
      $highestVotes = 0;
    @endphp

    @endforeach
  </div><!-- row -->
</div>
@endsection
