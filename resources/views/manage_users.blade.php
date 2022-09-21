@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Users</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Users</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createUserBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create User</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" id="userBulkInsertBtn"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

<table id="users_table" class="table table-sm caption-top table-striped">
  <thead>
    <tr>
      <th class="wd-10p">Name</th>
      <th class="wd-10p">Email</th>
      <th class="wd-10p">Division</th>
      <th class="wd-10p">Sub Division</th>
      <th class="wd-5p">Date Added</th>
      <th class="wd-5p">Role</th>
      <th class="wd-5p">Status</th>
      <th class="wd-10p">Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the user details to edit-->
<div class="modal fade" id="users_modal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="usersModalLabel">User Details</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <div id="usersFormValErr" class="alert alert-danger d-none">
        </div>

      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif
        <form id="usersForm" method="POST"  enctype="multipart/form-data">
          @csrf
          <div class="form-group col">
            <label for="user_name" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('User Name') }} <span class="text-danger">*</span></label>
            <label for="user_email" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right float-right">{{ __('User Email') }} <span class="text-danger">*</span></label>

            <div class="row">
              <div class="col-6">
                  <input type="text" class="form-control" required placeholder="Naame" value="{{ old('user_name') }}" id="user_name" name="user_name">
                  <input id="userId" type="hidden" name="userId">
              </div>

              <div class="col-6">
                <input type="email" class="form-control" required placeholder="Email" value="{{ old('user_email') }}" id="user_email" name="user_email">
              </div>
            </div><!-- row -->
          </div>

          <div class="form-group col">
            <label for="user_division_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the division') }} <span class="text-danger">*</span></label>

            <label for="user_sub_division_dropdown" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right float-right">{{ __('Select the sub division') }} <span class="text-danger">*</span></label>
            <div class="row">
              <div class="col-6">
                <select id="user_division_dropdown" name="user_division_dropdown" class="custom-select" onchange="getSelectedDivision(event)"style="width: 100%">
                </select>
              </div>

              <div class="col-6">
                <select id="user_sub_division_dropdown" name="user_sub_division_dropdown" class="custom-select" style="width: 100%">
                </select>
              </div>

            </div><!-- row -->
          </div>

          <div class="form-group col">
            <label for="user_status" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('User Status') }} <span class="text-danger">*</span></label>
            <label for="user_role" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right float-right">{{ __('User Role') }} <span class="text-danger">*</span></label>

            <div class="row">
              <div class="col-6">
                <select id="user_status" class="custom-select">
                  <option class="tx-spacing-1" selected="selected" value="1">Active</option>
                  <option class="tx-spacing-1" value="0">Deactivated</option>
                  </select>
              </div>

              <div class="col-6">
                <select id="user_role" class="custom-select">
                  <option class="tx-spacing-1" selected="selected" value="1">Administrator</option>
                  <option class="tx-spacing-1" value="0">Default</option>
                  </select>
              </div>

            </div><!-- row -->
          </div>

          <div id="user_password_section" class="form-group col">
          <label for="user_password" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('User Password') }} <span class="text-danger">*</span></label>

          <div class="input-group mg-b-10">
              <div class="wd-md-50p">
              <input type="password" class="form-control" required placeholder="User's password" value="{{ old('user_password') }}" id="user_password" name="user_password">
              </div>
            </div>
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
      <button type="button" id="saveUserBtn" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- modal to handle bulk user insert-->
<div class="modal fade" id="user_import_modal" tabindex="-1" role="dialog" aria-labelledby="userImportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="userImportModalLabel">User Import File</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <div id="userImportFormValErr" class="alert alert-danger d-none">
        </div>

      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif
        <form id="userImportForm" method="POST"  enctype="multipart/form-data">
          @csrf

          <div class="form-group col">
            <label for="users_file" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the csv file with the users to upload') }} <span class="text-danger">*</span></label>

            <div class="row">
              <div class="col-6">
                <input id="users_file" type="file" class="form-control" name="users_file">
              </div>

              <div class="col-6">
                <a href="#" id="userImpTempBtn"><i data-feather="download" ></i> Download Template</a>
              </div>

            </div><!-- row -->
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
      <button type="button" id="importUsersBtn" class="btn btn-success"> <i data-feather="upload"></i> {{ __('Import Users') }}</button>
      </div>
    </div>
  </div>
</div>
    
  <!-- modal to confirm with user if they want to delete the user -->
  <div class="modal fade" id="delete_user_modal" tabindex="-1" role="dialog" aria-labelledby="confirmUserDelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="confirmUserDelLabel">Confirm Delete</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <p id="deleteUserConfirmText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="deleteUserBtn"class="btn btn-danger"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection
