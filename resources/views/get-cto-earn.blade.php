<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CTO EARN</title>
  </head>
  <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <body style="background-color:black">
    <div class="container" style="margin-top:20px">
      <div class="row">
        <div class="col-12">   
        <!-- STACKED BAR CHART -->
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title"><b>CTO EARN</b></h3>       
                  <div class="card-tools">                                 
                    <div class="row">     
                      <div class="col-md-3">                 
                        <select class="form-control" name="division" id="division">
                            @foreach (getDivisionList() as $item)
                                <option value="{{ $item->division_id }}">{{ $item->division_acro }}</option>
                            @endforeach
                        </select>              
                      </div>
                      <div class="col-md-3">                 
                        <select class="form-control" name="yr" id="yr">
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                        </select>              
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">  
                          <table id="tbl" class="table table-bordered" style="font-size: 13px; width:100%">                               
                            <thead style='font-weight: bold; font-size:15px;'>
                              </tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Minutes</th>
                                <th>CTO Earn</th>
                                <th style="width: 30%">Day Desc/Purpose</th>
                              </tr>
                            </thead>
                            @foreach($data['list'] AS $lists)
                                <tr>    
                                    <td>{{ $lists->employee_name }}</td>
                                    <td>{{ $lists->ot_date }}</td>
                                    <td>{{ $lists->ot_in }}</td>
                                    <td>{{ $lists->ot_out }}</td>
                                    <td>{{ $lists->ot_min }}</td>
                                    <td>{{ $lists->cto_earn }}</td>
                                    <td>
                                        <?php
                                            $dayDesc = weekDesc($lists->ot_date);
                                            if($dayDesc == 'Sat' || $dayDesc == 'Sun')
                                            {
                                                $dayDesc = "Weekend<br>";
                                            }
                                            else
                                            {
                                                if(checkIfHoliday($lists->ot_date))
                                                {
                                                    $dayDesc = "Holiday<br>";
                                                }
                                                else
                                                {
                                                    $dayDesc = null;
                                                }
                                                
                                            }
                                            
                                            echo $dayDesc."".$lists->ot_purpose;
                                        ?>
                                    </td>
                                </tr> 
                            @endforeach
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
    $(document).ready(function(){
        $("#tbl").DataTable();
        $("#division").val('{{ $data['division'] }}');
        $("#yr").val('{{ $data['year'] }}');
    })

    $("#division,#yr").change(function(){
        var div = $("#division").val();
        var yr = $("#yr").val();
        window.location.href = "{{ url('get-cto-earn') }}/"+div+"/"+yr;
    })
  </script>
</html>