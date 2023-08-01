<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Workforce Monitoring</title>
  </head>
  <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
  <body>
    <div class="container" style="margin-top:20px">
      <div class="row">
        <div class="col-12">   
        <!-- STACKED BAR CHART -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title"><b>PCAARRD Workforce Monitoring</b></h3>
              <div class="card-tools">                                 
                <div class="row">     
                  <div class="col-md-3">                 
                    <input type="date" class="form-control" name="date" id="date" onchange="showWorkforce()">                
                  </div>
                  <?php 
                    $date = $data['date'];
                    $completedate = date('l, F d, Y', strtotime($date)); 
                  ?>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered wf_table">
                <thead class="tophead">
                  <tr>
                    <h5><b><center>{{ $completedate }}</b></center></h5>
                  </tr>
                  <tr align="center">	                    		
                    <th rowspan="2" class="items">Location</th>
                    <th nowrap colspan="3" scope="colgroup" class="amounts">Staff</th>
                    <th rowspan="2" class="amounts">TOTAL %</th>                      
                  </tr>
                  <tr>
                    <th>Regular</th>
                    <th>ICOS</th>
                    <th>Project</th>
                  </tr>   
                </thead>                       
                <tbody>
                  <?php 
                    // $main_reg_ofc=getMain_Reg_Ofc($date);
                    // $main_icos_ofc=getMain_ICOS_Ofc($date);
                    // $main_proj_ofc=getMain_Proj_Ofc($date);

                    // $dpitc_reg_ofc=getDPITC_Reg_Ofc($date);
                    // $dpitc_icos_ofc=getDPITC_ICOS_Ofc($date);
                    // $dpitc_proj_ofc=getDPITC_Proj_Ofc($date);

                    $main_reg_all=getMain_Reg_All($date);
                    $main_icos_all=getMain_ICOS_All($date);
                    $main_proj_all=getMain_Proj_All($date);

                    $dpitc_reg_all=getDPITC_Reg_All($date);
                    $dpitc_icos_all=getDPITC_ICOS_All($date);
                    $dpitc_proj_all=getDPITC_Proj_All($date);

                    $main_reg_sched=getMain_Reg_Sched($date);
                    $main_icos_sched=getMain_ICOS_Sched($date);
                    $main_proj_sched=getMain_Proj_Sched($date);

                    $dpitc_reg_sched=getDPITC_Reg_Sched($date);
                    $dpitc_icos_sched=getDPITC_ICOS_Sched($date);
                    $dpitc_proj_sched=getDPITC_Proj_Sched($date);

                    // $main_ofc=$main_reg_ofc+$main_icos_ofc+$main_proj_ofc;                    
                    // $dpitc_ofc=$dpitc_reg_ofc+$dpitc_icos_ofc+$dpitc_proj_ofc;                    
                    // $total_ofc=$main_ofc+$dpitc_ofc;

                    // $total_reg_ofc=$main_reg_ofc+$dpitc_reg_ofc;
                    // $total_icos_ofc=$main_icos_ofc+$dpitc_icos_ofc;
                    // $total_proj_ofc=$main_proj_ofc+$dpitc_proj_ofc;

                    $main_all=$main_reg_all+$main_icos_all+$main_proj_all;                    
                    $dpitc_all=$dpitc_reg_all+$dpitc_icos_all+$dpitc_proj_all;                    
                    $total_all=$main_all+$dpitc_all;

                    $total_reg_all=$main_reg_all+$dpitc_reg_all;
                    $total_icos_all=$main_icos_all+$dpitc_icos_all;
                    $total_proj_all=$main_proj_all+$dpitc_proj_all;

                    $main_sched=$main_reg_sched+$main_icos_sched+$main_proj_sched;
                    $dpitc_sched=$dpitc_reg_sched+$dpitc_icos_sched+$dpitc_proj_sched;
                    $total_sched=$main_sched+$dpitc_sched;

                    $total_reg_sched=$main_reg_sched+$dpitc_reg_sched;
                    $total_icos_sched=$main_icos_sched+$dpitc_icos_sched;
                    $total_proj_sched=$main_proj_sched+$dpitc_proj_sched;

                    // $total_count_ofc=$total_reg_ofc+$total_icos_ofc+$total_proj_ofc;
                    $total_count_all=$total_reg_all+$total_icos_all+$total_proj_all;
                    $total_count_sched=$total_reg_sched+$total_icos_sched+$total_proj_sched;
                  ?>
                  <tr>
                    <td>Main Building</td> 
                    <td>{{ round(($main_reg_sched / $main_reg_all) * 100, 2)  }} %</td>                    
                    <td>{{ round(($main_icos_sched / $main_icos_all) * 100, 2)  }} %</td>    
                    @if($main_proj_all != 0)
                      <td> {{ round(($main_proj_sched / $main_proj_all) * 100, 2) }} % </td>  
                    @else
                      <td>-</td>  
                    @endif               
                    <td>{{ round(($main_sched / $main_all) * 100, 2) }} %</td>                    
                  </tr>
                  <tr>
                    <td>DPITC</td> 
                    <td>{{ round(($dpitc_reg_sched / $dpitc_reg_all) * 100, 2) }} %</td>        
                    @if($dpitc_icos_all != 0)
                      <td> {{ round(($dpitc_icos_sched / $dpitc_icos_all) * 100, 2)}} %</td>  
                    @else
                      <td>-</td>  
                    @endif                          
                    @if($dpitc_proj_all != 0)
                      <td> {{ round(($dpitc_proj_sched / $dpitc_proj_all) * 100, 2)}} %</td>  
                    @else
                      <td>-</td>  
                    @endif    
                    <td>{{ round(($dpitc_sched / $dpitc_all) * 100, 2) }} %</td>                         
                  </tr>
                  <tr>
                    <td>TOTAL %</td> 
                    <td>{{ round(($total_reg_sched / $total_reg_all) * 100, 2) }} %</td>                    
                    <td>{{ round(($total_icos_sched / $total_icos_all) * 100, 2) }} %</td>
                    @if($total_proj_all != 0)
                      <td> {{ round(($total_proj_sched / $total_proj_all) * 100, 2)}} %</td>  
                    @else
                      <td>-</td>  
                    @endif    
                    <td><b>{{ round(($total_count_sched / $total_count_all) * 100, 2) }} %</b></td>                         
                  </tr>    
                  <tr>
                    <td colspan="5"><b>Divisions/Sections</b></td>
                  </tr> 
                  <?php
                  foreach(getDivisionList() AS $list) {  
                    $list_reg = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '1')
                          ->where('sched_status', '!=', 'On-Leave')			
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();
                    $count_reg = $list_reg->count();
                    $list_icos = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '8')
                          ->where('sched_status', '!=', 'On-Leave')			
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();
                    $count_icos = $list_icos->count();
                    $list_proj = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '5')
                          ->where('sched_status', '!=', 'On-Leave')			
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();
                    $count_proj = $list_proj->count();

                    $list_reg_sched = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '1')
                          ->whereIn('sched_status',['Office','Pickup'])	
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();                    
                    $count_reg_sched = $list_reg_sched->count();
                    $list_icos_sched = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '8')
                          ->whereIn('sched_status',['Office','Pickup'])	
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();                       
                    $count_icos_sched = $list_icos_sched->count();  
                    $list_proj_sched = App\View_schedule::where('division',$list->division_id)
                          ->where('date',$date)
                          ->where('employment_id', '5')
                          ->whereIn('sched_status',['Office','Pickup'])	
                          ->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
                          ->groupBy("username")
                          ->get();                       
                    $count_proj_sched = $list_proj_sched->count();
                    $perdiv_sched=$count_reg_sched+$count_icos_sched+$count_proj_sched;
                    $perdiv_all=$count_reg+$count_icos+$count_proj;  
                  ?>
                    <tr>                 
                      <td>{{ $list->division_acro }}</td>                         
                      @if($count_reg != 0)
                        <td> {{ round(($count_reg_sched / $count_reg) * 100, 2)}} %</td>  
                      @else
                        <td>-</td>  
                      @endif 
                      @if($count_icos != 0)
                        <td> {{ round(($count_icos_sched / $count_icos) * 100, 2)}} %</td>  
                      @else
                        <td>-</td>  
                      @endif 
                      @if($count_proj != 0)
                        <td> {{ round(($count_proj_sched / $count_proj) * 100, 2)}} %</td>  
                      @else
                        <td>-</td>  
                      @endif                                
                      @if($perdiv_all != 0)
                        <td> {{ round(($perdiv_sched / $perdiv_all) * 100, 2)}} %</td>  
                      @else
                        <td>-</td>  
                      @endif               
                    </tr>
                  <?php
                    }
                  ?>           
                  <tr>
                    <td colspan="5"></td>
                  </tr>
                  <tr>
                    <td colspan="5"><b>Count of staff on workforce</b>&nbsp;<sub>*Based on set daily schedule</sub></td>
                  </tr>
                  <tr>
                    <td>Main Building</td>     
                    <td>{{ $main_reg_sched }}</td>                    
                    <td>{{ $main_icos_sched }}</td>                    
                    <td>{{ $main_proj_sched }}</td>                        
                    <td>{{ $main_sched }}</td>                      
                  </tr>
                  <tr>
                    <td>DPITC</td>            
                    <td>{{ $dpitc_reg_sched }}</td>                    
                    <td>{{ $dpitc_icos_sched }}</td>                  
                    <td>{{ $dpitc_proj_sched }}</td>                  
                    <td>{{ $dpitc_sched }}</td>                        
                  </tr>
                  <tr>
                    <td>Total</td> 
                    <td>{{ $total_reg_sched }}</td>                    
                    <td>{{ $total_icos_sched }}</td>                    
                    <td>{{ $total_proj_sched }}</td>                           
                    <td>{{ $main_sched+$dpitc_sched }}</td>                       
                  </tr>
                  <tr>
                    <td colspan="5"></td>
                  </tr>
                  <tr>
                    <td colspan="5"><b>Count of staff</b>&nbsp<sub>*As warm bodies</sub></td>
                  </tr>
                  <tr>
                    <td>Main Building</td>     
                    <td>{{ $main_reg_all }}</td>                    
                    <td>{{ $main_icos_all }}</td>                    
                    <td>{{ $main_proj_all }}</td>                        
                    <td>{{ $main_all }}</td>                       
                  </tr>
                  <tr>
                    <td>DPITC</td>            
                    <td>{{ $dpitc_reg_all }}</td>                    
                    <td>{{ $dpitc_icos_all }}</td>                  
                    <td>{{ $dpitc_proj_all }}</td>                  
                    <td>{{ $dpitc_all }}</td>                       
                  </tr>
                  <tr>
                    <td>Total</td> 
                    <td>{{ $total_reg_all }}</td>                    
                    <td>{{ $total_icos_all }}</td>                    
                    <td>{{ $total_proj_all }}</td>                           
                    <td>{{ $main_all+$dpitc_all }}</td>                       
                  </tr>                  
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
  {{-- <script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables/jquery.dataTables.min.js') }}"></script>
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
  <script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}
  <script>
    // $(".table").DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         {
    //             extend: 'excelHtml5',
    //             className: "bg-success",
    //             text: '<i class="fas fa-file-excel"></i> Export to Excel',
    //             title: "{{ $completedate }}"+' - Work Service'
    //         }
    //     ]
    // } );

    function showWorkforce()
    {
      date = $("#date").val();
      window.location.replace("{{ url('workforce-monitoring') }}/"+date);
    }
  </script>
</html>