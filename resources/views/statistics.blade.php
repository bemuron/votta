@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item active" aria-current="page">Dashboard Home</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Votta Statistics</h4>
  </div>
</div>

<div class="row row-xs">

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $allElections[0]->allelections }}</h3>
        <p class="tx-11 tx-color-03 mg-b-0"> Both running and ended</p>
      </div>
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Ongoing Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $ongoingPolls->ongoingelects }}</h3>
        <p class="tx-11 tx-color-03 mg-b-0">currently ongoing</p>
      </div>
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Completed Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $completedPolls->finishedelects }}</h3>
        <p class="tx-11 tx-color-03 mg-b-0">completed</p>
      </div>
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Votes Cast</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">{{ $votesCast->numvotes }}</h3>
        <p class="tx-11 tx-color-03 mg-b-0">Total votes cast</p>
      </div>
    </div>
  </div><!-- col -->

  <div class="col-md-6 col-xl-4 mg-t-10 order-md-1 order-xl-0">
    <div class="card ht-lg-100p">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mg-b-0">Elections recently completed</h6>
        <div class="tx-13 d-flex align-items-center">
          <span class="mg-r-5">Showing:</span> <a href="" class="d-flex align-items-center link-03 lh-0">{{ count($completedPollsDets) }} </a>
        </div>
      </div><!-- card-header -->
      <div class="card-body pd-0">
        <div class="table-responsive">
          <table class="table table-borderless table-dashboard table-dashboard-one">
              @if (count($completedPollsDets) > 0)
            <thead>
              <tr>
                <th class="wd-30">Name</th>
                <th class="wd-25 text-right">Candidates</th>
                <th class="wd-45 text-right">Ended On</th>
              </tr>
            </thead>
            <tbody>
                @foreach( $completedPollsDets as $completed )
              <tr>
                <td class="tx-medium">{{ $completed->name }}</td>
                <td class="text-right">{{ $completed->numcandidates }}</td>
                <td class="text-right">{{ date_format(date_create($completed->end_date),"d M Y")  }}</td>
              </tr>
              @endforeach
            </tbody>
            @else
                <tbody>
                <tr>
                  <td class="align-middle">No completed elections</td>
                </tr>
              </tbody>  
            @endif
          </table>
        </div><!-- table-responsive -->
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col -->

  <div class="col-lg-12 col-xl-8 mg-t-10">
    <div class="card mg-b-10">
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h6 class="mg-b-5">Recently started elections</h6>
          <p class="tx-13 tx-color-03 mg-b-0">Showing {{ count($startedElections) }} recently started elections</p>
        </div>
      </div><!-- card-header -->
      <div class="card-body pd-y-30">
      </div><!-- card-body -->
      <div class="table-responsive">
        <table class="table table-dashboard mg-b-0">
          <thead>
            <tr>
              <th>Election Name</th>
              <th class="text-right">Candidates</th>
              <th class="text-right">Started On</th>
              <th class="text-right">Ends On</th>
              <th class="text-right">Status</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($startedElections as $election)
            <tr>
              <td class="tx-color-03 tx-pink">{{ $election->name }}</td>
              <td class="text-right tx-teal">{{ $election->numcandidates }}</td>
              <td class="tx-medium text-right">{{ date_format(date_create($election->start_date),"d M Y") }}</td>
              <td class="text-right tx-pink">{{ date_format(date_create($election->end_date),"d M Y") }}</td>
              <td class="tx-medium text-right">{{ $election->status }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- table-responsive -->
    </div><!-- card -->
  </div><!-- col -->

  <div class="col-lg-12 col-xl-8 mg-t-10">
    <div class="card mg-b-10">
      <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
        <div>
          <h6 class="mg-b-5">Recently created candidates</h6>
          <p class="tx-13 tx-color-03 mg-b-0">Showing {{ count($createdcandidates) }} recently created candidates</p>
        </div>
      </div><!-- card-header -->
      
      <div class="card-body pd-y-30">
      </div><!-- card-body -->
      <div class="table-responsive">
        <table class="table table-dashboard mg-b-0">
          <thead>
            <tr>
              <th>Candidate Name</th>
              <th class="text-right">Election</th>
              <th class="text-right">Post</th>
              <th class="text-right">Created On</th>
              <th class="text-right">Election Status</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($createdcandidates as $candidate)
            <tr>
              <td class="tx-color-03 tx-pink">{{ $candidate->candidate_name }}</td>
              <td class="tx-medium text-right">{{ $candidate->election_name }}</td>
              <td class="text-right tx-teal">{{ $candidate->post_name }}</td>
              <td class="text-right tx-pink">{{ date_format(date_create($candidate->created_at),"d M Y") }}</td>
              <td class="tx-medium text-right">{{ $candidate->election_status }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- table-responsive -->
    </div><!-- card -->
  </div><!-- col -->

  <div class="col-md-6 col-xl-4 mg-t-10">
    <div class="card ht-100p">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mg-b-0">Recently Added Users</h6>
        <p class="tx-13 tx-color-03 mg-b-0">Showing {{ count($usersAdded) }} users</p>
      </div>
      <ul class="list-group list-group-flush tx-13">
          <li class="list-group-item d-flex pd-sm-x-20">
          <div class="pd-sm-l-10">
            <p class="tx-medium tx-info  mg-b-0">Name</p>
          </div>
          <div class="mg-l-auto text-right">
            <p class="tx-medium tx-info  mg-b-0">Sub Division</p>
          </div>
          <div class="mg-l-auto text-right">
            <p class="tx-medium tx-info  mg-b-0">Date Added</p>
          </div>    
        </li>
          @foreach($usersAdded as $user)
        <li class="list-group-item d-flex pd-sm-x-20">
          <div class="pd-sm-l-10">
            <p class="tx-medium mg-b-0">{{ $user->name }}</p>
          </div>
          <div class="mg-l-auto text-right">
            <p class="tx-medium mg-b-0">{{ $user->sub_division_name }}</p>
          </div>
          <div class="mg-l-auto text-right">
            <p class="tx-medium mg-b-0">{{ date_format(date_create($user->created_at),"d M Y") }}</p>
          </div>  
        </li>
        @endforeach
      </ul>
    </div><!-- card -->
  </div>

</div> <!--row-->
@endsection
