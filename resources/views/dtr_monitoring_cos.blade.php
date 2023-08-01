<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DTR MONITORING</title>
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
                  <h3 class="card-title"><b>DTR MONITORING</b></h3>       
                  <div class="card-tools">                                 
                    <div class="row">     
                      <div class="col-md-3">                 
                        <input type="date" class="form-control" name="dateinput" id="dateinput" value="{{ $data['dt'] }}">                
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">  
                          <table class="table table-bordered" style="font-size: 13px; width:100%">                               
                            <thead style='font-weight: bold; font-size:15px;'>
                              </tr>
                                <th>Name</th>
                                <th>Division</th>
                                <th>AM-In</th>
                                <th>AM-Out</th>
                                <th>PM-In</th>
                                <th>PM-Out</th>
                              </tr>
                            </thead>                                     
                            @foreach($data['list'] AS $lists)
                                <tr>    
                                    <td>{{ $lists->employee_name }}</td>
                                    <td>{{ $lists->division }}</td>
                                    <td>{{ $lists->fldEmpDTRamIn }}</td>
                                    <td>{{ $lists->fldEmpDTRamOut }}</td>
                                    <td>{{ $lists->fldEmpDTRpmIn }}</td>
                                    <td>{{ $lists->fldEmpDTRpmOut }}</td>
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
    $("#dateinput").change(function(){
        window.location.href = "{{ url('dtr/monitoring/J6JblRa9yz-COS') }}/"+this.value;
    })
  </script>
</html>