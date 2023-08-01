<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>DTR REVERSAL</title>
</head>
<link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<body>
  <div class="container" style="margin-top:20px">
    <div class="row">
      <div class="col-12">   
      <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>DTR REVERSAL</b></h3>
            
                  
                </h3>
                <div class="card-tools">
                  <a href="{{ url('add-leave') }}" class="btn btn-success"> ADD PL/FL</a>
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered" id="tbl" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th>Employee</th><th style="text-align: left;width: 12%;">Month</th><th style="text-align: left;width: 12%;">Year</th><th></th>
                  </thead>
                  <?php
                  $dtr = App\DTRProcessed::whereIn('dtr_year',[2021,2020,2019])->orderBy('dtr_mon','DESC')->orderBy('dtr_year','DESC')->orderBy('empcode','ASC')->get();
                  
                  foreach ($dtr as $key => $value) {

                    $user = App\User::where('id',$value->userid)->first();

                    if($user)
                    {
                      //IF MONTH HAS NO FUTURE MONTH ENABLE DELETE
                      $mon = $value->dtr_mon;
                      $yr = $value->dtr_year;

                      if($mon == 12)
                      {
                        $mon = 1;
                        $yr = $value->dtr_year + 1;
                      }
                      else
                      {
                        ++$mon;
                      }

                      $dtr2 = App\DTRProcessed::where('userid',$value->userid)->whereIn('dtr_year',[2021,2020,2019])->where('dtr_mon',$mon)->where('dtr_year',$yr)->first();

                      $del = "";
                      if(!$dtr2)
                      {
                        $del = "<a href='".url('delete/dtr-process/'.$value->id)."' class='text-danger'><b>delete</b>";
                      }

                      echo "<tr>
                              <td>".$user['lname'].','.$user['fname'].' '.$user['mname']."</td>    
                              <td>".$value->dtr_mon."</td>    
                              <td>".$value->dtr_year."</td> 
                              <td align='center'>".$del."</td>   
                          </tr>";
                    }
                    
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
  $("#tbl").DataTable();
</script>

</html>