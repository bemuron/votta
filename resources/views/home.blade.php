@extends('layouts.app')

@section('content')
<!-- Carousel -->
<div id="demo" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicators/dots -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
  </div>
  
  <!-- The slideshow/carousel -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://via.placeholder.com/1100x281" alt="Los Angeles" class="d-block w-100">
      <div class="carousel-caption">
        <h3>Los Angeles</h3>
        <p>We had such a great time in LA!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://via.placeholder.com/1100x281" alt="Chicago" class="d-block w-100">
      <div class="carousel-caption">
        <h3>Chicago</h3>
        <p>Thank you, Chicago!</p>
      </div> 
    </div>
    <div class="carousel-item">
      <img src="{{ asset('images/elections/votta.jpg') }}" alt="New York" class="d-block w-100"
      >
      <div class="carousel-caption">
        <h3>New York</h3>
        <p>We love the Big Apple!</p>
      </div>  
    </div>
  </div>
  
  <!-- Left and right controls/icons -->
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<div class="container mt-5">
  <h3>Ongoing Elections</h3>
  <div class="row row-xs">
  @foreach( $electionsList as $election )
  <div class="col-6 col-sm-4 col-md-3 col-xl">
      <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="{{ asset('images/elections/'.$election->image ) }}" alt="Card image" style="width:100%">
        <div class="card-body">
          <h4 class="card-title">{{ $election->name }}</h4>
          <p class="card-text">{{ $election->description }}</p>
          <form method="GET" action="{{ route('election_details') }}">
            <input type="hidden" class="form-control" id="election_id" name="election_id" value="{{ $election->id }}">
            <!-- <a href="#" class="btn btn-outline-info" id="{{ $election->id }}">View</a> -->
            <button type="submit" id="election_button" class="btn btn-info">
                {{ __(' View') }}
            </button>
          </form> 
        </div>
      </div>
    </div><!-- col -->
    @endforeach
  </div><!-- row -->
</div>
@endsection
