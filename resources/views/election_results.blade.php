@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Election Results</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Election Results</h4>
  </div>
  <div class="d-none d-md-block">
  </div>
</div>

<div class="container mt-5">

  <table id="election_results_table" class="table table-sm table-striped">
    <tbody>
    </tbody>
  </table>

</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the summary results of and election-->
<div class="modal fade" id="election_res_modal" tabindex="-1" role="dialog" aria-labelledby="elecResModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="elecResModalLabel">Election Results Details</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
          <div id="votersFormValErr" class="alert alert-danger d-none">
          </div>

      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif

      <div class="container ht-100p tx-center">
        <div class="row justify-content-center">
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 d-flex flex-column">
            <div class="tx-60 lh-1 tx-primary"><i class="icon ion-ios-stats"></i></div>
            <h5 id="votes_cast" class="tx-rubik tx-normal"></h5>
            <h5 class="mg-b-25">Votes Cast</h5>
          </div><!-- col -->
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 mg-t-40 mg-sm-t-0 d-flex flex-column">
            <div class="tx-60 lh-1 tx-success"><i class="icon ion-ios-people"></i></div>
            <h5 id="voter_base" class="tx-rubik tx-normal"></h5>
            <h5 class="mg-b-25">Voter Base</h5>
          </div><!-- col -->
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 mg-t-40 mg-md-t-0 d-flex flex-column">
            <div class="tx-60 lh-1 tx-warning"><i class="icon ion-ios-calendar"></i></div>
            <h5 id="election_period" class="tx-rubik mg-b-19 tx-13"></h5>
            <h5 class="mg-b-25">Period</h5>
          </div><!-- col -->
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 mg-t-40 mg-md-t-0 d-flex flex-column">
            <div class="tx-60 lh-1 tx-info"><i class="icon ion-ios-person"></i></div>
            <h5 id="elect_candidates" class="tx-rubik tx-small"></h5>
            <h5 class="mg-b-25">Candidates</h5>
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- container -->

      <div class="container">
        <div id="elect_candidates_res" class="row row-xs">

        </div><!-- row -->
      </div><!-- container -->

      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-dark tx-13" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- modal to confirm with user if they want to delete the voter base -->
<div class="modal fade" id="delete_voters_modal" tabindex="-1" role="dialog" aria-labelledby="confirmVotersDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmVotersDeleteLabel">Confirm Delete</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <p id="deleteVotersConfirmText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
        <button type="button" id="deleteVotersBtn"class="btn btn-danger" data-bs-dismiss="modal"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection
