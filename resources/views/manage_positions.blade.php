@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Posts</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Posts</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createPostBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Post</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" id="postBulkInsertBtn"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

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

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the posts details to edit-->
<div class="modal fade" id="posts_modal" tabindex="-1" role="dialog" aria-labelledby="postsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="postsModalLabel">Post Details</h6>
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
            <form id="postsForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
              <label for="postName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Post Name') }} <span class="text-danger">*</span></label>

              <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="elections_dropdown" name="elections_dropdown" class="custom-select">
                      </select>
                  </div>
                  <div class="wd-md-50p">
                      <input type="text" class="form-control" required placeholder="Position Name" value="{{ old('postName') }}" id="postName" name="postName">
                      <input type="hidden" id="postId" name="postId">
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
              
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="savePostBtn" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    
  <!-- modal to confirm with user if they want to delete the position -->
  <div class="modal fade" id="delete_post_modal" tabindex="-1" role="dialog" aria-labelledby="confirmPostDelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="confirmPostDelLabel">Confirm Delete</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <p id="deletePostConfirmText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="deletePostBtn"class="btn btn-danger"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection
