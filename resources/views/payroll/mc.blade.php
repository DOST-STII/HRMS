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

  <!-- MAINE INCLUDE MO TO -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection

@section('content')

<div class="row">
<form method="POST" id="frm_mc" enctype="multipart/form-data" role="form" action="{{ url('payroll/mc-print') }}" target="_blank">  
  {{ csrf_field() }}
  <input type="hidden" name="print_mc_mon" value="{{ $data['mon'] }}">
  <input type="hidden" name="print_mc_year" value="{{ $data['yr'] }}">
</form>

  <div class="col-lg-12 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  MC REPORT
                </h3>
                <div class="card-tools" style="width: 30%;">

                @if(!checkMCProcess($data['mon'],$data['yr']))
                <?php
                    $cl = "style='cursor:pointer;' class='text-primary'";
                ?>
                <div class="float-right" style="margin-right: 1%">
                    <button class="btn btn-success btn-sm" onclick="processMC()">Process</button>
                </div>
                @else
                <?php
                    $cl = "";
                    //GET FILE
                    $mcp = App\Payroll\MCProcess::where('payroll_mon',$data['mon'])->where('payroll_year',$data['yr'])->first();
                ?>
                <div class="float-right" style="margin-right: 1%">
                    <a href="{{ url('payroll/mc-textfile/'.$data['mon'].'/'.$data['yr']) }}" target="_blank" class="btn btn-success btn-sm">Download Text File</a>
                </div>
                @endif

                <div class="float-right" style="margin-right: 1%">
                    <button class="btn btn-primary btn-sm" onclick="submitMCReport()">Print</button>
                </div>

                <div class="float-right" style="margin-right: 1%">
                  <select class="form-control-sm" name="payroll_year" id="payroll_year" onchange="showMC()">
                  <?php
                    for ($i = date('Y'); $i <= (date('Y') + 3) ; $i++) { 
                        echo "<option value='$i'>".$i."</option>";
                    }
                  ?>
                  </select>
                  
                </div>
                

              <div class="float-right" style="margin-right: 1%">
                <select class="form-control-sm" name="payroll_mon" id="payroll_mon" onchange="showMC()">
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
                
                <table class="table" id="tbl" style="font-size: 11px;">
                  <thead>
                    <th>ID</th>
                    <th>PayrollDate</th>
                    <th>Employee</th>
                    <th>Division</th>
                    <th>BasicSalary</th>
                    <th>LPCurrent</th>
                    <th>SA</th>
                    <th>LA</th>
                    <th>HMO</th>
                    <th>GSIS</th>
                    <th>PMPC</th>
                    <th>CDC</th>
                    <th>GFAL</th>
                    <th>Landbank</th>
                    <th>ITW</th>
                    <th>HPRate</th>
                    <th>HP</th>
                    <th>Net MC</th>
                  </thead>

                  <?php

                  
                    $mon = $data['mon'];
                    $yr = $data['yr'];

                    $mondesc = date('F',mktime(0, 0, 0, $data['mon'], 10));

                    //GET MC
                    $mc = App\Payroll\MC::where('payroll_mon',$mon)->where('payroll_yr',$yr)->get();
                    $totalmc = 0;
                    
                    foreach ($mc as $key => $value) {

                        $user = App\User::where('id',$value->userid)->where('payroll',1)->first();

                        if($user)
                        {
                          $division = getDivision($user['division']);
                          $plantilla = getPlantillaInfo($user['username']);

                        switch($user['employment_id'])
                        {
                          case 1:
                          case 11:
                          case 13:
                          case 14:
                          case 15:
                              ++$totalmc;
                              $hp = $value->salary * $value->hprate;

                              //GET SALA
                              $sala = App\Employee_sala::where('user_id',$value->userid)->where('sala_mon',$mon)->where('sala_year',$yr)->first();

                              if($sala)
                              {
                                //GET MON LESS 1
                                $m = $mon;
                                $y = $yr;
                                if($m == 1)
                                {
                                    $m = 12;
                                    $y = $y - 1;
                                }
                                else
                                {
                                    --$m;
                                }

                                //DEDUC SA
                                // $mcd = App\MCday::where('userid',$value->userid)->whereMonth('req_date_from',$m)->whereYear('req_date_from',$y)->get();
                                // $sa_deduc = 0;
                                // foreach($mcd as $k => $v) {
                                //         // $dt = date('M d, y',strtotime($value->req_date_from));
                                //         // if($value->req_date_from != $value->req_date_to)
                                //         // {
                                //         //     $dt = date('M d, y',strtotime($value->req_date_from))." - ".date('M d, y',strtotime($value->req_date_to));
                                //         // }

                                //         // $rows3 .= "<tr><td>".$value->req_type."</td><td>".$dt."</td><td>".$value->req_deduc."</td></tr>";
                                //         $sa_deduc += $v->req_deduc;
                                // } 



                                //LEAVE
                                //MULTIPLE DATE
                                // $l1_total = App\Request_leave::where('user_id',$value->userid)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m)->whereYear('leave_date_from',$y)->count();

                                // //SINGLE DATE
                                // $l2_total = App\Request_leave::where('user_id',$value->userid)->where('parent','YES')->whereIn('leave_deduction',[1,0.5])->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',$m)->whereYear('leave_date_from',$y)->sum('leave_deduction');

                                // $l_total = $l1_total + $l2_total;



                                // $sa = $sala['sa_amt'] - ($l_total * 150);
                                // $la = $sala['la_amt'] - (($sala['la_amt'] / 22) * $l_total);
                              }
                              else
                              {
                                $sa = 0;
                                $la = 0;
                              }

                              if($user['employment_id'] == 15)
                              {
                                $sa = 0;
                                $la = 0;
                                $hp = 0;
                              }


                              $net_mc = ($value->sa + $value->la + $value->lp + $hp) - ($value->hmo + $value->gsis + $value->pmpc + $value->gfal + $value->cdc + $value->landbank + $value->itw);


                              ////MAINE
                              ///NILATAG KO SA ROW PARA SA TABLE
                              echo "<tr>
                                      <td>".$value->empcode."</td>
                                      <td>".$value->payroll_mon.'/'.$value->payroll_yr."</td>
                                      <td>".$user['lname'].", ".$user['fname']." ".$user['mname']." ".$user['exname']."</td>
                                      <td>".$division."</td>
                                      <td>".formatCash($value->salary)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"lp\",".$value->userid.")'>".formatCash($value->lp)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"sa\",".$value->userid.")'>".formatCash($value->sa)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"la\",".$value->userid.")'>".formatCash($value->la)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"hmo\",".$value->userid.")'>".formatCash($value->hmo)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"gsis\",".$value->userid.")'>".formatCash($value->gsis)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"pmpc\",".$value->userid.")'>".formatCash($value->pmpc)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"cdc\",".$value->userid.")'>".formatCash($value->cdc)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"gfal\",".$value->userid.")'>".formatCash($value->gfal)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"landbank\",".$value->userid.")'>".formatCash($value->landbank)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"itw\",".$value->userid.")'>".formatCash($value->itw)."</td>
                                      <td {{ $cl }} onclick='editMC(".$value->id.",\"hprate\",".$value->userid.")'>".$value->hprate."</td>
                                      <td>".formatCash($hp)."</td>
                                      <td>".formatCash($net_mc)."</td>
                                  </tr>";
                                break;
                        }
                        }
                        

                        
                    }

                  ?>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <?php
                  $emp = App\User::where('id','!=',374)->whereIn('employment_id',[1,11,13,14])->get();
                  
                  $pending = 0;
                  foreach ($emp as $key => $value) {
                      $mc = App\Payroll\MC::where('payroll_mon',$mon)->where('payroll_yr',$yr)->where('userid',$value->id)->count();
                      if($mc == 0)
                      {
                        ++$pending;
                      }
                  }

                  
                ?>
                <h3>Pending DTR Process : <span class="badge badge-danger" style="cursor:pointer" onclick="showPending()"><b>{{ $pending }}</b></span></h3>
              </div>
            </div>
            <!-- /.card -->
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalMC">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PENDING DTR PROCESS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table" id="tblMC">

        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

