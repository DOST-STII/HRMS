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
  <div class="col-lg-8 col-md-12 col-sm-12">
    <h5 class="alert alert-warning"><center>Click on the column to edit/add</center></h5>
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                   @if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'Director')
                    <div class="float-center" style="margin-right: 1%">

                      <select class="form-control-sm" name="userid" id="userid" onchange="showDTR()">
                        @foreach(getICOSDivision(null,Auth::user()->division) AS $divs)
                          @if(isset($divs->rfid))
                          <option value='{{ $divs->id }}'>{{ ucwords(strtolower($divs->lname)).', '.$divs->fname.' '.$divs->mname }}</option>
                          @endif
                        @endforeach
                      </select>

                    </div>
                    @endif
                </h3>
              
                  
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
             
                <h3 class="card-title float-right" style="padding-right: 10px">
                    
                </div>
              </div>
              <div class="card-body">
                <p align="right"><button class="btn btn-primary btn-sm" onclick="addDTR()"><i class="fas fa-plus"></i>ADD DTR</button></p>
                <center>
                <h4><b>Daily Time Record (DTR)</b></h4>
                <h5><b>{{ date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'] }}</b></h5>
                </center>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th>
                    <th>OT In</th><th>OT Out</th><th style="width:30%">Remarks</th>
                  </thead>
                  <tbody>
                    <?php

                      $mon2 = date('F',mktime(0, 0, 0, $data['mon'], 10));
                      $date = $mon2  ."-" . $data['yr'];

                      $total = Carbon\Carbon::parse($date)->daysInMonth;
                      $prevweek = 1;

                      $week_num = 2;

                      $mon = $data['mon'];
                      $yr = $data['yr'];

                      echo "<tr><td></td><td colspan='7' align='center'>  <b>WEEK 1 </b> </td></tr>";
                      for($i = 1;$i <= $total;$i++)
                      {
                        $weeknum = weekOfMonth($yr.'-'.$mon.'-'.$i);
                        if($weeknum == $prevweek)
                        {
                          
                        }
                        else
                        {
                          $prevweek = $weeknum;
                          echo "<tr><td></td><td colspan='7' align='center'> <b>WEEK $week_num </b> </td></tr>";
                          $week_num++;  
                        }

                       $dtr_date = $yr.'-'.$mon.'-'.$i;

                       $dayDesc = weekDesc(date($yr.'-'.$mon.'-'.$i));


                      $dtr_date2 = date("Y-m-d",strtotime($dtr_date));
                      //$dtrss = getEmpDTR($dtr_date2,$data['dtr']);

                      $dtr = getDTRemp($dtr_date,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']);

                      if(isset($dtr))
                      {
                          if($dtr['id'])
                            {
                              $dtrid = $dtr['id'];
                            }
                            else
                            {
                              $dtrid = 0;
                            }
                          
                          $amIn = "";
                          $amOut = "";
                          $pmIn = "";
                          $pmOut = "";
                          $otIn = "";
                          $otOut = "";

                          $req = "";
                          $remarks = "";

                        //    //CHECK IF HAS HOLIDAY
                          if(!checkIfHoliday($dtr_date))
                              {

                               $amIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",1,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamIn'])."</div></td>";
                               $amOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",2,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamOut'])."</div></td>";
                               $pmIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",3,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmIn'])."</div></td>";
                               $pmOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",4,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmOut'])."</div></td>";
                               $otIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",5,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRotIn'])."</div></td>";
                               $otOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",6,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRotOut'])."</div></td>";

                               $remarks = "<td colspan='6' align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",7,".$yr.",".$mon.",".$i.")'>".$dtr['dtr_remarks']."</td>";

                              $dtrcol = $amIn."".$amOut."".$pmIn."".$pmOut."".$otIn."".$otOut."".$remarks;

                              // //CHECK IF WFH
                              // if(checkIfDTR('check',$dtr_date2,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']) > 0)
                              //     {
                              //       $wfh = checkIfDTR('list',$dtr_date2,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']);
                              //       switch($wfh['wfh'])
                              //                     {
                              //                       case 'Wholeday':
                              //                         $dtrcol = "<td align='center' colspan='7' class='text-success'><b>WFH</b></td>";
                              //                       break;

                              //                       case "AM":
                              //                          $dtrcol = $amIn."".$amOut."<td align='center' colspan='2' class='text-success'><b>WFH</b></td><td></td><td></td><td></td>";
                              //                       break;

                              //                       case "PM":
                              //                           $dtrcol = "<td align='center' colspan='2' class='text-success'><b>WFH</b></td>".$pmIn."".$pmOut."</td><td></td><td></td><td></td>";
                              //                       break;
                              //                     }
                              //     }
                                  
                              //     //CHECK IF T.O
                              //     if(checkIfDTR('check',$dtr_date2,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']) > 0)
                              //       {
                              //       $wfh = checkIfDTR('list',$dtr_date2,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']);
                              //       switch($wfh['dtr_to'])
                              //                     {
                              //                       case 'Wholeday':
                              //                         $dtrcol = "<td align='center' colspan='7' class='text-success'><b>On-Trip</b></td>";
                              //                       break;

                              //                       case "AM":
                              //                           $dtrcol = $amIn."".$amOut."<td align='center' colspan='2' class='text-success'><b>On-Trip</b></td></td><td></td><td></td><td></td>";
                              //                       break;

                              //                       case "PM":
                              //                           $dtrcol = "<td align='center' colspan='2' class='text-success'><b>On-Trip</b></td>".$pmIn."".$pmOut."</td><td></td><td></td><td></td>";
                              //                       break;
                              //                     }
                              //     }
                              }
                              else
                              {
                                $dtrcol = "<td align='center' colspan='7'>".getHoliday($dtr_date)."</td>";
                              }

                          
                          
                          echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td>".$dtrcol."</tr>";

                        }
                        
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <center><button class="btn btn-primary" onclick="showPrint()"><i class="fas fa-print"></i> Print DTR</button></center>
              </div>
            </div>
            <!-- /.card -->
  </div>
</div>

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

          <input type="hidden" name="userid" id="userid" value="{{ $data['emp']['id'] }}">
          <input type="hidden" name="yr" id="yr" value="{{ $data['yr'] }}">
          <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
          <input type="hidden" name="day" id="day" value="">
          <input type="hidden" name="dtr_colid" id="dtr_colid">
          <input type="hidden" name="dtr_col" id="dtr_col">
          <input type="hidden" name="dtr_orig" id="dtr_orig">
          <input type="hidden" name="dtr_url" id="dtr_url" value="{{ url('dtr/icos/'.$data['mon'].'/'.$data['yr'].'/'.$data['emp']['id']) }}">
          
          <div id="div_time">
            <span id="desc"><b>Time</b></span>
            <input type="time" class="form-control" name="dtr_val" id="dtr_val">
          </div> 

          <div id="div_remarks" style="display: none">
            <span id="desc"><b>Remarks</b></span>
            <input type="text" class="form-control" name="dtr_remarks" id="dtr_remarks">
          </div> 
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalprint">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">DTR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="POST" id="frm_print" enctype="multipart/form-data" action="{{ url('dtr-icos/pdf') }}" target="_blank">  
              {{ csrf_field() }}
              <input type="hidden" name="userid2" id="userid2">
              <input type="hidden" name="mon2" id="mon2">
              <input type="hidden" name="yr2" id="yr2">
              <input type="hidden" name="deadln" id="deadln">
           
          <p class="text-muted">
          <div class="form-group clearfix">
          <div id="leave_times" style="display: block">

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icoswholeday" name="deadline" value="1" checked>
            <label for="icoswholeday">
               1-15
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosam" name="deadline" value="2">
            <label for="icosam">
              16-31
            </label>
          </div>

        </div>
        </div>
        </p>
      </div>
       </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="printDTR()">Print DTR</button>
      </div>
    </div>
  </div>
</div>



<div class="modal" tabindex="-1" role="dialog" id="modalAdd">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ADD DTR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_dtr2" enctype="multipart/form-data" role="form" action="{{ url('dtr/icos/add') }}">  
          {{ csrf_field() }}
          <input type="hidden" name="add_wfhto_userid" id="add_wfhto_userid" value="{{ $data['emp']['id'] }}">
          <p class="text-muted">
          <div class="form-group clearfix">
          <div id="leave_times" style="display: block">


          <div>
            <span id="desc"><b>DATE</b></span>
            <input type="date" class="form-control" name="icos_dtr_date" id="icos_dtr_date" min="{{ $data['yr'] }}-{{ $data['mon'] }}-01" max="{{ $data['yr'] }}-{{ $data['mon'] }}-31" value="{{ date('Y-m-d') }}">
          </div> 

          <!-- <br> -->

          <!-- <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icoswfh" name="icos_dtr_type" value="WFH" checked>
            <label for="icoswfh" style="font-size: 14px">
               WFH
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosto" name="icos_dtr_type" value="T.O">
            <label for="icosto" style="font-size: 14px">
                  T.O
            </label>
          </div>

          <div class="icheck-primary d-inline" style="margin-right: 10px">
            <input type="radio" id="icosleave" name="icos_dtr_type" value="LEAVE">
            <label for="icosleave" style="font-size: 14px">
                  LEAVE
            </label>
          </div> -->

          <!-- <div class="icheck-primary d-inline" style="margin-right: 10px;dis:0">
            <input type="radio" id="icostime" name="icos_dtr_type" value="TIME" checked>
            <label for="icostime" style="font-size: 14px">
                  TIME
            </label>
          </div> -->
          <input type="hidden" name="icos_dtr_type" value="TIME">
          
        </div>
        </div>
        </p>
     

        <p class="text-muted">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_wholeday" name="icos_dtr_time" value="Wholeday" checked>
                        <label for="leave_time_wholeday">
                          Whole day
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_am" name="icos_dtr_time" value="AM">
                        <label for="leave_time_am">
                          AM
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_pm" name="icos_dtr_time" value="PM">
                        <label for="leave_time_pm">
                          PM
                        </label>
                      </div>
                    </div>
                    </div>
                    </p>

        <strong>Remarks</strong>
            <br>
            <p class="text-muted">
              <input type="text" class="form-control" name="icos_remarks" id="icos_remarks">
            </p>
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
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
$("#dtr_mon,#mon2").val({{ $data['mon'] }});
$("#userid,#userid2").val({{ $data['emp']['id'] }});
$("#dtr_year,#yr2").val({{ $data['yr'] }});

function showEdit(colid,userid,col,y,m,d)
{
    $("#div_time").show();
    $("#div_remarks").hide();

    $("#dtr_orig,#dtr_val").val('');
    $("#day").val(d);
    $("#dtr_col").val(col);
    $("#dtr_colid").val(colid);

    $.getJSON( "{{ url('json/dtr') }}/"+userid+"/icos/"+colid+"/"+col, function( datajson ) {
              }).done(function(datajson) {
                $("#dtr_orig,#dtr_val").val(datajson);
            }).fail(function() {
            });

    if(col == 7)
    {
        $("#div_time").hide();
        $("#div_remarks").show();
    }
    $("#modalEdit").modal("toggle");
}

function showDTR()
{
  mon = $("#dtr_mon").val();
  yr = $("#dtr_year").val();
  userid = $("#userid").val();

  $("#overlay").show();

  window.location.replace("{{ url('dtr/icos') }}/"+mon+"/"+yr+"/"+userid);
}


function addDTR()
{
  $("#modalAdd").modal("toggle");
}


function showPrint()
{
  $("#modalprint").modal('toggle');
  // $("#frm_print").submit();
}

function printDTR()
{
  $("#frm_print").submit();
}

$("#icos_dtr_type").change(function(){
    alert(this.value);
})


$('input:radio[name="icos_dtr_type"]').change(
    function(){

      if(this.value == 'LEAVE')
            $("#icos_remarks").attr('disabled',true);
      else
            $("#icos_remarks").attr('disabled',false);
            
    });
</script>
@endsection