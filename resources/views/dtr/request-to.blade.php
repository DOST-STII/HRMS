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


  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/bootstrap-datepicker3.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/bootstrap-theme.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/font-awesome.min.css') }}">
@endsection

@section('content')

<div class="row">
  <div class="col-lg-6 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>REQUEST FOR TRAVEL ORDER</b></h3>
                </form>
                </h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" onclick="showRequest('Apply for Leave')" ><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <div class="card-body">
                <form method="POST" id="frm_request" enctype="multipart/form-data" action="{{ url('dtr/send-to-request') }}">
                  {{ csrf_field() }}
                  <table class="table table-sm table-bordered" id="tbl">
                    <thead>
                        <th style="width: 20%">Date</th>
                        <th style="width: 20%">Duration</th>
                        <th>Remarks</th>
                        <th style="width: 5%"></th>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </form>
                <br>
                  <p align="right"><button onclick="submitFrm()" class="btn btn-primary">Submit Request</button></p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
  </div>
</div>

<!-- RESET PASSWORD MODAL-->
      <div class="modal fade" id="modal-request-for">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-request-for-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
             <!--  <form method="POST" id="frm_request" enctype="multipart/form-data">
              {{ csrf_field() }} -->

              <!-- LEAVE TYPE -->
              <div class="div-request" id="div-request-leave">

                  <strong>Duration</strong>
                    <!-- <div class="form-group">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="datepicker" name="datepicker">
                      </div>
                    </div> -->

                    <div class="input-group date form-group" id="datepicker">
                        <input type="text" class="form-control input--style-4" id="Dates" name="Dates" placeholder="Select days" required autocomplete="off" />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>
                    </div>

                    <p class="text-muted">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_wholeday" name="leave_time" value="wholeday" checked>
                        <label for="leave_time_wholeday">
                          Whole day
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_am" name="leave_time" value="AM">
                        <label for="leave_time_am">
                          AM
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_pm" name="leave_time" value="PM">
                        <label for="leave_time_pm">
                          PM
                        </label>
                      </div>
                      </div>

                      <br>
                      <strong>Remarks</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="remarks" id="remarks">
                      </p>
                      
                    </div>
                    </p>
                </div>
              <!-- </form> -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="addLeave()">Add</button>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

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

<script src="{{ asset('datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script>
  $(document).ready(function(){

    //Date range picker
    // $('#datepicker').daterangepicker({
    //     singleDatePicker: true,
    //     datesDisabled: [
    //                     '02/09/2021'
    //                    ],
    // });

    $('#datepicker').datepicker({
        startDate: "2000-01-01",
        singleDatePicker: true,
        format: "yyyy-mm-dd",
        daysOfWeekHighlighted: "0,6",
        // daysOfWeekDisabled: [0,6],
        datesDisabled: [
                        <?php
                          foreach (getDisableDates() as $dates) {
                            echo "'".$dates['date_desc']."',";
                          }
                        ?>
                       ],
        language: 'en'
    }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        // $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
    });

  });

  function showRequest(title)
  {
    $("#modal-request-for-title").text(title);
    $("#modal-request-for").modal("toggle");

    $(".div-request").hide();
    switch(title)
    {
      case "Apply for Leave":
        $("#div-request-leave").show();
      break;
    }
  }

  function addLeave()
  { 
    var remarks = $("#remarks" ).val();

    var dateTime = new Date($("#datepicker").datepicker("getDate"));
    var strDateTime =  dateTime.getFullYear() + "-" + (dateTime.getMonth()+1) + "-" + dateTime.getDate();

    var deduction = $('input[name="leave_time"]:checked').val();
    deduc = 0.5;
    if(deduction == 'wholeday')
    {
      deduc = 1;
    }

    $("#tbl tbody").append("<tr><td><input type='hidden' name='leavedates[]' value='"+strDateTime+"'>"+strDateTime+"</td><td><input type='hidden' name='todeduc[]' value='"+deduc+"'><input type='hidden' name='todeductime[]' value='"+deduction+"'>"+deduction+"</td><td><input type='hidden' name='remarks[]' value='"+remarks+"'>"+remarks+"</td><td align='center'><i class='fas fa-times-circle text-danger' style='cursor:pointer' onclick='removeTR(this)'></i></td></tr>");

    //RESET

    $("#remarks").val("");
    $("input[name=leave_time][value=wholeday]").prop('checked', true);
    $("#remarks").val("");
    $('#datepicker').datepicker('setDate', null);
  }

  function removeTR(obj)
  {
    $(obj).closest("tr").remove();
  }

  function submitFrm()
  {
    $("#frm_request").submit();
  }
</script>
@endsection