@if(!checkMCProcess($data['mon'],$data['yr']))
<div class="modal" tabindex="-1" role="dialog" id="modalEditMC">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">EDIT VALUE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" id="frm_mcedit" enctype="multipart/form-data" role="form" action="{{ url('payroll/mc-deduc-edit') }}">  
        {{ csrf_field() }}

              <input type="text" class="form-control" name="mcval" id="mcval" value="">
              <input type="hidden" class="form-control" name="mcvalid" id="mcvalid"  value="">
              <input type="hidden" class="form-control" name="mcvalcol" id="mcvalcol"  value="">
              <input type="hidden" class="form-control" name="mcmon" id="mcmon"  value="{{$data['mon']}}">
              <input type="hidden" class="form-control" name="mcyr" id="mcyr"  value="{{$data['yr']}}">
              <input type="hidden" class="form-control" name="mcuser" id="mcuser"  value="">
      </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="submitMCEdit()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<form method="POST" id="frm_mcproc" enctype="multipart/form-data" role="form" action="{{ url('payroll/mc-process') }}">  
  {{ csrf_field() }}
  <input type="hidden" class="form-control" name="mc_mon" id="mc_mon"  value="{{ $data['mon'] }}">
  <input type="hidden" class="form-control" name="mc_year" id="mc_year"  value="{{ $data['yr'] }}">
  <input type="hidden" name="mcpath" value="{{ $data['yr'].'-'.$data['mon'] }}">
