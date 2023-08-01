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
        @include('pis/staff/info-boxes')
<div class="row">
  <div class="col-md-6">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>DTR</b></h3>
                <h3 class="card-title float-right"><a class="btn btn-primary btn-sm" href="#" target="_blank" onclick="showDTR()"><i class="fas fa-print"></i></a></h3>
                <h3 class="card-title float-right" style="padding-right: 10px">
                  <input type="hidden" id="dtr-year" value="{{ date('Y') }}">
                  <input type="hidden" id="dtr-mon" value="{{ date('m') }}">
                  
                </h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <h5><b><center>{{ date('F Y') }}</b></center></h5>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th>
                  </thead>
                  <tbody>
                    <?php
                      $total = Carbon\Carbon::now()->daysInMonth;
                      $prevweek = 1;

                      $week_num = 2;

                      $mon = date('m');
                      $yr = date('Y');

                      echo "<tr><td colspan='5' align='center'>  <b>WEEK 1 </b> </td></tr>";
                      for($i = 1;$i <= $total;$i++)
                      {
                        $weeknum = weekOfMonth(date('Y-m-'.$i)) + 1;
                        if($weeknum == $prevweek)
                        {
                          
                        }
                        else
                        {
                          $prevweek = $weeknum;
                          echo "<tr><td colspan='5' align='center'> <b>WEEK $week_num </b> </td></tr>";
                          $week_num++;
                        }

                       $dtr_date = $yr.'-'.$mon.'-'.$i;

                        $dayDesc = weekDesc(date($yr.'-'.$mon.'-'.$i));

                       // echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".getDTR($dtr_date,'am-in',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'am-out',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'pm-in',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'pm-out',$dayDesc,Auth::user()->id)."</td></tr>";

                        echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center'></td><td align='center'></td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
  </div>
  <div class="col-md-6">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('pdf/my-payslip') }}" target="_blank">  
              {{ csrf_field() }}
              <div class="card-header">
                <h3 class="card-title"><b>PAYSLIP</b></h3>
                <h3 class="card-title float-right"><button class="btn btn-primary btn-sm" ><i class="fas fa-print"></i></button></h3>
                <input type="hidden" id="payslip-year" name="payslip_year" value="{{ date('Y') }}">
                <input type="hidden" id="payslip-mon" name="payslip_mon" value="{{ date('m') }}">
                </form>
                </h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <table width="100%">
                  <tr>
                    <td style="width:33%"><b>GROSS PAY</b></td>
                    <td style="width:33%"><b>DEDUCTIONS</b></td>
                    <td></td>
                  </tr>
                  <tbody>
                    <tr>
                      <td valign="top">
                        SALARY - <br>
                        PERA -
                      </td>
                      <td valign="top">
                        BIR - <br/>
                        SIC - <br/>
                        MED - <br/>
                        HDMF - <br/>
                      </td>
                      <td valign="top"></td>
                    </tr>
                    <tr>
                      <td><b>TOTAL</b> - </td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><b>NET SALARY</b> - </td>
                      <td><b>PER WEEK - </b></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card card-default">
              <form method="POST" id="frm3" enctype="multipart/form-data" action="{{ url('pdf/print-leave') }}" target="_blank">  
              {{ csrf_field() }}
              <div class="card-header">
                <h3 class="card-title"><b>LEAVE BALANCES</b></h3>
                <h3 class="card-title float-right"><button class="btn btn-primary btn-sm" ><i class="fas fa-print"></i></button></h3>
                <h3 class="card-title float-right" style="padding-right: 10px">
                <input type="hidden" id="payslip-year" name="payslip_year" value="{{ date('Y') }}">
                <input type="hidden" id="payslip-mon" name="payslip_mon" value="{{ date('m') }}">
                </form>
                </h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <table width="100%">
                  <tr>
                    <td style="width:30%"><b>LEAVE BALANCES</b></td>
                    <td style="width:9%" align="center"><b></b></td>
                    <td style="width:30%" align="center"><b>PENDING</b></td>
                    <td style="width:30%" align="center"><b>PROJECTED BALANCE</b></td>
                  </tr>
                  <tbody>
                    @foreach(showLeaves() AS $leaves)
                      <tr>
                        <td>{{ $leaves->leave_desc }}</td>
                        <td align="center">{{ getLeaves(Auth::user()->id,$leaves->id) }}</td>
                        <td align="center">{{ getPending($leaves->id) }}</td>
                        <td align="center">{{ getProjectedLeave(getLeaves(Auth::user()->id,$leaves->id),getPending($leaves->id)) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <h5><small>LEAVE RECORD</small></h5>
                <br>
                <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('pdf/print-leave-record') }}" target="_blank">

                  <div class="float-left">
                    <small><b>DURATION</b></small>
                  </div>

                  <div class="float-right" style="margin-left: 2%">
                    <button class="btn btn-primary btn-sm" ><i class="fas fa-print"></i></button>
                  </div>

                  <div class="float-right">
                      <select class="form-control-sm" id="payslip-year" name="payslip_year">
                      <?php
                        $total = date('Y') - 5;
                        for ($i = date('Y'); $i >= $total ; $i--) { 
                          # code...
                          echo "<option value='$i'>$i</option>";
                        }
                        
                      ?>
                    </select>
                  </div>
                  

                  <div class="float-right" style="margin-right: 1%">
                      <select class="form-control-sm" id="payslip-mon" name="payslip_mon">
                    <?php
                      $month = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                      foreach ($month as $months) {
                        # code...
                        $style = "";
                        if($months == date('F'))
                        {
                          $style = "font-weight:bold";
                        }
                        echo "<option value='$months' style='$style'>$months</option>";
                      }
                    ?>
                  </select>
                  </div>

                  <div class="float-right">
                      <select class="form-control-sm" id="payslip-year" name="payslip_year">
                      <?php
                        $total = date('Y') - 5;
                        for ($i = date('Y'); $i >= $total ; $i--) { 
                          # code...
                          echo "<option value='$i'>$i</option>";
                        }
                        
                      ?>
                    </select>
                    &nbsp&nbsp-&nbsp&nbsp
                  </div>
                  <div class="float-right" style="margin-right: 1%">
                      <select class="form-control-sm" id="payslip-mon" name="payslip_mon">
                    <?php
                      $month = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                      foreach ($month as $months) {
                        # code...
                        $style = "";
                        if($months == date('F'))
                        {
                          $style = "font-weight:bold";
                        }
                        echo "<option value='$months' style='$style'>$months</option>";
                      }
                    ?>
                  </select>
                  </div>
                </form>
              </div>
            </div>

            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>REQUEST FOR APPROVAL</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <table width="100%">
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
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
              <form method="POST" id="frm_request" enctype="multipart/form-data">
              {{ csrf_field() }}

              <!-- LEAVE TYPE -->
              <div class="div-request" id="div-request-leave">
                <strong>Leave Type</strong>
                  <br>
                  <p class="text-muted">
                    <select class="form-control" name='leave_id'>
                      @foreach(showLeaves() AS $leaves)
                        <?php
                          if(getLeaves(Auth::user()->id,$leaves->id) > 0)
                          {
                            echo '<option value="'.$leaves->id.'">'.$leaves->leave_desc.'</option>';
                          }
                        ?>
                      @endforeach
                    </select>
                  </p>

                  <strong>Duration</strong>
                    <div class="form-group">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration" name="leave_duration">
                      </div>
                      <!-- /.input group -->
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

                      
                      
                    </div>
                    </p>
                </div>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="modalOnSubmit()">Submit</button>
            
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


