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
  <div class="col-lg-7 col-md-12 col-sm-12">
    <h5 class="alert alert-warning"><center>Click on the column to edit/add</center></h5>
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  Employee : <select class="form-control-sm" name="userid" id="userid" onchange="showDTR()">
                  @foreach(getAllStaffDivision2() AS $divs)
                    
                      <option value='{{ $divs->id }}'>{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>

                  @endforeach
                  <?php

                      //CHECKED IF PROCESSES NA
                      $proc = App\DTRProcessed::where('userid',$data['userid'])->where('dtr_mon',$data['mon'])->where('dtr_year',$data['yr'])->first();

                      $edit = true;
                      $final = null;
                      if($proc)
                      {
                        $edit = false;
                        $final = " <span class='text-danger'>(Processed)</span>";
                      }


                      if(Auth::user()->division == 'q' && Auth::user()->usertype == 'Marshal')
                      {
                        echo "<option value='141'>Molina, Susan S.</option>";
                      }
                    ?>
                </select>
                </h3>
                <div class="card-tools">
                <div class="float-right" style="margin-right: 1%">
                  <select class="form-control-sm" name="dtr_year" id="dtr_year" onchange="showDTR()">
                  <?php
                    for ($i = (date('Y') + 1); $i >= (date('Y') - 5) ; $i--) { 
                        echo "<option value='$i'>".$i."</option>";
                    }
                  ?>
                  </select>
              </div>

              <div class="float-right" style="margin-right: 1%">
                <select class="form-control-sm" name="dtr_mon" id="dtr_mon" onchange="showDTR()">
                  <option selected value='1'>January</option>
                  <option value='2'>February</option>
                  <option value='3'>March</option>
                  <option value='4'>April</option>
                  <option value='5'>May</option>
                  <option value='6'>June</option>
                  <option value='7'>July</option>
                  <option value='8'>August</option>
                  <option value='9'>September</option>
                  <option value='10'>October</option>
                  <option value='11'>November</option>
                  <option value='12'>December</option>
                </select>
              </div>
              @if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'Director')
              <div class="float-left" style="margin-right: 1%">

                

              </div>
              @else
                  <input type="hidden" id="userid" name="userid" value="{{ Auth::user()->id }}">
              @endif
                <h3 class="card-title float-right" style="padding-right: 10px">
                    
                </div>
              </div>
              <div class="card-body">
                <h5><b><center><?php echo date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'].''.$final; ?></b></center></h5>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th>
                    <th style="width:30%">Remarks</th>
                  </thead>
                  <tbody>
                    <?php
                      //echo formatDTRrow($data['mon'],$data['yr'],$data['emp']);
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <center><button class="btn btn-primary" onclick="printDTR()"><i class="fas fa-print"></i> Print DTR</button></center>
              </div>
            </div>
            <!-- /.card -->
  </div>
</div>

@if(!$proc)
<div class="modal" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit DTR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" action="{{ url('dtr/update') }}">  
          {{ csrf_field() }}

          <!--<input type="hidden" name="userid" id="userid" value="{{ $data['emp']['id'] }}"> -->
          <!--<input type="hidden" name="dtr_url" id="dtr_url" value="{{ url('staff/attendance/'.$data['mon'].'/'.$data['yr'].'/'.$data['emp']['id']) }}"> -->
         
          <input type="hidden" name="yr" id="yr" value="{{ $data['yr'] }}">
          <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
          <input type="hidden" name="day" id="day" value="">
          <input type="hidden" name="dtr_colid" id="dtr_colid">
          <input type="hidden" name="dtr_col" id="dtr_col">
          <input type="hidden" name="dtr_orig" id="dtr_orig">
         
          <span id="desc"><b>Time</b></span>
          <input type="time" class="form-control" name="dtr_val" id="dtr_val">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endif

<form method="POST" id="frm_print" enctype="multipart/form-data" action="{{ url('dtr/pdf') }}" target="_blank">  
  {{ csrf_field() }}
  <input type="hidden" name="userid2" id="userid2">
  <input type="hidden" name="mon2" id="mon2">
  <input type="hidden" name="yr2" id="yr2">
</form>

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
$("#dtr_mon,#mon2").val({{ $data['mon'] }});
$("#userid,#userid2").val({{ $data['userid'] }});
$("#dtr_year,#yr2").val({{ $data['yr'] }});

<?php 
  if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'Administrator')
  {
  ?>
    function showEdit(colid,userid,col,y,m,d)
    {
      $("#dtr_orig,#dtr_val").val('');
      $("#day").val(d);
      $("#dtr_col").val(col);
      $("#dtr_colid").val(colid);

      $.getJSON( "{{ url('json/dtr') }}/"+userid+"/regular/"+colid+"/"+col, function( datajson ) {
                }).done(function(datajson) {
                  $("#dtr_orig,#dtr_val").val(datajson);
              }).fail(function() {
              });
      $("#modalEdit").modal("toggle");
    }
  <?php
  }
?>
  

function showDTR()
{
  $("#overlay").show();
  mon = $("#dtr_mon").val();
  yr = $("#dtr_year").val();
  userid = $("#userid").val();

  window.location.replace("{{ url('staff/attendance') }}/"+mon+"/"+yr+"/"+userid);
}

function printDTR()
{
  $("#frm_print").submit();
}


</script>
@endsection