</form>
@endif

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


<!-- MAINE INCLUDE MO TO -->
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>

  function editMC(id,col,userid)
  {
    $("#modalEditMC").modal("toggle");
    $("#mcval").empty().val("loading data..");
    $("#mcvalid").empty().val(id);
    $("#mcvalcol").empty().val(col);
    $("#mcuser").empty().val(userid);
    //GET VALUE
    $.getJSON( "{{ url('payroll/mc-deduc/json') }}/"+id+"/"+col, function( datajson ) {
              }).done(function(datajson) {
                $("#mcval").empty().val(datajson);
            }).fail(function() {
            });
  }

  function submitMCEdit()
  {
    $("#overlay").show();
    $("#frm_mcedit").submit();
  }

  function submitMCReport()
  {
    $("#frm_mc").submit();
  }

 function showPending()
 {
   $("#modalMC").modal('toggle');
   $("#tblMC").empty();
   $.getJSON( "{{ url('payroll/mc-pending/json/'.$mon.'/'.$yr) }}", function( datajson ) {
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                         $("#tblMC").append("<tr><td>"+obj+"</td></tr>");
                    });
            }).fail(function() {
            });
 }

 function processMC()
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
        $("#frm_mcproc").submit();
      }
    })
  }


 ///MAINE
 ///THEN ETO TINAWAG KO SA DATATABLE
$("#tbl").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                className: "bg-success",
                text: '<i class="fas fa-file-excel"></i> Export to Excel',
                title: 'MC export '+"{{ $mondesc }} "+"{{ $data['yr'] }}"
            }
        ]
    } );
$("#payroll_mon").val({{ $data['mon'] }});
$("#payroll_year").val({{ $data['yr'] }});


function showMC()
{
  var mon = $("#payroll_mon").val();
  var yr = $("#payroll_year").val();

  window.location.replace("{{ url('/payroll/mc') }}/"+mon +"/"+yr);
}
</script>
@endsection