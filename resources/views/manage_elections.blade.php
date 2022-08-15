@extends('layouts.app')

@section('content')
<div class="breadcrumbs d-flex align-items-center" style="background-image: url('/images/img/manage-elections-header.jpg');">
  <div class="container position-relative d-flex flex-column align-items-center">

    <h2>Manage Elections</h2>
  </div>
</div>

<div class="container mt-5">

  <ul class="nav nav-tabs nav-justified" id="manageElectionsTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="create-election-tab" data-bs-toggle="tab" href="#create-election" role="tab" aria-controls="create-election" aria-selected="true"><i class="bi bi-plus-square"></i>&nbsp; Create</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="edit-election-tab" data-bs-toggle="tab" href="#edit-election" role="tab" aria-controls="edit-election" aria-selected="false"><i class="bi bi-pencil-square"></i>&nbsp; Edit</a>
    </li>
  </ul>
  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
    <div class="tab-pane fade show active" id="create-election" role="tabpanel" aria-labelledby="create-election-tab">
      <h4 class="mt-4">Create a new election</h4>

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

      <form id="addElectionForm" method="POST" action="{{ route('create_election') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group col">
            <label for="electionName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Election Name') }} <span class="text-danger">*</span></label>

              <div class="wd-md-50p">
                <input type="text" class="form-control" required placeholder="Election name" value="{{ old('electionName') }}" id="electionName" name="electionName">
              </div>
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
            <label for="electionStatus" class="col-md-4 col-form-label fw-bold text-md-right">
              {{ __('Election Status') }} <span class="text-danger">*</span></label>

              <div class="wd-md-50p">
                  <select id="electionStatus" name="electionStatus" class="custom-select">
                      <option class="tx-spacing-1 tx-semibold" selected="selected" value="0">Draft</option>
                      <option class="tx-spacing-1" value="1">Live / Ongoing</option>
                      <option class="tx-spacing-1" value="2">Paused</option>
                      <option class="tx-spacing-1" value="3">Completed</option>
                  </select>
              </div>
        </div>

        <div class="form-group col mt-3">
            <label for="electionThumbImg" class="col-md-4 col-form-label fw-bold text-md-right">
              {{ __('Thumbnail Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

            <div class="col-md-6">
                <input id="electionThumbImg" type="file" class="form-control" name="electionThumbImg">
            </div>
        </div>

        <div class="form-group col mt-3">
            <label for="electionBigImg" class="col-md-4 col-form-label fw-bold text-md-right">
              {{ __('Big Image 1100 X 281 for best results') }} <span class="text-danger">*</span>
            </label>

            <div class="col-md-6">
                <input id="electionBigImg" type="file" class="form-control" name="electionBigImg">
            </div>
        </div>
        
        <div class="form-group col mt-3">
            <label for="electionDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                {{ __('Election Description') }} <span class="text-danger">*</span>
            </label>

            <div class="mg-b-0">
                <textarea id="electionDescription" class="form-control" value="{{ old('electionDescription') }}" name="electionDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
            </div>
        </div>
        <div class="mt-3 text-center">
          <button type="submit" id="createElectionBtn" class="btn btn-success fs-5">Create Election</button>
        </div>
    </form>
    </div>
    <div class="tab-pane fade" id="edit-election" role="tabpanel" aria-labelledby="edit-election-tab">
      <table id="elections_table" class="table table-sm caption-top table-striped">
        <thead>
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
  </div>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to show the election details to edit-->
<div class="modal fade" id="edit_election_modal" tabindex="-1" role="dialog" aria-labelledby="editElecModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="editElecModalLabel">Edit Election Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
              <div id="electFormValErr" class="alert alert-danger d-none">
              </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="editElectionForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
                <label for="editElectionName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Election Name') }} <span class="text-danger">*</span></label>

                  <div class="wd-md-50p">
                    <input type="text" class="form-control" required placeholder="Election name" value="{{ old('editElectionName') }}" id="editElectionName" name="editElectionName">
                    <span class="error text-danger d-none"></span>
                  </div>
              </div>

              <div class="form-group col mt-3">
                  <label for="editElectDateFrom" class="mg-b-0 col-form-label fw-bold text-md-right">{{ __('Duration') }}
                  <span class="text-danger">*</span>
                  </label>
                  
                    <div class="row">
                      <div class="col-6">
                        <input type="text" id="editElectDateFrom" name="editElectDateFrom" value="{{ old('editElectDateFrom') }}" class="form-control" required placeholder="Start">
                      </div><!-- col -->
                      
                      <div class="col-6">
                        <input type="text" id="editElectDateTo" name="editElectDateTo" value="{{ old('editElectDateTo') }}" class="form-control" required placeholder="End">
                      </div><!-- col -->
                    </div><!-- row -->
              </div>

              <div class="form-group col mt-3">
                  <label for="editElectionStatus" class="col-md-4 col-form-label fw-bold text-md-right">
                    {{ __('Election Status') }} <span class="text-danger">*</span></label>

                    <div class="wd-md-50p">
                        <select id="editElectionStatus" name="editElectionStatus" class="custom-select">
                            <option class="tx-spacing-1 tx-semibold" selected="selected" value="0">Draft</option>
                            <option class="tx-spacing-1" value="1">Live / Ongoing</option>
                            <option class="tx-spacing-1" value="2">Paused</option>
                            <option class="tx-spacing-1" value="3">Completed</option>
                        </select>
                    </div>
              </div>

              <div class="form-group col mt-3">
                  <label for="editElectionThumbImg" class="col-form-label fw-bold text-md-right">
                    {{ __('Thumbnail Image 288 X 288 for best results') }} <span class="text-danger">*</span></label>

                  <div class="wd-md-50p">
                      <input id="editElectionThumbImg" type="file" class="form-control" name="editElectionThumbImg">
                      <span class="error text-danger d-none"></span>
                  </div>

                  <div class=" mt-3" id="editThumbImg"></div>
              </div>

              <div class="form-group col mt-3">
                  <label for="editElectionBigImg" class="col-form-label fw-bold text-md-right">
                    {{ __('Big Image 1100 X 281 for best results') }} <span class="text-danger">*</span>
                  </label>

                  <div class="wd-md-50p">
                      <input id="editElectionBigImg" type="file" class="form-control" name="editElectionBigImg">
                      <span class="error text-danger d-none"></span>
                  </div>

                  <div class="mt-3" id="editBigImg"></div>
              </div>
            
              <div class="form-group col mt-3">
                  <label for="editElectionDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                      {{ __('Election Description') }} <span class="text-danger">*</span>
                  </label>

                  <div class="mg-b-0">
                      <textarea id="editElectionDescription" class="form-control" value="{{ old('editElectionDescription') }}" name="editElectionDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" id="saveEditElectionBtn" onclick="saveElectionEdit()" class="btn btn-success">Save Changes</button>
          </div>
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
        <button type="button" id="deletElectionBtn"class="btn btn-success" data-bs-dismiss="modal"> Delete</button>
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
