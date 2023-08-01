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
  <div class="col-lg-4 col-md-4 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>Reverse DTR</b></h3>
                <div class="card-tools">
                
                </div>
              </div>
              <div class="card-body">
                                    <label for="dtr_options">Employee</label>
                                      <select class="form-control" name="emp_list_11" id="emp_list_11">
                                          <?php $empcode = ""; $processcode = ""; ?>
                                          @foreach(getAllUser() AS $users)
                                            <option value="{{ $users->id }}">{{ $users->lname.", ".$users->fname." ".$users->mname }}</option>
                                            <?php 

                                              if($empcode == "")
                                              {
                                                $empcode = $users->username;
                                                $processcode = $users->process_code;
                                              }
                                            
                                            ?>
                                          @endforeach
                                       </select>
                                       <br>
                                       <p align="right"><button class="btn btn-primary" id="process_code_btn" onclick="reverseDTR()"> REVERSE DTR {{ getPrevDTR('date',$empcode) }}</button></p>

                                       <form method="POST" id="frm_reverse" enctype="multipart/form-data" role="form" action="{{ url('reverse-dtr') }}">
                                        {{ csrf_field() }}
                                              <input type="hidden" name="process_code" id="process_code" value="{{ getPrevDTR('code',$empcode) }}">
                                       </form>

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
$("#emp_list_11").change(function(){

$.getJSON( "{{ url('dtr/process-json') }}/"+this.value, function( datajson ) {
          }).done(function(datajson) {
            console.log(datajson[0]['date']);
            $("#process_code").val(datajson[0]['process_code']);
            $("#process_code_btn").html("Reverse DTR " + datajson[0]['date']);  
          });
});

function reverseDTR()
  {
    $("#overlay").show();
    $("#frm_reverse").submit();
  }
</script>
@endsection