<script>
  $(document).ready(function(){
    $("#dtr-mon,#payslip-mon").val("{{ date('F') }}");
    $("#dtr-year,#payslip-year").val({{ date('Y') }});

    //Date range picker
    $('#leave_duration').daterangepicker();

    checkPendingRequest();

  });

  function checkPendingRequest()
    {

    }

  function modalOnSubmit()
  {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes!'
    }).then((result) => {
      if (result.value) {
        $("#frm_request").submit();
      }
    })
  }

  //SUBMIT REQUEST

  //GET DATE
  $('#leave_duration').on('apply.daterangepicker', function(ev, picker) {
    var start = new Date(picker.startDate.format('YYYY-MM-DD')),
    end   = new Date(picker.endDate.format('YYYY-MM-DD')),
    diff  = new Date(end - start),
    days  = diff/1000/60/60/24;
    console.log(days);
    if(days > 0)
    {
      console.log("pass");
      $("#leave_times").hide();
    }
    else
    {
      $("#leave_times").show();
    }
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
        $("#frm_request").attr({"action" : "{{ url('request/leave') }}"});
      break;
    }
  }

  function showDTR()
  {
    var win = window.open('{{ url("pdf/my-dtr") }}/' + $("#dtr-mon").val() + '-' + $("#dtr-year").val(), '_blank');
  }

  function showPayslip()
  {
    var win = window.open('{{ url("pdf/my-payslip") }}/' + $("#dtr-mon").val() + '-' + $("#dtr-year").val(), '_blank');
  }

</script>
@endsection