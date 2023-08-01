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
                  <h3 class="card-title"><b>DAILY TIME RECORD (DTR)</b></h3>
                
                    
                  </h3>
                  <div class="card-tools">
                    <!-- <a href="{{ url('update-weekly-schedule/'.date('m').'/'.date('Y') ) }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-alt"></i> Update Weekly Schedule</a> -->
                  </div>
                </div>
                <div class="card-body">
                  <h5><b><center>{{ date('F Y') }}</b></center></h5>
                  <table class="table table-bordered" style="font-size: 12px">
                    <thead style="text-align: center">
                      <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th><th style="width:30%">Remarks</th>
                    </thead>
                    <tbody>
                      <?php
                        echo formatDTRrow(date('m'),date('Y'),Auth::user());
                      ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
    </div>
    <div class="col-md-6">
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
                        <?php
                          $bal = getLeaves(Auth::user()->id,$leaves->id);
                          

                          if($leaves->id == 1 || $leaves->id == 2)
                          {
                            // $bal = $bal + 1.25;           
                          }

                          $pending = getPending($leaves->id);
                          $projected = $bal - $pending;
                        ?>
                        <tr>
                          <td>{{ $leaves->leave_desc }}
                          </td>
                          <td align="center">{{ $bal }}</td>
                          <td align="center">{{ $pending }}</td>
                          <td align="center">{{ $projected }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer" style="display: none;">
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
                  <h3 class="card-title"><b>REQUEST STATUS</b></h3>
                  <div class="card-tools">
                  <a href="{{ url('staff-all-request') }}">View all requests</a>
                  </div>
                </div>
                <div class="card-body">
                  <table width="100%">
                    <tr>
                      <td style="width:30%"><b>TYPE</b></td>
                      <td style="width:30%" align="center"><b>DATE</b></td>
                      <td style="width:25%" align="center"><b>STATUS</b></td>
                      <td style="width:5%" align="center"></td>
                    </tr>
                    <tbody>
                      @foreach(checkRequest() AS $values)
                        <tr>
                          <td>{{ $values['request_desc'] }}
                              <?php
                                if(isset($values['request_lwop']))
                                    echo LWOPAlert($values['request_code']);
                              ?>
                          </td>
                          <td align="center">{{ $values['request_date'] }}</td>
                          <td align="center"><?php echo formatRequestStatus($values['request_action_status']) ?></td>
                          
                          <td>
                          @if($values['request_desc'] == 'T.O')

                          <span style="cursor:pointer" onclick="toPrint('to',{{ $values['request_id'] }})"><i class="fas fa-print" ></i></span>
                          @elseif($values['request_desc'] == 'O.T')

                          @elseif($values['request_desc'] == 'Work From Home')
                          <span style="cursor:pointer" onclick="toPrint('wfh',{{ $values['request_id'] }})"><i class="fas fa-print" ></i></span>

                          @else
                          <span style="cursor:pointer" onclick="toPrint('leave',{{ $values['request_id'] }})"><i class="fas fa-print" ></i></span>


                          @endif
                          </td>

                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <h5><small>LEAVE RECORD</small></h5>
                  <br>
                  <form method="POST" id="frmleave" enctype="multipart/form-data" action="{{ url('dtr/report/leave-record') }}" target="_blank">
                  {{ csrf_field() }}
                  <input type="hidden" name="employee" value="{{ Auth::user()->id }}">
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

  

    </div>
  </div>

  <form method="POST" id="frm_request_print" target="_blank">
    {{ csrf_field() }}
    <input type="hidden" name="req_id" id="req_id">
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

@include('pis.staff.leave-js')

<script>
  $("#payslip-mon_new,#payslip-mon_new2").val({{ date('n')}});

  //SUBMIT REQUEST

  //GET DATE
//   $('#leave_duration').on('apply.daterangepicker', function(ev, picker) {
//     var start = new Date(picker.startDate.format('YYYY-MM-DD')),
//     end   = new Date(picker.endDate.format('YYYY-MM-DD')),
//     diff  = new Date(end - start),
//     days  = diff/1000/60/60/24;
//     console.log(days);
//     if(days > 0)
//     {
//       console.log("pass");
//       $("#leave_times").hide();
//     }
//     else
//     {
//       $("#leave_times").show();
//     }
// });



  function showPayslip()
  {
    var win = window.open('{{ url("pdf/my-payslip") }}/' + $("#dtr-mon").val() + '-' + $("#dtr-year").val(), '_blank');
  }

</script>
@endsection