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
<!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form">   -->

<form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('dtr/final-process') }}">  
{{ csrf_field() }}
<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('dtr/final-process') }}">
<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('/') }}">
<input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
<input type="hidden" name="yr" id="yr" value="{{ $data['year'] }}">
<div class="row">
  <div class="col-lg-6 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>PROCESS DTR</b></h3>
                <div class="card-tools">

                </div>
              </div>
              <div class="card-body">
                  <span>Month : <b>{{ date('F',mktime(0, 0, 0, $data['mon'], 10)) }}</b></span><br>
                  <span>Year : <b>{{ $data['year'] }}</b></span>
                  <hr>
                  <i>Pending request for Leave/TO/OT...</i><br>

                  <i><small>**Leave**</small></i><br>
                  <small>
                    <?php 
                      //DISABLE BUTTON IF HAS ANG ERROR
                      $err = 0;

                      $msg = explode("~", checkPendingRequest('Leave',$data['mon'],$data['year'],Auth::user()->division)); 
                      
                      $err_txt = "";
                      if($msg[1] == 'false')
                        {
                          $err++;
                          $err_txt = "class='text-danger'";
                        }
                      echo "<span $err_txt>".$msg[0]."</span>"
                    ?>
                  </small>
                  <br>
                  <i><small>**TO**</small></i><br>
                  <small>
                    <?php 

                      $msg = explode("~", checkPendingRequest('T.O',$data['mon'],$data['year'],Auth::user()->division)); 
                      
                      $err_txt = "";
                      if($msg[1] == 'false')
                        {
                          $err++;
                          $err_txt = "class='text-danger'";
                        }
                      echo "<span $err_txt>".$msg[0]."</span>"
                    ?>
                  </small>
                  <br>
                  <i><small>**OT**</small></i><br>
                  <small>
                    <?php

                      $msg = explode("~", checkPendingRequest('O.T',$data['mon'],$data['year'],Auth::user()->division)); 
                      
                      $err_txt = "";
                      if($msg[1] == 'false')
                        {
                          $err++;
                          $err_txt = "class='text-danger'";
                        }
                      echo "<span $err_txt>".$msg[0]."</span>"
                    ?>
                  </small>

                  <hr>
                  <i>Staff DTR...</i><br>
                  <?php checkDTRStaff($data['mon'],$data['year'],Auth::user()->division) ?>

              </div>
            </form>
              <div class="card-footer text-muted text-right">
                  @if($err > 0)
                  <button class="btn btn-primary" disabled>Process DTR</button>
                  @else
                  <button class="btn btn-primary" onclick="submitFrm()">Process DTR</button>
                  @endif
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


<script type="text/javascript">
  function submitFrm()
  {
    $("#frm2").sumbit();
  }
</script>
@endsection