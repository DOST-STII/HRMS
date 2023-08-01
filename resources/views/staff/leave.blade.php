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

<?php

if(isset($staffid))
{
  $userid = $staffid;

}
else
{
  $staffid = Auth::user()->id;
}

?>

<div class="row">
  <div class="col-lg-4 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>APPLY FOR LEAVE</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
              <form method="POST" id="frm_request" enctype="multipart/form-data" action="{{ url('dtr/send-leave-request') }}">
              {{ csrf_field() }}
              
              @if(Auth::user()->usertype == 'Marshal')
              <div class="form-group">
                <strong>Employee</strong>
              
                <p class="text-muted">
                      <select class="form-control" name="userid2" id="userid2">
                        @foreach(getStaffDivision() AS $divs)
                          <option value="{{ $divs->id }}">{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>
                        @endforeach
                      </select>
                </p>
              </div>
              @elseif(Auth::user()->usertype == 'Administrator')
                <div class="form-group">
                  <strong>Employee</strong>
                
                  <p class="text-muted">
                        <select class="form-control" name="userid2" id="userid2">
                          @foreach(getAllUser() AS $users)
                            <option value="{{ $users->id }}">{{ $users->lname.", ".$users->fname." ".$users->mname }}</option>
                          @endforeach
                        </select>
                  </p>
                </div>
              @else
                <input type="hidden" name="userid2"  id="userid2" value="{{ Auth::user()->id }}">
              @endif

              <!-- LEAVE TYPE -->
              <div class="div-request" id="div-request-leave">
                <div id="option-leave">
                  <strong>Leave Type</strong>
                    <br>
                    <p class="text-muted">
                      <select class="form-control" name='leave_id' id='leave_id'>
                        
                        <?php
                            if(Auth::user()->usertype == 'Administrator')
                              $lv = App\Leave_type::whereNotIn('id',[5,13,14,15,16])->get();
                            else
                              $lv = App\Leave_type::whereNotIn('id',[5,13,14,15,16,19])->get();


                            foreach ($lv as $key => $lvs) {
                                  echo '<option value="'.$lvs->id.'">'.$lvs->leave_desc.'</option>';
                            }
                        ?>
                      </select>
                    </p>
                    <!-- <small class="badge badge-warning">Pls note that .....</small> -->
                  </div>

                  <strong>Duration</strong>
                    <div class="form-group">

                      <div class="input-group" id="option-leave-duration">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration" name="leave_duration">
                      </div>
                      <!-- /.input group -->

                      <div class="input-group" id="option-leave-duration2" style="display:none;">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration2" name="leave_duration2">
                      </div>

                      <div class="input-group" id="option-leave-duration3" style="display:none;">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration3" name="leave_duration3">
                      </div>
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

                    <div id="option-vl-select">
                      <br>
                      <strong>In case of Vacation/Special Privilege Leave</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vl_select1" name="vl_select" value="Within the Philippines" checked>
                          <label for="vl_select1">
                            Within the Philippines
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vl_select2" name="vl_select" value="Abroad">
                          <label for="vl_select2">
                            Abroad
                          </label>
                        </div>
                      </p>
                      <input type="text" class="form-control" name="vl_select_specify" id="vl_select_specify"  placeholder="Specify" style="display:none">
                    </div>

                    <div id="option-sl-select">
                      <br>
                      <strong>In case of Sick Leave</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="sl_select1" name="sl_select" value="Hospital" checked>
                          <label for="sl_select1">
                            Hospital
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="sl_select2" name="sl_select" value="Out Patient">
                          <label for="sl_select2">
                            Out Patient
                          </label>
                        </div>
                      </p>
                      <input type="text" class="form-control" name="sl_select_specify" id="sl_select_specify"  placeholder="Specify Illness">
                    </div>
                    <br>
                    <div id="option-remarks">
                      <strong>Remarks</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="remarks" id="remarks">
                      </p>
                    </div>


                    <div id="option-desc">
                      <strong>Description</strong>
                      <br>
                      <p class="text-muted" id="leave_def">
                        <?php
                          $lv = getLeaveInfo2(1);
                          echo $lv['leave_def'];
                        ?>
                      </p>
                    </div>

                </div>
              </form>

              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="button" class="btn btn-primary float-right" onclick="modalOnSubmit()">Submit</button>
              </div>
              
            </div>
            <!-- /.card -->
  </div>

  <div class="col-lg-8 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>LEAVE BALANCES</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
              <table width="100%">
                  <tr>
                    <td style="width:30%"><b>LEAVE BALANCES</b></td>
                    <td style="width:9%" align="center"><b>CURRENT</b></td>
                    <td style="width:30%" align="center"><b>PENDING</b></td>
                    <td style="width:30%" align="center"><b>PROJECTED BALANCE</b></td>
                  </tr>
                  <tbody>
                    @foreach(showLeaves() AS $leaves)
                      <?php
                        $bal = getLeaves($staffid,$leaves->id);
                        

                        if($leaves->id == 1 || $leaves->id == 2)
                        {
                          $bal = $bal;
                        }

                        $pending = getPending($leaves->id,$staffid);
                       
                        $projected = $bal - $pending;
                      ?>
                      <tr>
                        <td>{{ $leaves->leave_desc }}</td>
                        <td align="center">{{ $bal }}</td>
                        <td align="center">{{ $pending }}</td>
                        <td align="center">{{ $projected }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <br>
                <br>
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
                    <td style="width:30%" align="center"><b>STATUS</b></td>
                  </tr>
                  <tbody>
                    @foreach(checkRequest($staffid) AS $values)
                      <tr>
                        <td>{{ $values['request_desc'] }}</td>
                        <td align="center">{{ $values['request_date'] }}</td>
                        <td align="center"><?php echo formatRequestStatus($values['request_action_status']) ?></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
              </div>
              
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
$('#option-leave-duration2,#option-leave-duration3,#option-vl-select,#option-sl-select').hide();
$('#option-leave-duration,#option-vl-select').show();

  $("#userid2").val({{ $staffid }});

@if(Auth::user()->usertype == 'Administrator')
var now = new Date();
      now.setDate(now.getDate()+2);
      $('#leave_duration').daterangepicker({
          //minDate:now,
          isInvalidDate: function(date) {
            if (date.day() == 0 || date.day() == 6)
              return true;
            return false;
          }   
      });
@else
      var now = new Date();
      now.setDate(now.getDate()+2);
      isWeekend(now);
      
      if(now.getDay() === 6)
        now.setDate(now.getDate()+2);
      if(now.getDay() === 0)
        now.setDate(now.getDate()+1);

      $('#leave_duration').daterangepicker({
          minDate:now,
          endDate: now,
          isInvalidDate: function(date) {
            if (date.day() == 0 || date.day() == 6)
              return true;
            return false;
          }   
      });
@endif

    var now2 = new Date();
    $('#leave_duration2').daterangepicker({
      // maxDate: now2,
      isInvalidDate: function(date) {
          if (date.day() == 0 || date.day() == 6)
            return true;
          return false;
        }
    });

  $('#leave_duration3').daterangepicker();

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

    //CHECK TYPE OF LEAVE

  $("#leave_id").change(function(){

    getLeaveDef(this.value);

    $('#option-leave-duration,#option-leave-duration2,#option-leave-duration3,#option-vl-select,#option-sl-select').hide();


    if(this.value == 1)
    {
      $('#option-leave-duration,#option-vl-select').show();
    }
    else if(this.value == 2)
    {
      $('#option-leave-duration2,#option-sl-select').show();
    }
    else if(this.value == 6)
    {
      $('#option-leave-duration').show();
    }
    else if(this.value == 16)
    {
      $('#option-leave-duration3').show();
    }
    else if(this.value == 3)
    {
      $('#option-leave-duration2,#option-vl-select').show();
    }
    else if(this.value == 9 || this.value == 7)
    {
      $('#option-leave-duration3').show();
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


  function getLeaveDef(id)
    {
      $.getJSON( "{{ url('leave/json') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                  $("#leave_def").empty().append(datajson.leave_def);

              });
    }

    $("#userid2").change(function(){
        window.location.replace("{{ url('staff/leave/') }}/"+this.value);
    });

    function isWeekend(date = new Date()) {
    if(date.getDay() === 6)
    {
      return "Saturday";
    }

    if(date.getDay() === 0)
    {
      return "Sunday";
    }

    //return date.getDay() === 6 || date.getDay() === 0;
  }
</script>
@endsection