@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Elections</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Elections</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createElectionBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Election</button>
    <!-- <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button> -->
  </div>
</div>

<div class="container mt-5">

  <table id="elections_table" class="table table-sm caption-top table-striped">
    <thead class="thead-dark">
      <tr>
        <th class="wd-10p">Name</th>
        <th class="wd-10p">Status</th>
        <th class="wd-5p">Start Date</th>
        <th class="wd-10p">End Date</th>
        <th class="wd-5p">Description</th>
        <th class="wd-5p">Image</th>
        <th class="wd-5p">Big Image</th>
        <th class="wd-5p">Created On</th>
        <th class="wd-5p">Created By</th>
        <th class="wd-10p">Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- elections modal to handle both creation and editing election details-->
<div class="modal fade" id="elections_modal" tabindex="-1" role="dialog" aria-labelledby="electionsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="electionsModalLabel">Election Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>

          <form id="addElectionForm" method="POST"  enctype="multipart/form-data">
          <div class="modal-body">
              <div id="electFormValErr" class="alert alert-danger d-none">
              </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            
              @csrf
              <div class="form-group col">
                <label for="electionName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Election Name') }} <span class="text-danger">*</span></label>
                <label for="electionStatus" class="col-md-4 col-form-label fw-bold text-md-right float-right">
                    {{ __('Election Status') }} <span class="text-danger">*</span></label>
                <div class="row">
                  <div class="col-6">
                    <input type="text" class="form-control" required placeholder="Election name" value="{{ old('electionName') }}" id="electionName" name="electionName">
                    <input type="hidden" id="electionId" name="electionId">
                    <span class="error text-danger d-none"></span>
                  </div>

                  <div class="col-6">
                    <select id="electionStatus" name="electionStatus" class="custom-select">
                        <option class="tx-spacing-1 tx-semibold" selected="selected" value="0">Draft</option>
                        <option class="tx-spacing-1" value="1">Live / Ongoing</option>
                        <option class="tx-spacing-1" value="2">Paused</option>
                        <option class="tx-spacing-1" value="3">Completed</option>
                    </select>
                  </div>

                </div><!-- row -->
              </div>
              

              <div class="form-group col mt-3">
                  <label for="electDateFrom" class="mg-b-0 col-form-label fw-bold text-md-right">{{ __('Duration') }}
                  <span class="text-danger">*</span>
                  </label>
                  
                    <div class="row">
                      <div class="col-6">
                        <input type="text" id="electDateFrom" name="electDateFrom" value="{{ old('electDateFrom') }}" class="form-control" required placeholder="Start">
                      </div><!-- col -->
                      
                      <div class="col-6">
                        <input type="text" id="electDateTo" name="electDateTo" value="{{ old('electDateTo') }}" class="form-control" required placeholder="End">
                      </div><!-- col -->
                    </div><!-- row -->
              </div>

              <div class="form-group col mt-3">
                  <label for="electionThumbImg" class="col-form-label fw-bold text-md-right">
                    {{ __('Thumbnail Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

                    <label for="electionBigImg" class="col-form-label fw-bold text-md-right float-right">
                    {{ __('Big Image 1100 X 281 for best results') }} <span class="text-danger">*</span>
                  </label>

                  <div class="row">
                    <div class="col-6">
                        <input id="electionThumbImg" type="file" class="form-control" name="electionThumbImg">
                        <span class="error text-danger d-none"></span>
                        <div class=" mt-3" id="electThumbImg"></div>
                    </div>
                    
                    <div class="col-6">
                        <input id="electionBigImg" type="file" class="form-control" name="electionBigImg">
                        <span class="error text-danger d-none"></span>
                        <div class="mt-3" id="electBigImg"></div>
                    </div>

                  </div><!-- row -->
              </div>
            
              <div class="form-group col mt-3">
                  <label for="electionDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                      {{ __('Election Description') }} <span class="text-danger">*</span>
                  </label>

                  <div class="mg-b-0">
                      <textarea id="electionDescription" class="form-control" value="{{ old('electionDescription') }}" name="electionDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
                  </div>
              </div>
            
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="saveElectionBtn" class="btn btn-success"><i data-feather="save" class="mg-r-5"></i> Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <!-- modal to confirm with user if they want to delete the election -->
  <div class="modal fade" id="delete_election_modal" tabindex="-1" role="dialog" aria-labelledby="confirmVoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="confirmVoteLabel">Confirm Delete</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <p id="deleteElectionConfirmText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="deletElectionBtn"class="btn btn-danger"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection
