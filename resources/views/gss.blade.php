<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>GSS</title>
</head>
<link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
<body>
  <div class="container" style="margin-top:20px">
    <div class="row">
      <div class="col-12">   
      <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>PCAARRD SHUTTLE SERVICE</b></h3>
              
                  
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
                </div>
              </div>
              <div class="card-body">
                <h5><b><center>{{ date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'] }}</b></center></h5>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left;width: 12%;">Day</th><th>Staff</th>
                  </thead>
                  <tbody>
                    <?php
                      $mon2 = date('F',mktime(0, 0, 0, $data['mon'], 10));
                      $date = $mon2  ."-" . $data['yr'];

                      $total = Carbon\Carbon::parse($date)->daysInMonth;
                      $prevweek = 1;

                      $week_num = 2;

                      echo "<tr><td></td><td align='center' style='font-size:20px'>  <b>WEEK 1 </b> </td></tr>";
                      for($i = 1;$i <= $total;$i++)
                        {
                          $weeknum = weekOfMonth($data['yr'].'-'.$data['mon'].'-'.$i);
                          if($weeknum == $prevweek)
                          {
                            
                          }
                          else
                          {
                            $prevweek = $weeknum;
                            echo "<tr><td></td><td align='center' style='font-size:20px'> <b>WEEK $week_num </b> </td></tr>";
                            $week_num++;
                            // $wkn = $data['week'] + 1;
                          }

                          // $dtr_date = $data['yr'].'-'.$data['mon'].'-'.$i;

                            $dayDesc = weekDesc(date($data['yr'].'-'.$data['mon'].'-'.$i));
                            $dt = $data['yr'].'-'.$data['mon'].'-'.$i;

                            // $dtr_date2 = date("Y-m-d",strtotime($dtr_date));
                            echo "<tr><td align='left' style='font-size:20px'> <b>$i <span class='text-right'>$dayDesc</span></b> </td><td><table class='table' style='width:100%'>";

                            

                            foreach(getStaffSched($dt) AS $list)
                            {
                              $user = App\User::where('id',$list->userid)->first();

                              if(isset($user))
                              {
                                $pickup = "";
                                if($user['pickup'] != null)
                                {
                                  $pickup = $user['pickup'];
                                }

                                $cellnum = "";
                                if($user['cellnum'] != null)
                                {
                                  $cellnum = $user['cellnum'];
                                }
                                
                                echo "<tr><td>".ucwords(strtolower($user['lname'].", ".$user['fname']." ".$user['mname']))."</td><td style='width:33%'>".$pickup."</td><td style='width:33%'>".$cellnum."</td></tr>";
                                }
                              
                            }

                            echo "</table></td></tr>";
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
  </div>
</body>

<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('bootstrap4/js/bootstrap.min.js') }}"></script>
<script>
$("#dtr_mon").val({{ $data['mon'] }});
$("#dtr_year").val({{ $data['yr'] }});


function showDTR()
{
  mon = $("#dtr_mon").val();
  yr = $("#dtr_year").val();
  window.location.replace("{{ url('gss') }}/"+mon+"/"+yr);
}
</script>

</html>