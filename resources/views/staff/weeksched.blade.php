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
  <div class="col-lg-4 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>WEEKLY SCHEDULE</b></h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm" onclick="editDetails()"><i class="fas fa-edit"></i> Edit Details</button>
                </div>
              </div>
              <div class="card-body">
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
                  <br>
                  <h6><b>Pickup point : </b> {{ Auth::user()->pickup }} </h6>
                  <h6><b>Contact Number : </b> {{ Auth::user()->cellnum }} </h6>
                  <!-- <center><h4><b>{{ date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'] }}</b></h4></center> -->
                  <table class="table table-bordered">
                      <thead>
                          <th style="width: 25%;"><center>Day</center></th>
                          <th style=""><center>Remarks</center></th>
                      </thead>
                      <tbody>
                      <?php
                        $mon = date('F',mktime(0, 0, 0, $data['mon'], 10));
                        $date = $mon  ."-" . $data['yr'];
                        $total = Carbon\Carbon::parse($date)->daysInMonth;
                        $weeks = getWeekMonth('total',null,$data['mon'],$data['yr']);
                        $prevweek = 1;
                        $week_num = 2;

                        echo "<tr><td align='center'></td><td align='center'>  <b>WEEK 1 </b> </td></tr>";
                        for($i = 1;$i <= $total;$i++)
                        {
                          $weeknum = weekOfMonth($data['yr'].'-'. $data['mon'].'-'.$i);
                          $dayDesc = weekDesc(date($data['yr'].'-'.$data['mon'].'-'.$i));
                          $dt = date($data['yr'].'-'.$data['mon'].'-'.$i);
                          if($weeknum == $prevweek)
                          {
                            
                          }
                          else
                          {
                            $prevweek = $weeknum;
                            echo "<tr><td align='center'></td><td align='center'> <b>WEEK $week_num </b> </td></tr>";
                            $week_num++;
                          }

                          $st = getSchedStaff('val',Auth::user()->id,$dt);
                          $stid = getSchedStaff('id',Auth::user()->id,$dt);

                          echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='background-color:#EEE;cursor:pointer' onclick='editSched(".$stid.",\"".$dt."\")'>".$st."<input type='hidden' id='sched_id_".$stid."' value='$st'></td></tr>";
                        }
                        
                      ?>
                      </tbody>
                    </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalSchedEdit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">EDIT SCHEDULE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_sched_update" enctype="multipart/form-data" role="form" action="{{ url('update-weekly-schedule-send') }}">  
          {{ csrf_field() }}
        <input type="hidden" name="schedid" id="schedid">
        <input type="hidden" name="sched_date" id="sched_date">
        <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
        <input type="hidden" name="yr" id="yr" value="{{ $data['yr'] }}">
        <input type="hidden" name="sched_userid" id="sched_userid" value="{{ Auth::user()->id }}">

        <p class="text-muted">
          <div class="form-group clearfix">
          <div id="leave_times" style="display: block">

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icoswholeday" name="sched_edit_status" value="WFH">
            <label for="icoswholeday">
               WFH
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosam" name="sched_edit_status" value="Office">
            <label for="icosam">
              OFFICE
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icospm" name="sched_edit_status" value="Pickup">
            <label for="icospm">
              PICKUP
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosontrip" name="sched_edit_status" value="On-Trip">
            <label for="icosontrip">
              ON-TRIP
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosonleave" name="sched_edit_status" value="On-Leave">
            <label for="icosonleave">
              ON-LEAVE
            </label>
          </div>
          <br/>
          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosremove" name="sched_edit_status" value="Remove">
            <label for="icosremove">
              REMOVE
            </label>
          </div>

        </div>
        </div>
        </p>
        </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSched()">Save changes</button>
      </div>
    
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">EDIT DETAILS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_details" enctype="multipart/form-data" role="form" action="{{ url('update-weekly-schedule-details') }}">  
          {{ csrf_field() }}

          <span id="desc"><b>Pickup point</b></span>
          <input type="text" class="form-control" name="pickup" id="pickup" value="{{ Auth::user()->pickup }}">
          <br>
          <span id="desc"><b>Contact No.</b></span>
          <input type="text" class="form-control" name="cellnum" id="cellnum" value="{{ Auth::user()->cellnum }}">

          <input type="hidden" name="mon2" id="mon2" value="{{ $data['mon'] }}">
          <input type="hidden" name="yr2" id="yr2" value="{{ $data['yr'] }}">
       
        
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
       </form>
    </div>
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
  $("#dtr_mon").val({{ $data['mon'] }});
  $("#dtr_year").val({{ $data['yr'] }});

  function editSched(id,dt)
  {
    if(id != null)
    {
      $("#schedid").val(id);
      $("#sched_date").val(dt);
      // $("#sched_id_status").val($("#sched_id_"+id).val());
      // alert($("#sched_id_"+id).val());
      st = $("#sched_id_"+id).val();
      if(st != "")
      {
         $("input[name=sched_edit_status][value=" + st + "]").prop('checked', 'checked');
      }
     
    }
    else
    {

    }
    
    $("#modalSchedEdit").modal('toggle');
  }

  function updateSched()
  {
    $("#overlay").show();
    $("#frm_sched_update").submit();
  }

  function editDetails()
  {
    $("#modalEdit").modal('toggle');
  }

  function updateDetails()
  {
    $("#overlay").show();
    $("#frm_details").submit();
  }

  function showDTR()
  {
    mon = $("#dtr_mon").val();
    yr = $("#dtr_year").val();

    window.location.replace("{{ url('update-weekly-schedule') }}/"+mon+"/"+yr);
  }
</script>
@endsection