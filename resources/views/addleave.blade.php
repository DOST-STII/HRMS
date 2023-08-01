<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ADD LEAVE</title>
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
                <h3 class="card-title"><b>ADD LEAVE</b></h3>
            
                  
                </h3>
                <div class="card-tools">
                  <a href="{{ url('revert-dtr') }}" class="btn btn-success">REVERSE DTR</a>
                </div>
              </div>
              <div class="card-body">
              <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" action="{{ url('add-leave-post') }}">  
                {{ csrf_field() }}    
                  <?php
                  $emp = App\User::whereIn('employment_id',[1,13,14,15])->orderBy('lname','ASC')->get();
                  
                  ?>
                  Employee : <br/><select class="form-control-sm" name="userid" id="userid" onchange="showDTR()">
                  @foreach($emp AS $divs)
                    
                      <option value='{{ $divs->id }}'>{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>

                  @endforeach
                </select>
                <br/>
                <br/>
                Privilege Leave : <input type="text" class="form-control" name="lv_pl" style="width: 25%;"><br/> 
                Force Leave : <input type="text" class="form-control" name="lv_fl" style="width: 25%;"><br/> 
                <br>

                <input type="submit" class="btn btn-primary">
              </form>
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