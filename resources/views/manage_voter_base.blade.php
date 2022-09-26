@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Voter Base</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Voter Base</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createVotersBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Voter Base</button>
  </div>
</div>

<div class="container mt-5">

  <table id="voters_table" class="table table-sm caption-top table-striped">
    <thead class="thead-dark">
      <tr>
        <th class="wd-10p">Name</th>
        <th class="wd-10p">Election</th>
        <th class="wd-10p">Position</th>
        <th class="wd-5p">Description</th>
        <th class="wd-5p">Image</th>
        <th class="wd-5p">Created On</th>
        <th class="wd-10p">Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the voters for an election-->
<div class="modal fade" id="voters_modal" tabindex="-1" role="dialog" aria-labelledby="votersModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="votersModalLabel">Specify the voters for the elections</h6>
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
            <form id="votersForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
                <label for="voters_election_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select Election') }} <span class="text-danger">*</span></label>

                <div class="wd-md-50p">
                  <select id="voters_election_dropdown" name="voters_election_dropdown" class="custom-select">
                  </select>
                </div>
                <input id="votersId" type="hidden" name="votersId">
              </div>

              <div class="form-group col">
                <label for="voters_division_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the division') }} <span class="text-danger">*</span></label>

                <label for="voters_sub_division_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right float-right">{{ __('Select the sub division') }} <span class="text-danger">*</span></label>
                <div class="row">
                  <div class="col-6">
                    <select id="voters_division_dropdown" name="voters_division_dropdown" class="custom-select" onchange="getVoterBaseSelectedDivision(event)"style="width: 100%">
                    </select>
                  </div>

                  <div class="col-6">
                    <select id="voters_sub_division_dropdown" name="voters_sub_division_dropdown" class="custom-select" style="width: 100%">
                    </select>
                  </div>

                </div><!-- row -->
              </div>

              <div class="form-group col">
              <div class="row">
                  <div class="col-3">

                  </div>
                  <div class="col-6">
                    <button type="button" id="addVotersBtn" class="btn btn-dark btn-block"> <i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Add Votters</button>
                    <p  class="tx-10 tx-spacing-1 tx-purple tx-semibold">* Click save after you finish adding votters</p>
                  </div>
                  <div class="col-3">

                  </div>
              </div>
              </div>

              <div class="form-group col">
                <div class="col-6 mg-t-20 mg-sm-t-0">
                <table id="election_voters_table" class="table table-sm caption-top table-bordered table-striped">
                  <caption class="tx-17 tx-rubik">This election's voter base.</caption>
                  <thead class="thead-primary">
                    <tr>
                      <th class="wd-10p">Division</th>
                      <th class="wd-10p">Sub Division</th>
                      <th class="wd-5p">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                </div>
              </div>
            
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveCandidateBtn" class="btn btn-success"> <i data-feather="save" class="wd-10 mg-r-5"></i> Save</button>
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
