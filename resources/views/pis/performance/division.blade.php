@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<h1>Performance Management</h1>
<div class="row">

  <div class="col-12">
          <div class="card card-primary card-outline card-outline-tabs">

                  <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-hrdplan" role="tab" aria-controls="tabs-hrdplan" aria-selected="true">IPCR</a>
                      </li>

                      <!-- <li class="nav-item">
                        <a class="nav-link" id="tabs-non-degree-tab" data-toggle="pill" href="#tabs-non-degree" role="tab" aria-controls="tabs-non-degree-tab" aria-selected="false">Non-Degree</a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" id="tabs-studies-tab" data-toggle="pill" href="#tabs-studies" role="tab" aria-controls="tabs-studies-tab" aria-selected="false">Graduate Studies</a>
                      </li> -->

                    </ul>
                  </div>

              <div class="card-body">

                <div class="tab-content" id="custom-tabs-three-tabContent">

                  <div class="tab-pane fade show active" id="tabs-hrdplan" role="tabpanel" aria-labelledby="tabs-hrdplan-tab">
                    <br>
                    <table id="tbl" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Staff</th>
                        <th>Ave Rating</th>
                        <th>Year</th>
                        <th>Period</th>
                        <th style="width: 5%"></th>
                      </tr>
                      </thead>
                      <tbody>
                            @foreach($data['dpcr'] AS $dpcrs)
                                <tr>
                                  <td></td>
                                  <td>{{ getStaffInfo($dpcrs->user_id) }}</td>
                                  <td>{{ $dpcrs->ipcr_score }}</td>
                                  <td>{{ $dpcrs->dpcr_year }}</td>
                                  <td>{{ $dpcrs->dpcr_period }}</td>
                                  <td><center><a href="{{ asset('../storage/app/'.$dpcrs->ipcr_file_path) }}" target="_blank"><i class="fas fa-download"></i></a></center></td>
                                </tr>
                            @endforeach
                      </tbody>
                    </table>
                    <br>
                    <br>
                    <p style="color: red"><i>*Please be reminded that by submitting this form, you are affixing your signature.</i></p>
                    <span class="float-right"><button class="btn btn-info" onclick="uploadDPCR()">SUBMIT DPCR</button></span>
                  </div>

                  <div class="tab-pane fade" id="tabs-non-degree" role="tabpanel" aria-labelledby="tabs-non-degree-tab">

                     

                  </div>

                  <div class="tab-pane fade" id="tabs-studies" role="tabpanel" aria-labelledby="tabs-studies-tab">
                     
                     

                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
  </div>
</div>

<div class="modal fade" id="modal-dpcr">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frm" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('performance/dpcr/submit') }}">
                    <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('/') }}">
                    <div class="row">
                        <div class="col-12">
                            <strong>Upload DPRC</strong>
                            <div class="form-group">
                            <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="files_dpcr_file" id="customFile">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <strong>Ave Rating</strong>
                            <div class="form-group">
                                <input type="text" class="form-control" name="dpcr_score" id="dpcr_score">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submitDPCR()">Submit</button>
            </div>
        </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>
</div>



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
$(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

// function submitDPCR(opt)
// {
//     $("#modal-submission").modal('toggle');

//     switch(opt)
//     {
//         case "ipcr":
//             $("#modalOption").modal('toggle');
//             $(".modal-title").text("IPCR");
//             $("#frm_url_reset").val("{{ url('performance/index') }}");
//             $("#frm_url_action").val("{{ url('performance/dpcr/create') }}");
//         break;
//     }
// }
function uploadDPCR()
{
  $("#modal-dpcr").modal('toggle');
}
function submitDPCR()
{
  $("#frm").submit();
}
</script>

@endsection