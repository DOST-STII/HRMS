@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<h1>Rewards and Recognition</h1>
<!-- <div class="row">
  <div class="col-3">
    <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Training</span>
                <span class="info-box-number">-</span>
              </div>
            </div>
  </div>
</div> -->
  <br>
  <br>
<div class="row">
  <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-excellence" role="tab" aria-controls="tabs-excellence" aria-selected="true">Excellence Award</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-outstanding" role="tab" aria-controls="tabs-outstanding" aria-selected="true">Outstanding Employee</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-year-of-service" role="tab" aria-controls="tabs-year-of-service" aria-selected="true">Years of Service</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tabs-excellence" role="tab" aria-controls="tabs-attendance" aria-selected="false">Perfect Attendance</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tabs-excellence" role="tab" aria-controls="tabs-flag" aria-selected="false">Flag Ceremony</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="tabs-excellence" role="tabpanel" aria-labelledby="tabs-training-tab">

                  </div>

                  <div class="tab-pane fade" id="tabs-outstanding" role="tabpanel" aria-labelledby="tabs-training-tab">

                  </div>
                  <div class="tab-pane fade" id="tabs-year-of-service" role="tabpanel" aria-labelledby="tabs-hiring-tab">
                    <table class="table" id='tbl_1'>
                      <thead>
                        <th>YEARS OF SERVICE</th>
                        <th>LIST OF STAFF</th>
                      </thead>
                      <tbody>
                        
                          @foreach($data['yrservice'] AS $yrservices)
                          <tr>
                              <td>{{ $yrservices->years_service }}</td>
                              <td>{{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}</td>
                          </tr>
                          @endforeach
                        
                        <!-- <tr>
                          <td><b>15</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 15)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                          <td><b>20</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 20)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                          <td><b>25</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 25)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                          <td><b>30</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 30)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                          <td><b>35</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 35)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                          <td><b>40</b></td>
                          <td>
                            @foreach($data['yrservice'] AS $yrservices)
                              @if($yrservices->years_service == 40)
                                {{ $yrservices->lname . ', ' .$yrservices->fname . ' ' . $yrservices->mname }}<br>
                              @endif
                            @endforeach
                          </td>
                        </tr> -->
                      </tbody>
                    </table>
                  </div>

                  <div class="tab-pane fade" id="tabs-attendance" role="tabpanel" aria-labelledby="tabs-training-tab">

                  </div>

                  <div class="tab-pane fade" id="tabs-flag" role="tabpanel" aria-labelledby="tabs-hrd-tab">
                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
  </div>
</div>
@endsection

@section('JS')

<!-- Sparkline -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- ChartJS -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(function () {
    var t = $("#tbl_1").DataTable();

  //   t.on('order.dt search.dt', function () {
  //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  //         cell.innerHTML = i+1;
  //     });
  // }).draw();

  });
</script>
@endsection