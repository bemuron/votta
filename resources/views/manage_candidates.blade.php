@extends('layouts.app')

@section('content')
<!-- election image-->
<div class="breadcrumbs d-flex align-items-center" style="background-image: url('/images/img/manage-candidates-header.jpg');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Manage Candidates</h2>
  </div>
</div>

<div class="container mt-5">
  <ul class="nav nav-tabs nav-justified" id="manageCandidatesTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="create-candidate-tab" data-bs-toggle="tab" href="#create-candidate" role="tab" aria-controls="create-candidate" aria-selected="true"> <strong> <i class="bi bi-person-plus"></i> &nbsp; Create</strong></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="edit-candidate-tab" data-bs-toggle="tab" href="#edit-candidate" role="tab" aria-controls="edit-candidate" aria-selected="false"><strong><i class="bi bi-pencil-square"></i>&nbsp;   Edit</strong></a>
    </li>
  </ul>
  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
    <div class="tab-pane fade show active" id="create-candidate" role="tabpanel" aria-labelledby="create-candidate-tab">
      <h4 class="mt-4">Create a new candidate</h4>

      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif

      <form id="addCandidateForm" method="POST" action="{{ route('create_candidate') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group col">
            <label for="candidateName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Candidate Name') }} <span class="text-danger">*</span></label>

            <div class="wd-md-50p">
              <input type="text" class="form-control" required placeholder="Candidate name" value="{{ old('candidateName') }}" id="candidateName" name="candidateName">
            </div>
              <!-- <div class="input-group mg-b-10">
                <div class="input-group-prepend">
                    <select id="candidate_name_dropdown" name="candidate_name_dropdown" class="custom-select select2" style="width: 100%">
                    </select>
                </div>
              </div> -->
        </div>

        <div class="form-group col">
          <label for="candidate_election_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the election') }} <span class="text-danger">*</span></label>

          <div class="input-group mg-b-10">
              <div class="input-group-prepend">
                  <select id="candidate_election_dropdown" name="candidate_election_dropdown" class="custom-select" onchange="getSelectedElection(event)"style="width: 100%">
                  </select>
              </div>
            </div>
        </div>

        <div class="form-group col">
          <label for="candidate_position_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the position') }} <span class="text-danger">*</span></label>

          <div class="input-group mg-b-10">
            <div class="input-group-prepend">
                <select id="candidate_position_dropdown" name="candidate_position_dropdown" class="custom-select" style="width: 100%">
                </select>
            </div>
          </div>
        </div>

        <div class="form-group col mt-3">
            <label for="candidateImg" class="mg-b-0 col-form-label fw-bold text-md-right">
              {{ __('Candidate Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

            <div class="wd-md-50p">
                <input id="candidateImg" type="file" class="form-control" name="candidateImg">
            </div>
        </div>
        
        <div class="form-group col mt-3">
            <label for="candidateDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                {{ __('Candidate Description') }} <span class="text-danger">*</span>
            </label>

            <div class="mg-b-0">
                <textarea id="candidateDescription" class="form-control" value="{{ old('candidateDescription') }}" name="candidateDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
            </div>
        </div>
        <div class="mt-3 text-center">
          <button type="submit" id="createCandidateBtn" class="btn btn-success fs-5">Create Candidate</button>
        </div>
    </form>
    </div>

    <div class="tab-pane fade" id="edit-candidate" role="tabpanel" aria-labelledby="edit-candidate-tab">
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
  </div>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to show the candidate details to edit-->
<div class="modal fade" id="edit_candidate_modal" tabindex="-1" role="dialog" aria-labelledby="editCanModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="editCanModalLabel">Edit Candidate Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
              <div id="editCanFormValErr" class="alert alert-danger d-none">
              </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="editCandidateForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
                <label for="edit_candidate_name_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Candidate Name') }} <span class="text-danger">*</span></label>

                <div class="wd-md-50p">
                    <input type="text" class="form-control" required placeholder="Candidate name" value="{{ old('editCandidateName') }}" id="editCandidateName" name="editCandidateName">
                </div>
                <input id="editCandidateRecordId" type="hidden" name="editCandidateRecordId">
              </div>

              <div class="form-group col">
                <label for="edit_candidate_election_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the election') }} <span class="text-danger">*</span></label>

                <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="edit_candidate_election_dropdown" name="edit_candidate_election_dropdown" class="custom-select" onchange="getSelectedElectionEdit(event)"style="width: 100%">
                      </select>
                  </div>
                </div>
              </div>

              <div class="form-group col">
                <label for="edit_candidate_position_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the position') }} <span class="text-danger">*</span></label>

                <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="edit_candidate_position_dropdown" name="edit_candidate_position_dropdown" class="custom-select" style="width: 100%">
                      </select>
                  </div>
                </div>
              </div>

              <div class="form-group col mt-3">
                  <label for="editCandidateImg" class="col-form-label fw-bold text-md-right">
                    {{ __('Candidate Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

                  <div class="wd-md-50p">
                      <input id="editCandidateImg" type="file" class="form-control" name="editCandidateImg">
                  </div>

                  <div class="col-md-4 mt-3" id="CandidateImgDisplay"></div>
              </div>
            
              <div class="form-group col mt-3">
                  <label for="editCandidateDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                      {{ __('Candidate Description') }} <span class="text-danger">*</span>
                  </label>

                  <div class="mg-b-0">
                      <textarea id="editCandidateDescription" class="form-control" value="{{ old('editCandidateDescription') }}" name="editCandidateDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" id="saveEditCandidateBtn" class="btn btn-success">Save Changes <i class="bi bi-cassette"></i></button>
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
        <button type="button" id="deleteCandidateBtn"class="btn btn-success" data-bs-dismiss="modal"> Delete</button>
      </div>
    </div>
  </div>
</div>

    <!-- modal to inform user they already voted -->
    <div class="modal fade" id="alreadyVotedModal" tabindex="-1" role="dialog" aria-labelledby="alreadyVotedLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="alreadyVotedLabel">You already voted</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
            <p id="alreadyVotedText"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

<!-- Alert message for user to show vote cast successfully-->
<div id="voteSuccessAlert" class="modal alert-success h-auto  fade show alert-dismissible" role="document">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <br>
  <p class="fs-4 text-center"><strong>Success!</strong> Your vote has been recieved.</p>
  <br>
</div>

<!-- Alert message for user to show vote not cast-->
<div id="voteFailedAlert" class="modal alert-danger h-auto fade show alert-dismissible" role="document">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <br>
  <p class="fs-4 text-center"><strong>Danger!</strong> Your vote was not received.</p>
  <br>
</div>
@endsection
