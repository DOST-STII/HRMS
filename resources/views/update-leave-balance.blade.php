<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>UPDATE LEAVE BALANCE</title>
  </head>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('fontawesome-free-5.12.1-web/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style type="text/css">
        #overlay {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 9998;
        cursor: pointer;
        }

        .spinner {
    width: 300px;
    height: 300px;
    position: absolute;
    left: 50%;
    top: 50%; 
    margin-left: -50px;
    margin-top: -100px;
    z-index: 9999;
}
  </style>
  <body style="background-color:black">
  <div id="overlay"></div>
    <div class="container-fluid" style="margin-top:20px">
      <div class="row">
        <div class="col-12">   
        <!-- STACKED BAR CHART -->
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title"><b>UPDATE LEAVE BALANCE <select class="form-control" id="slctyr" name="slctyr" style="width: 200px;"><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option></select></b></h3>       
                  <div class="card-tools">                                 
                    <div class="row">     
                      <div class="col-md-3">                           
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">  
                          <table class="table table-bordered" style="font-size: 13px; width:100%">                               
                            <thead style='font-weight: bold; font-size:15px;'>
                              </tr>
                                <th style="width: 10%;">Name</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>May</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Aug</th>
                                <th>Sept</th>
                                <th>Oct</th>
                                <th>Nov</th>
                                <th>Dec</th>
                              </tr>
                            </thead>                                     
                            @foreach($data['list'] AS $lists)
                                <tr>    
                                    <td>{{ $lists->lname.", ".$lists->fname }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,1,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,1,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,2,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,2,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,3,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,3,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,4,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,4,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,5,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,5,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,6,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,6,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,7,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,7,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,8,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,8,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,9,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,9,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,10,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,10,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,11,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,11,$data['yr']) }}</td>
                                    <td>LUD: {!! getDefUpdate($lists->id,12,$data['yr']) !!}<br>VL : {{ getLVUpdate($lists->id,12,$data['yr']) }}</td>
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
  <form method="POST" id="frm" enctype="multipart/form-data" role="form" action="{{ url('update-leave-balance-process-post') }}">  
    {{ csrf_field() }}
    <input type="hidden" name="userid" id="userid" value="">
    <input type="hidden" name="mh" id="mh" value="">
    <input type="hidden" name="my" id="my" value="{{$data['yr']}}">
  </form>
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
    $("#slctyr").val({{ $data['yr'] }});

    $("#slctyr").change(function(){
      window.location.href = "{{ url('update-leave-balance') }}/"+this.value;
    })

    function submitFrm(userid,mon)
    {
        $("#userid").val(userid);
        $("#mh").val(mon);
        $("#frm").submit();
        $("#overlay").show();
    }
  </script>
</html>