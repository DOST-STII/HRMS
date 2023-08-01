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
  <div class="col-lg-6 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  Employee : <select class="form-control-sm" name="userid" id="userid" onchange="showDTR()">
                  @foreach(getStaffDivision() AS $divs)
                    <option value='{{ $divs->id }}'>{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>
                  @endforeach
                  <?php
                  // $data['mon'] = 1;
                  // $data['yr'] = 2022;

                      // if(Auth::user()->division == 'q' && Auth::user()->usertype == 'Marshal')
                      // {
                      //   echo "<option value='141'>Molina, Susan S.</option>";
                      // }
                    ?>
                </select>
                </h3>
                <div class="card-tools">
                <div class="float-right" style="margin-right: 1%">
                  <select class="form-control-sm" name="dtr_year" id="dtr_year" onchange="showDTR()">
                  <?php
                    for ($i = date('Y'); $i >= (date('Y') - 5) ; $i--) { 
                        echo "<option value='$i'>".$i."</option>";
                    }
                  ?>
                  </select>
              </div>
              <input type="hidden" id="date_year_mon" value="{{ $data['yr'].'-'.$data['mon'] }}">
              <input type="hidden" id="date_year_mon2" value="{{ $data['yr'].'-'.$data['mon'] }}">
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
                <h5><b><center>{{ date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'] }}</b></center></h5>
                <p align="right"><a href="{{ url('update-weekly-schedule/'.date('m').'/'.date('Y') ) }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-alt"></i> Update Weekly Schedule</a></p>
                  

                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th>
                    <th style="width:30%">Remarks</th>
                  </thead>
                  <tbody>
                    <?php
                      echo formatDTRrow($data['mon'],$data['yr'],$data['empinfo']);
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <center></center>
              </div>
            </div>
            <!-- /.card -->
    
  </div>
  <div class="col-md-6">

    <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                    <?php
                        $datewfh = Carbon\Carbon::parse(date('Y-m-d'));
                        $weekNumber = $datewfh->weekNumberInMonth;
                    ?>
                    <b>WEEKLY SCHEDULE ({{date('F',mktime(0, 0, 0, $data['mon'], 10))}})</b>
                </h3>
                <div class="card-tools">
                  <select class="form-control-sm" id="weeknum" name="weeknum" onchange="showDTR()">
                      <?php
                        
                        $weeknum = getWeekMonth('total',null,$data['mon'],$data['yr']);
                        --$weeknum;
                        for ($i=1; $i < $weeknum; $i++) { 
                          echo "<option value='".$i."'>Week ".$i."</option>";
                        }
                      ?>
                  </select>



                </div>
              </div>
              <div class="card-body">
                 <table class="table table-bordered">
                      <thead>
                          <th></th>
                          <th style="width: 10%;"><center>M</center></th>
                          <th style="width: 10%;"><center>T</center></th>
                          <th style="width: 10%;"><center>W</center></th>
                          <th style="width: 10%;"><center>Th</center></th>
                          <th style="width: 10%;"><center>F</center></th>
                      </thead>
                      <td align="center"><b>Staff</b></td>
                        <tr><td></td>
                        <?php
                          if($data['weeknum'] == 1)
                          {
                                  $dayDesc = weekDesc($data['yr']."-".$data['mon']."-1");

                                  switch ($dayDesc) {
                                    case 'Tue':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(1)"><b>1</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(2)"><b>2</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(3)"><b>3</b></td><td align="center" ><b>4</b></td>';
                                      break;

                                    case 'Wed':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(1)"><b>1</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(2)"><b>2</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(3)"><b>3</b></td>';
                                      break;

                                    case 'Fri':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(1)"><b>1</b></td>';
                                      break;

                                    case 'Thu':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(1)"><b>1</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(2)"><b>2</b></td>';
                                      break;
                                    
                                    case 'Mon':
                                        $d = '<td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(1)"><b>1</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(2)"><b>2</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(3)"><b>3</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(4)"><b>4</b></td><td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched(5)"><b>5</b></td>';
                                      break;

                                    case 'Sat':
                                        $d = '<td align="center" ></td><td align="center" ></td><td align="center" ></td><td align="center" ></td><td align="center" ></td>';
                                  }
                            echo $d;
                          }
                          else
                          {
                            $wkn = $data['weeknum'] + 1;
                            foreach(getWeekMonth('week',$wkn,$data['mon'],$data['yr']) AS $weeks)
                                {
                                    echo '<td align="center" style="cursor:pointer" class="text-primary" onclick="weeksched('.$weeks.')"><b>'.$weeks.'</b></td>';
                                }
                          }
                        ?>
                        </tr>
                      <?php
                          if($data['weeknum'] == 1)
                          {
                            foreach(getAllStaffDivision() AS $lists)
                            {

                              $txtcolor = '';
                              if($lists->employment_id == 8)
                              {
                                $txtcolor = 'class="text-success"';
                              }

                              echo "<tr><td ".$txtcolor.">".$lists->lname.", ".$lists->fname." ".$lists->mname."</td>";
                                  $dayDesc = weekDesc($data['yr']."-".$data['mon']."-1");

                                  switch ($dayDesc) {
                                    case 'Tue':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['mon'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-4') .'</b></td>';
                                      break;

                                    case 'Wed':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td>';
                                      break;

                                    case 'Thu':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td>';
                                      break;

                                    case 'Fri':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td>';
                                      break;
                                    
                                    case 'Mon':
                                        echo '<td align="center" ><b>'.getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-4') .'</b></td><td align="center" ><b>'. getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-5') .'</b></td>';
                                      break;


                                    case 'Sat':
                                        echo '<td align="center" ></td><td align="center" ></td><td align="center" ></td><td align="center" ></td><td align="center" ></td>';
                                      break;
                                  }
                            }
                            echo "</tr>";
                          }
                          else
                          {
                            foreach(getAllStaffDivision4() AS $lists)
                            {
                              $txtcolor = '';
                              if($lists->employment_id == 8)
                              {
                                $txtcolor = 'class="text-success"';
                              }
                              elseif($lists->employment_id == 5)
                              {
                                $txtcolor = 'class="text-info"';
                              }

                                echo "<tr><td ".$txtcolor.">".$lists->lname.", ".$lists->fname." ".$lists->mname."</td>";

                                $wknm2 = $data['weeknum'] + 1;
                                foreach(getWeekMonth('week',$wknm2,$data['mon'],$data['yr']) AS $weeks)
                                {
                                  echo '<td align="center"><b>'.getWeekSchedStaff($lists->id,$data['yr'].'-'.$data['mon'].'-'.$weeks).'</b></td>';
                                }
                            }
                            echo "</tr>";
                          }
                        ?>

                        
                 </table>
              </div>
              <!-- /.card-body -->
            </div>
  </div>
</div>

<form method="POST" id="frm_request_print" target="_blank">
  {{ csrf_field() }}
  <input type="hidden" name="req_id" id="req_id">
</form>







<form method="POST" id="frm_index" action="{{ url('home') }}">
  {{ csrf_field() }}
  <input type="hidden" name="frm_mon" id="frm_mon">
  <input type="hidden" name="frm_yr" id="frm_yr">
  <input type="hidden" name="empid" id="empid">
</form>


<div class="modal" tabindex="-1" role="dialog" id="modalweeksched">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Weekly Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_sched" enctype="multipart/form-data" role="form" action="{{ url('dtr/weekly-schedule') }}">  
          {{ csrf_field() }}
          <input type="hidden" id="weekdate" name="weekdate">
          <input type="hidden" id="weeksched_mon" name="weeksched_mon" value="{{ $data['mon']  }}">
          <input type="hidden" id="weeksched_year" name="weeksched_year" value="{{ $data['yr']  }}">
          <input type="hidden" id="weeksched_weeknum" name="weeksched_weeknum" value="{{ $data['weeknum'] }}">
          <input type="hidden" id="weeksched_empid" name="weeksched_empid" value="{{ $data['empinfo']['id'] }}">
          <h4><b>DATE</b> : <span id="date_desc"></span></h4>
          <br>
          <table class="table table-bordered">
            <thead>
              <th>Staff</th>
              <th style="width: 15%;"><center>WFH</center></th>
              <th style="width: 15%;"><center>Office</center></th>
              <th style="width: 15%;"><center>Office/Pickup</center></th>
            </thead>
          @foreach(getAllStaffDivision4() AS $lists)
                        <tr>
                            <td>{{ $lists->lname.", ".$lists->fname." ".$lists->mname }}</td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_{{ $lists->id }}" value="WFH"></td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_{{ $lists->id }}" value="Office"></td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_{{ $lists->id }}" value="Pickup"></td>
                        </tr>
                      @endforeach
                    @if(Auth::user()->division == 'q' && Auth::user()->usertype == 'Marshal')
                      <tr>
                            <td>Molina, Susan S.</td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_141" value="WFH"></td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_141" value="Office"></td>
                            <td align="center"><input type="radio" class="form-control-sm" name="dt_141" value="Pickup"></td>
                        </tr>
                      @endif
          </table>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitSched()">Save changes</button>
      </div>
    
    </div>
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
        <form method="POST" id="frm_sched_update" enctype="multipart/form-data" role="form" action="{{ url('dtr/weekly-schedule-edit') }}">  
          {{ csrf_field() }}
        <input type="hidden" name="schedid" id="schedid">
        <input type="hidden" name="schedmon" id="schedmon" value="{{ $data['mon'] }}">
        <input type="hidden" name="schedyear" id="schedyear" value="{{ $data['yr'] }}">
        <input type="hidden" name="scheduserid" id="scheduserid" value="{{ $data['empinfo']['id'] }}">
        <input type="hidden" name="schedweek" id="schedweek" value="{{ $data['weeknum'] }}">


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
              OFFICE/PICKUP
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

    getLeaveCTO({{ AUth::user()->id }});
    $("#cto-choice").hide();
    
    $("#dtr_mon").val({{ $data['mon'] }});
    $("#userid").val({{ $data['empinfo']['id'] }});
    $("#dtr_year").val({{ $data['yr'] }});
    $("#weeknum").val({{ $data['weeknum'] }});

   var now = new Date();
      now.setDate(now.getDate()+2);
      $('#leave_duration').daterangepicker({
          endDate:now,
          minDate:now,
          isInvalidDate: function(date) {
            if (date.day() == 0 || date.day() == 6)
              return true;
            return false;
          }   
      });
    

    var now2 = new Date();
    $('#leave_duration2').daterangepicker({
      // maxDate: now2,
      isInvalidDate: function(date) {
          if (date.day() == 0 || date.day() == 6)
            return true;
          return false;
        }
    });

    $('#leave_duration3').daterangepicker(
      {
        singleDatePicker : true,
        // isInvalidDate: function(date) {
        //     if (date.day() == 0 || date.day() == 6)
        //       return true;
        //     return false;
        //   }
      }
    );

    $('#leave_duration4').daterangepicker();

    checkPendingRequest();

  });

  $('input:radio[name="vl_select"]').change(
    function(){
      $("#vl_select_specify").hide();
      if(this.value == 'Abroad')
            $("#vl_select_specify").show();
    });
  
  $('input:radio[name="request_cto"]').change(
    function(){
      $("#div_request_ot,#cto_bal").hide();
      if(this.value == 'apply_cto')
            $("#cto_bal").show();
      else
            $("#div_request_ot").show();
            
    });

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
        $("#overlay").show();
        $("#frm_request").submit();
      }
    })
  }

  $("#userid2").change(function(){
    // alert(this.value);
    getLeaveCTO(this.value);
  });
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

  
  function showRequest(title)
  {
    $("#modal-request-for-title").text(title);
    $("#modal-request-for").modal("toggle");

    $("#option-leave,#option-remarks,#option-to,#option-cto,#option-wfh,#cto_bal,#cto-choice,#cto_bal").hide();

    $('#option-leave-duration,#option-leave-duration2,#option-leave-duration3,#option-leave-duration4,#option-vl-select,#option-sl-select').hide();

    switch(title)
    {
      case "Apply for Leave":
        $("#option-leave,#option-leave-duration2,#option-vl-select").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-leave-request') }}"});
      break;

      case "Work From Home":
        $("#option-leave-duration3,#option-wfh").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-wfh-request') }}"});
      break;

      case "Request for T.O":
        $("#option-leave-duration4,#option-to").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-to-request') }}"});
        // $("#frm_request").attr({"action" : "{{ url('test-leave') }}"});
      break;

      case "Request for O.T / CTO":
        $("#option-leave-duration3,#option-cto,#cto-choice").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-ot-request') }}"});
      break;
    }
  }


  //CHECK TYPE OF LEAVE

  $("#leave_id").change(function(){

    getLeaveDef(this.value);
    
    $('#option-leave-duration,#option-leave-duration2,#option-leave-duration3,#option-leave-duration4,#option-vl-select,#option-sl-select').hide();


    if(this.value == 1)
    {
      $('#option-leave-duration2,#option-vl-select').show();
    }
    else if(this.value == 2)
    {
      $('#option-leave-duration2,#option-sl-select').show();
    }
    else if(this.value == 6)
    {
      $('#option-leave-duration2').show();
    }
    else if(this.value == 16)
    {
      $('#option-wfh').show();
      $('#option-leave-duration3').show();
    }
    else if(this.value == 3)
    {
      $('#option-leave-duration2,#option-vl-select').show();
    }
    else
    {
      $('#option-leave-duration2').show();
    }

    switch(this.value)
    {
        case 1:
        break;
        case 2:
        break;
    }

  });

  function showDTR()
  {
    // mon = $("#dtr_mon").val();
    // yr = $("#dtr_year").val();
    // empid = $("#userid").val();

    // $("#frm_mon").val(mon);
    // $("#frm_year").val(yr);
    // $("#empid").val(empid);

    // $("#frm_index").submit();
    mon = $("#dtr_mon").val();
    yr = $("#dtr_year").val();
    userid = $("#userid").val();
    weeknum = $("#weeknum").val();

    

    window.location.replace("{{ url('home3') }}/"+mon+"/"+yr+"/"+userid+"/"+weeknum);
  }

  function showPayslip()
  {
    var win = window.open('{{ url("pdf/my-payslip") }}/' + $("#dtr-mon").val() + '-' + $("#dtr-year").val(), '_blank');
  }


  function toPrint(type,id) {

    if(type == 'leave')
    {
      action = "{{ url('dtr/print-leave') }}";
    }
    else if(type == 'to')
    {
      action = "{{ url('dtr/print-to') }}";
    }
    else if(type == 'wfh')
    {
      action = "{{ url('dtr/print-wfh') }}";
    }
    else
    {
      action = "{{ url('dtr/print-ot') }}";
    }

    $("#req_id").val(id);
    $("#frm_request_print").prop('action',action).submit();
  }


  function getLeaveDef(id)
    {
      $.getJSON( "{{ url('leave/json') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                  $("#leave_def").empty().append(datajson.leave_def);

              });
    }

    function getLeaveCTO(id)
    {
      // alert(id);
      $.getJSON( "{{ url('staff/json/cto') }}/"+id, function( datajson ) {
              }).done(function(datajson) {
                  console.log(datajson);
                  $("#ctobalance").empty().append("Balance : " + datajson.balance + "<br/>Pending : "+datajson.pending+"<br/>Projected : "+datajson.projected);
              });
    }

  function weeksched(days)
    {
      $d = $('#date_year_mon2').val();
      $("#weekdate").val($d+'-'+days);
      $("#date_desc").empty().text($d+'-'+days);
      $("#modalweeksched").modal('toggle');
    }

    function submitSched()
    {
      $("#overlay").show();
      $("#frm_sched").submit();
    }

    function editSched(schedid)
    {

      $.getJSON( "{{ url('dtr/json/weekly-schedule') }}/"+schedid, function( datajson ) {
                
              }).done(function(datajson) {
                // console.log(datajson['sched_status']);
                $("#schedid").val(datajson['id']);
                $("input[name=sched_edit_status][value=" + datajson['sched_status'] + "]").prop('checked', 'checked');
              });

      $("#modalSchedEdit").modal('toggle');
    }


    // function getCTOBal(userid)
    // {

    //   $.getJSON( "{{ url('dtr/ctobal') }}/"+userid, function( datajson ) {
                
    //           }).done(function(datajson) {
    //             // console.log(datajson['sched_status']);
    //             // $("#schedid").val(datajson['id']);
    //             // $("input[name=sched_edit_status][value=" + datajson['sched_status'] + "]").prop('checked', 'checked');
    //           });

    //   $("#modalSchedEdit").modal('toggle');
    // }

    function updateSched()
    {
      $("#overlay").show();
      $("#frm_sched_update").submit();
    }

    function printDTR()
    {
      
    }
</script>
@endsection