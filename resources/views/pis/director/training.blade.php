@extends('template.master')

@section('CSS')
<!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.css') }}">
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> <b>TRAINING LIST</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                      <div class="col-9">
                       <table id="tbl" class="table table-bordered table-striped" style="font-size: 12px">
                        <thead>
                        <tr>
                          <th style="width: 2%">#</th>
                          <th>Staff</th>
                          <th>Title</th>
                          <th>Conducted By</th>
                          <th>Type</th>
                          <th>Amount</th>
                          <th>Hours</th>
                          <th>Areas of Discipline</th>
                          <th>LD</th>
                          <th>Training Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data['training_list'] AS $lists)
                            @if(getStaffInfo($lists->user_id) !== null)
                              <tr>
                                <td></td>
                                <td>{{ getStaffInfo($lists->user_id) }}</td>
                                <td>{{ $lists->training_title }}</td>
                                <td>{{ $lists->training_conducted_by }}</td>
                                <td>{{ $lists->training_type }}</td>
                                <td>{{ $lists->training_amount }}</td>
                                <td>{{ $lists->training_hours }}</td>
                                <td>{{ $lists->areas_of_discipline }}</td>
                                <td>{{ $lists->training_ld }}</td>
                                <td>{{ $lists->training_inclusive_dates }}</td>
                              </tr>
                              @endif
                            @endforeach
                        </tbody>
                      </table>
                      </div>

                      <div class="col-3">
                        <div class="card">
                          <div class="card-body">
                            <strong>List of staff total training</strong>
                            <table class="table table-bordered">
                              @foreach(getStaffDivision() AS $lists)
                                <tr>
                                  <td>{{ $lists->lname . "," .$lists->fname . " " . $lists->mname}}</td>
                                  <td style="width: 10%">
                                    {{ getTraining($lists->id) }}
                                  </td>
                                </tr>
                              @endforeach
                            </table>
                          </div>
                        </div>
                      </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
 var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();
</script>
@endsection