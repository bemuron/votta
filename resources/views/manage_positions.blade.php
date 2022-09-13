@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="#">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Posts</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Posts</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Post</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

  <ul class="nav nav-tabs nav-justified" id="managePostsTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="create-posts-tab" data-bs-toggle="tab" href="#create-posts" role="tab" aria-controls="create-posts" aria-selected="true"><i class="bi bi-plus-square"></i>&nbsp;  Create</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="edit-posts-tab" data-bs-toggle="tab" href="#edit-posts" role="tab" aria-controls="edit-posts" aria-selected="false"> <i class="bi bi-pencil-square"></i>&nbsp;  Edit</a>
    </li>
  </ul>
  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
    <div class="tab-pane fade show active" id="create-posts" role="tabpanel" aria-labelledby="create-election-tab">
      <h4 class="mt-4">Create a new position</h4>

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

      <form id="addPositionForm" method="POST" action="{{ route('create_position') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group col">
            <label for="postName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the election and enter the Post Name') }} <span class="text-danger">*</span></label>

              <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="elections_dropdown" name="elections_dropdown" class="custom-select">
                      </select>
                  </div>
                  <div class="wd-md-50p">
                      <input type="text" class="form-control" required placeholder="Position Name" value="{{ old('postName') }}" id="postName" name="postName">
                  </div>
              </div>
        </div>

        <div class="form-group col mt-3">
            <label for="postDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                {{ __('Post Description') }}
            </label>

            <div class="mg-b-0">
                <textarea id="postDescription" class="form-control" value="{{ old('postDescription') }}" name="postDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
            </div>
        </div>
        
        <div class="mt-3 text-center">
          <button type="submit" id="createPositionBtn" class="btn btn-success fs-5">Create Post</button>
        </div>
    </form>
    </div>
    <div class="tab-pane fade" id="edit-posts" role="tabpanel" aria-labelledby="edit-posts-tab">
      <table id="posts_table" class="table table-sm caption-top table-striped">
        <thead>
          <tr>
            <th class="wd-10p">Post Name</th>
            <th class="wd-10p">Election</th>
            <th class="wd-10p">Post Description</th>
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

<!-- modal to show the posts details to edit-->
<div class="modal fade" id="edit_posts_modal" tabindex="-1" role="dialog" aria-labelledby="editPostsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="editPostsModalLabel">Edit Post Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
            <div id="postsFormValErr" class="alert alert-danger d-none">
            </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="editPostsForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
              <label for="editPostName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Post Name') }} <span class="text-danger">*</span></label>

              <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="edit_elections_dropdown" name="edit_elections_dropdown" class="custom-select">
                      </select>
                  </div>
                  <div class="wd-md-50p">
                      <input type="text" class="form-control" required placeholder="Position Name" value="{{ old('editPostName') }}" id="editPostName" name="editPostName">
                  </div>
                </div>
              </div>

              <div class="form-group col mt-3">
                  <label for="editPostDescription" class="mg-b-0 col-form-label fw-bold text-md-right">
                      {{ __('Post Description') }}
                  </label>

                  <div class="mg-b-0">
                      <textarea id="editPostDescription" class="form-control" value="{{ old('editPostDescription') }}" name="editPostDescription" rows="5" cols="10" required placeholder="Brief description"></textarea>
                  </div>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" id="saveEditPostBtn" onclick="savePostEdit()" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal to confirm with user if they want to vote -->
<div class="modal fade" id="confirmVoteModal" tabindex="-1" role="dialog" aria-labelledby="confirmVoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmVoteLabel">Confirmation</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <p id="confirmText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
        <button type="button" id="voteBtn"class="btn btn-success" data-bs-dismiss="modal" onclick="voteCandidate( )">Vote</button>
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
