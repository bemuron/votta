@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item"><a href="{{ route('statistics') }}">Dashboard Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manage Sub Divisions</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Divisions</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createDivisionBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Sub Division</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" id="divBulkInsertBtn"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

<table id="division_table" class="table table-sm caption-top table-striped">
  <thead>
    <tr>
      <th class="wd-10p">Division Name</th>
      <th class="wd-10p">Created On</th>
      <th class="wd-10p">Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the divisions details to edit-->
<div class="modal fade" id="division_modal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="divModalLabel">Division Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
            <div id="divFormValErr" class="alert alert-danger d-none">
            </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="divisionForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
              <label for="divName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Divsion') }} <span class="text-danger">*</span></label>

              <div class="input-group mg-b-10">
                  <div class="wd-md-50p">
                      <input type="text" class="form-control" required placeholder="Divsion Name" value="{{ old('divName') }}" id="divName" name="divName">
                      <input type="hidden" id="divId" name="divId">
                  </div>
                </div>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveDivBtn" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    
  <!-- modal to confirm with user if they want to delete the sub division -->
  <div class="modal fade" id="delete_div_modal" tabindex="-1" role="dialog" aria-labelledby="confirmDivDelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="confirmDivDelLabel">Confirm Delete</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <p id="deleteDivConfirmText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="deleteDivBtn"class="btn btn-danger"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection
