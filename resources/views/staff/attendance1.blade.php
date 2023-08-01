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
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                <p><b>{{ mb_strtoupper(Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->mname) }}</b></p>
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
              <div id="modalprint" class="card-body"> 
                <form id="frm_print">
                <center>
                <h4><b>Daily Time Record (DTR)</b></h4>
                <h5><b>{{ date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'] }}</b></h5>
                </center>
                
                <style type="text/css">
                  p {margin: 0; padding: 0;}	
                  .ft10{font-size:20px;font-family:Helvetica;color:#000000;}
                  .ft11{font-size:12px;font-family:Helvetica;color:#000000;}
                  .ft12{font-size:11px;font-family:Helvetica;color:#000000;}
                  .ft13{font-size:8px;font-family:Helvetica;color:#000000;}
                  .ft14{font-size:10px;font-family:Helvetica;color:#000000;}
                  .ft15{font-size:10px;font-family:Times;color:#000000;}
                </style>
              
                <table class="table" style="font-size: 12px; width: 100%; padding: -1px;">
                  <thead style="text-align: center">
                  <tr>
                    <td colspan="7" align="left">
                      <p class="ft11">Employee Number: 23-01</p>
                      <p class="ft11">Employee Name &#160;: Francisco, Chester Galera</p>
                      <p class="ft11">Position &#160; &#160; &#160; : Science Research Specialist II</p>
                      <p class="ft11">Group &#160; &#160; &#160; &#160; &#160;: IT Unit</p>
                    </td>
                    <td colspan="7" align="left">
                      <p class="ft11">Month/Year &#160; &#160; : July, 2023</p>
                      <p class="ft11">Official Time &#160;: 07:00:00 am &#160;- &#160;05:00:59 pm</p>
                    </td>
                  </tr>

                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th>
                    <th>OT In</th><th>OT Out</th><th style="width:30%">Remarks</th>
                  </thead>
                  <tbody>
                  
                    <?php
                      $emp = App\User::where('id',Auth::user()->id)->first();

                      $mon = date('m');
                      $yr = date('Y');

                      if($data['mon'])
                      {
                        $mon = $data['mon'];
                        $yr = $data['yr'];
                      }

                      $mon2 = date('F',mktime(0, 0, 0,$mon, 10));
                      $date = $mon2  ."-" . $yr;
                    
                      $total = Carbon\Carbon::parse($date)->daysInMonth;
                      $prevweek = 1;
                      $week_num = 2;

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

                      $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);

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

                        //    //CHECK IF HAS HOLIDAY HEHEHE
                          if(!checkIfHoliday($dtr_date))
                              {

                               $amIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",1,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamIn'])."</div></td>";
                               $amOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",2,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamOut'])."</div></td>";
                               $pmIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",3,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmIn'])."</div></td>";
                               $pmOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",4,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmOut'])."</div></td>";
                               $otIn = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",5,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRotIn'])."</div></td>";
                               $otOut = "<td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",6,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRotOut'])."</div></td>";

                               $remarks = "<td colspan='6' align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$emp['id'].",7,".$yr.",".$mon.",".$i.")'>".$dtr['dtr_remarks']."</td>";

                              $dtrcol = $amIn."".$amOut."".$pmIn."".$pmOut."".$otIn."".$otOut."".$remarks;
                            
                              // //CHECK IF WFH
                              // if(checkIfDTR('check',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']) > 0)
                              //     {
                              //       $wfh = checkIfDTR('list',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']);
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
                              //     if(checkIfDTR('check',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']) > 0)
                              //       {
                              //       $wfh = checkIfDTR('list',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']);
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
                </form>
              </div>
             
              <!-- /.card-body -->            

              <div class="card-footer">
                <center><button class="btn btn-primary" onclick="showPrint()"><i class="fas fa-print"></i> Print DTR</button></center>
              </div>
            </div>
            <!-- /.card -->
  </div>
</div>
<input type="hidden" id="userid" value="{{ $emp['id'] }}">

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
$("#dtr_mon,#mon2").val({{ $mon }});
$("#userid,#userid2").val({{ $emp['id'] }});
$("#dtr_year,#yr2").val({{ $yr }});


function showDTR()
{
  mon = $("#dtr_mon").val();
  yr = $("#dtr_year").val();
  userid = $("#userid").val();

  $("#overlay").show();

  window.location.replace("{{ url('staff/attendance') }}/"+mon+"/"+yr+"/"+userid);
}

function printDTR()
{
  $("#frm_print").submit();
}

function showPrint() {
  // Toggle the modal
  $("#modalprint").modal('toggle');

  // Call the print function after a short delay to ensure the modal is visible
  setTimeout(function() {
    printForm();
  }, 500);
}

function printForm() {
  // Get the table element
  var table = document.querySelector("#frm_print table");

  // Open a new window to print the table
  var printWindow = window.open('', '_blank');

  // Write the table's HTML to the new window
  printWindow.document.write('<html><head><title>Print DTR</title>');
  printWindow.document.write('<style>@media print { table { border-collapse: collapse; }');
  printWindow.document.write('table, th, td { padding: 2px; text-align: center; }');
  printWindow.document.write('h4 { text-align: center; margin-bottom: 10px; }</style></head><body>');
  printWindow.document.write('<h4><b>Daily Time Record (DTR)</b></h4>');
  printWindow.document.write(table.outerHTML);
  printWindow.document.write('</body></html>');

  // Call the print function of the new window
  printWindow.print();
  printWindow.close();
}



</script>
@endsection