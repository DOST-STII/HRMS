<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>GSS</title>
  </head>
  <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <body>
    <div class="container" style="margin-top:20px">
      <div class="row">
        <div class="col-12">   
        <!-- STACKED BAR CHART -->
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title"><b>PCAARRD SHUTTLE SERVICE</b></h3>       
                  <div class="card-tools">                                 
                    <div class="row">     
                      <div class="col-md-3">                 
                        <input type="date" class="form-control" name="date" id="date" onchange="showSCHED()">                
                      </div>
                      <div class="col-md-6">                
                        <?php 
                          $date = $data['date'];
                          $completedate = date('l, F d, Y', strtotime($date)); 
                          $count=getStaffSchedCount($date);
                          echo "Total number of staff availing the shuttle service for the day: $count";
                        ?>
                      </div> 
                    </div>
                  </div>
                </div>
                <div class="card-body">  
                          <table class="table table-bordered" style="font-size: 13px; width:100%">                               
                            <thead style='font-weight: bold; font-size:15px;'>
                              <tr>
                                <th colspan="4"><h5><b><center>{{ $completedate }}</b></center></h5></th>
                              <tr>
                              </tr>
                                <th>Name</th>
                                <th>Division</th>
                                <th>Pickup Place</th>
                                <th>CP Number</th>
                              </tr>
                            </thead>                                     
                              <?php    
                                if($data['date'])
                                {        
                                                   
                                  foreach(getStaffSched2($date) AS $list)
                                  {
                                    $user = App\User::where('id',$list->user_id)->first();  
                                    if(isset($user))
                                    {
                                      $pickup = "";
                                      if($user->pickup != null)
                                      {
                                        $pickup = $user->pickup;
                                      }
                                      $cellnum = "";
                                      if($user->cellnum != null)
                                      {                                   
                                        $cellnum = $user->cellnum;
                                      }
                                      echo "<tr>
                                              <td>".ucwords(strtolower($user['lname'].", ".$user['fname']." ".$user['mname']))."</td>
                                              <td style='width:12%'>".$list['division_acro']."</td>
                                              <td style='width:50%'>".$pickup."</td>
                                              <td style='width:13%'>".$cellnum."</td>
                                            </tr>";    
                                          // break;                                 
                                      }                                    
                                  }
                                }                        
                            ?>
                          </table>
                        {{-- </td>
                      </tr>
                    </tbody>
                  </table> --}}
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
    $(".table").DataTable( {
          dom: 'Bfrtip',
          buttons: [
              {
                  extend: 'excelHtml5',
                  className: "bg-success",
                  text: '<i class="fas fa-file-excel"></i> Export to Excel',
                  title: "{{ $completedate }}"+' - Shuttle Service'
              }
          ]
      } );

    function showSCHED()
    {
      date = $("#date").val();
      window.location.replace("{{ url('gss2') }}/"+date);
    }
  </script>
</html>