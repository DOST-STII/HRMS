<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>MONITOR WEEKLY SCHEDULE</title>
</head>
<link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
<body>
  <div class="container" style="margin-top:20px">
    <div class="row">
      <div class="col-12">   
      <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>MONITOR WEEKLY SCHEDULE</b></h3>
     
                </h3>
                <div class="card-tools">
                  <div class="float-right" style="margin-right: 1%">

                    <select class="form-control-sm" name="sched_year" id="sched_year" onchange="showSCHED()">
                      <?php
                        for ($i = date('Y'); $i <= (date('Y') + 2) ; $i++) { 
                            echo "<option value='$i'>".$i."</option>";
                        }
                      ?>
                    </select>

                  </div>

                  <div class="float-right" style="margin-right: 1%">
                    <select class="form-control-sm" name="sched_mon" id="sched_mon" onchange="showSCHED()">
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

                  <div class="float-right" style="margin-right: 1%">
                    <select class="form-control-sm" id="sched_wk" name="sched_wk" onchange="showSCHED()">
                        <?php
                          $weeknum = getWeekMonth('total',null,$data['mon'],$data['yr']);
                          for ($i=1; $i < $weeknum; $i++) { 
                            echo "<option value='".$i."'>Week ".$i."</option>";
                          }
                        ?>
                    </select>
                  </div>

                  <div class="float-right" style="margin-right: 1%">
                    <select class="form-control-sm" id="sched_div" name="sched_div" onchange="showSCHED()">
                        @foreach(getDivisionList() AS $list)
                            <option value='{{ $list->division_id }}'>{{ $list->division_acro }}</option>
                        @endforeach
                    </select>
                  </div>

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
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>1</b></td><td align="center" ><b>2</b></td><td align="center" ><b>3</b></td><td align="center" ><b>4</b></td>';
                                      break;

                                    case 'Wed':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>1</b></td><td align="center" ><b>2</b></td><td align="center" ><b>3</b></td>';
                                      break;

                                    case 'Thu':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>1</b></td>';
                                      break;

                                    case 'Fri':
                                        $d = '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>1</b></td><td align="center" ><b>2</b></td>';
                                      break;
                                    
                                    default:
                                        $d = '<td align="center" ><b>1</b></td><td align="center" ><b>2</b></td><td align="center" ><b>3</b></td><td align="center" ><b>4</b></td><td align="center" ><b>5</b></td>';
                                      break;
                                  }
                            echo $d;
                          }
                          else
                          {

                            foreach(getWeekMonth('week',$data['weeknum'],$data['mon'],$data['yr']) AS $weeks)
                                {
                                    echo '<td align="center"  style="cursor: pointer;"><b>'.$weeks.'</b></td>';
                                }
                          }
                        ?>
                      </tr>

                      
                        <?php
                          if($data['weeknum'] == 1)
                          {
                            foreach(getAllStaffDivision3($data['division']) AS $lists)
                            {
                              echo "<tr><td>".$lists->lname.", ".$lists->fname." ".$lists->mname."</td>";
                                  $dayDesc = weekDesc($data['yr']."-".$data['mon']."-1");

                                  switch ($dayDesc) {
                                    case 'Tue':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-4') .'</b></td>';
                                      break;

                                    case 'Wed':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td>';
                                      break;

                                    case 'Thu':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td>';
                                      break;

                                    case 'Fri':
                                        echo '<td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>-</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td>';
                                      break;
                                    
                                    default:
                                        echo '<td align="center" ><b>'.getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-1') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-2') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-3') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-4') .'</b></td><td align="center" ><b>'. getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-5') .'</b></td>';
                                      break;
                                  }
                            }
                            echo "</tr>";
                          }
                          else
                          {
                            foreach(getAllStaffDivision3($data['division']) AS $lists)
                            {
                                echo "<tr><td>".$lists->lname.", ".$lists->fname." ".$lists->mname."</td>";
                                foreach(getWeekMonth('week',$data['weeknum'],$data['mon'],$data['yr']) AS $weeks)
                                {
                                  echo '<td align="center"><b>'.getWeekSchedStaff2($lists->id,$data['yr'].'-'.$data['mon'].'-'.$weeks).'</b></td>';
                                }
                            }
                            echo "</tr>";
                          }
                        ?>
                 </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
      </div>
    </div>
  </div>
</body>

<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
<script>
$("#sched_mon").val({{ $data['mon'] }});
$("#sched_year").val({{ $data['yr'] }});
$("#sched_wk").val({{ $data['weeknum'] }});
$("#sched_div").val("{{ $data['division'] }}");


function showSCHED()
{
  mon = $("#sched_mon").val();
  yr = $("#sched_year").val();
  wk = $("#sched_wk").val();
  div = $("#sched_div").val();


  window.location.replace("{{ url('schedule-monitor') }}/"+mon+"/"+yr+"/"+wk+"/"+div);
}
</script>

</html>