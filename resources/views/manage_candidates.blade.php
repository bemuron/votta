@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Candidates</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Candidates</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createCandidateBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Candidate</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" id="candidateBulkInsertBtn"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

  <table id="candidates_table" class="table table-sm caption-top table-striped">
    <thead>
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

<!-- modal to create or show the candidate details to edit-->
<div class="modal fade" id="candidate_modal" tabindex="-1" role="dialog" aria-labelledby="canModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="canModalLabel">Candidate Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
              <div id="canFormValErr" class="alert alert-danger d-none">
              </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="candidateForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
                <label for="candidate_name_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Candidate Name') }} <span class="text-danger">*</span></label>

                <div class="wd-md-50p">
                    <input type="text" class="form-control" required placeholder="Candidate name" value="{{ old('candidateName') }}" id="candidateName" name="candidateName">
                </div>
                <input id="candidateRecordId" type="hidden" name="candidateRecordId">
              </div>

              <div class="form-group col">
                <label for="candidate_election_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the election') }} <span class="text-danger">*</span></label>

                <label for="candidate_position_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right float-right">{{ __('Select the position') }} <span class="text-danger">*</span></label>
                <div class="row">
                  <div class="col-6">
                    <select id="candidate_election_dropdown" name="candidate_election_dropdown" class="custom-select" onchange="getSelectedElection(event)"style="width: 100%">
                    </select>
                  </div>

                  <div class="col-6">
                    <select id="candidate_position_dropdown" name="candidate_position_dropdown" class="custom-select" style="width: 100%">
                    </select>
                  </div>

                </div><!-- row -->
              </div>

              <div class="form-group col mt-3">
                  <label for="candidateImg" class="col-form-label fw-bold text-md-right">
                    {{ __('Candidate Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

                  <div class="wd-md-50p">
                      <input id="candidateImg" type="file" class="form-control" name="candidateImg">
                  </div>

                  <div class="col-md-4 mt-3" id="CandidateImgDisplay"></div>
              </div>
            
              <div class="form-group col mt-3">
                  <label for="candidateDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                      {{ __('Candidate Description') }} <span class="text-danger">*</span>
                  </label>

                  <div class="mg-b-0">
                      <textarea id="candidateDescription" class="form-control" value="{{ old('candidateDescription') }}" name="candidateDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveCandidateBtn" class="btn btn-success"> Save</button>
          </div>
        </div>
      </div>
    </div>

<!-- modal to confirm with user if they want to delete the candidate -->
<div class="modal fade" id="delete_candidate_modal" tabindex="-1" role="dialog" aria-labelledby="confirmCanDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmCanDeleteLabel">Confirm Delete</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <p id="deleteCandidateConfirmText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
        <button type="button" id="deleteCandidateBtn"class="btn btn-danger" data-bs-dismiss="modal"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection
