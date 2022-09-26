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
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Manage Sub Divisions</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-warning btn-uppercase" id="createSubDivisionBtn"><i data-feather="plus-circle" class="wd-10 mg-r-5"></i> Create Sub Division</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" id="subDivBulkInsertBtn"><i data-feather="file-plus" class="wd-10 mg-r-5"></i> Bulk Insert</button>
  </div>
</div>

<div class="container mt-5">

<table id="sub_division_table" class="table table-sm caption-top table-striped">
  <thead class="thead-dark">
    <tr>
      <th class="wd-10p">Sub Division Name</th>
      <th class="wd-10p">Division</th>
      <th class="wd-10p">Created On</th>
      <th class="wd-10p">Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

<!-- <hr class="mg-t-50 mg-b-40"> -->

<!-- modal to create or show the sub divisions details to edit-->
<div class="modal fade" id="sub_division_modal" tabindex="-1" role="dialog" aria-labelledby="subDivModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="subDivModalLabel">Sub Division Details</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </button>
          </div>
          <div class="modal-body">
            <div id="subDivFormValErr" class="alert alert-danger d-none">
            </div>

          @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
          @endif
            <form id="subDivisionForm" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="form-group col">
              <label for="subDivName" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Sub Divsion') }} <span class="text-danger">*</span></label>

              <div class="input-group mg-b-10">
                  <div class="input-group-prepend">
                      <select id="divisions_dropdown" name="divisions_dropdown" class="custom-select">
                      </select>
                  </div>
                  <div class="wd-md-50p">
                      <input type="text" class="form-control" required placeholder="Sub Divsion Name" value="{{ old('subDivName') }}" id="subDivName" name="subDivName">
                      <input type="hidden" id="subDivId" name="subDivId">
                  </div>
                </div>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveSubDivBtn" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal to handle bulk sub division insert-->
<div class="modal fade" id="sub_div_import_modal" tabindex="-1" role="dialog" aria-labelledby="subDivImportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content tx-14">
      <div class="modal-header">
        <h6 class="modal-title" id="subDivImportModalLabel">Siub Divisions Import File</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <div id="subDivImportFormValErr" class="alert alert-danger d-none">
        </div>

      @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
      @endif
        <form id="subDivImportForm" method="POST"  enctype="multipart/form-data">
          @csrf

          <div class="form-group col">
            <label for="sub_divs_file" class="mg-b-0 col-form-label tx-spacing-1 fw-bold text-md-right">{{ __('Select the csv file with the sub divisions to upload') }} <span class="text-danger">*</span></label>

            <div class="row">
              <div class="col-6">
                <input id="sub_divs_file" type="file" class="form-control" name="sub_divs_file">
              </div>

              <div class="col-6">
                <a href="#" id="subDivDwnTempBtn"><i data-feather="download" ></i> Download Template</a>
              </div>

            </div><!-- row -->
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
      <button type="button" id="importSubDivBtn" class="btn btn-success"> <i data-feather="upload"></i> {{ __('Import Sub Divisions') }}</button>
      </div>
    </div>
  </div>
</div>
    
  <!-- modal to confirm with user if they want to delete the sub division -->
  <div class="modal fade" id="delete_sub_div_modal" tabindex="-1" role="dialog" aria-labelledby="confirmSubDivDelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="confirmSubDivDelLabel">Confirm Delete</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </button>
        </div>
        <div class="modal-body">
          <p id="deleteSubDivConfirmText"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
          <button type="button" id="deleteSubDivBtn"class="btn btn-danger"> <i data-feather="trash" class="wd-10 mg-r-5"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection
