@extends('layouts.dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mg-b-10">
        <li class="breadcrumb-item active" aria-current="page">Dashboard Home</li>
      </ol>
    </nav>
    <h4 class="mg-b-0 tx-spacing--1" id="page-title">Votta Statistics</h4>
  </div>
  <div class="d-none d-md-block">
    <button class="btn btn-sm pd-x-15 btn-white btn-uppercase"><i data-feather="mail" class="wd-10 mg-r-5"></i> Email</button>
    <button class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-l-5"><i data-feather="printer" class="wd-10 mg-r-5"></i> Print</button>
    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="file" class="wd-10 mg-r-5"></i> Generate Report</button>
  </div>
</div>

<div class="row row-xs">

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Total Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">0.81%</h3>
        <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span></p>
      </div>
      <div class="chart-three">
          <div id="flotChart3" class="flot-chart ht-30"></div>
        </div><!-- chart-three -->
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Ongoing Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">0.81%</h3>
        <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span></p>
      </div>
      <div class="chart-three">
          <div id="flotChart3" class="flot-chart ht-30"></div>
        </div><!-- chart-three -->
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Completed Elections</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">0.81%</h3>
        <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span></p>
      </div>
      <div class="chart-three">
          <div id="flotChart3" class="flot-chart ht-30"></div>
        </div><!-- chart-three -->
    </div>
  </div><!-- col -->

  <div class="col-sm-6 col-lg-3">
    <div class="card card-body">
      <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Votes Cast</h6>
      <div class="d-flex d-lg-block d-xl-flex align-items-end">
        <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">0.81%</h3>
        <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span></p>
      </div>
      <div class="chart-three">
          <div id="flotChart3" class="flot-chart ht-30"></div>
        </div><!-- chart-three -->
    </div>
  </div><!-- col -->

</div> <!--row-->
@endsection
