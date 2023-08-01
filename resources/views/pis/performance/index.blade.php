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
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-dpcr" role="tab" aria-controls="tabs-dpcr" aria-selected="true">DPCR</a>
                      </li>

                      <!-- <li class="nav-item">
                        <a class="nav-link" id="tabs-non-degree-tab" data-toggle="pill" href="#tabs-saln" role="tab" aria-controls="tabs-saln-tab" aria-selected="false">SALN</a>
                      </li> -->

                      <!-- <li class="nav-item">
                        <a class="nav-link" id="tabs-studies-tab" data-toggle="pill" href="#tabs-studies" role="tab" aria-controls="tabs-studies-tab" aria-selected="false">Graduate Studies</a>
                      </li> -->

                    </ul>
                  </div>

              <div class="card-body">

                <div class="tab-content" id="custom-tabs-three-tabContent">

                  <div class="tab-pane fade show active" id="tabs-dpcr" role="tabpanel" aria-labelledby="tabs-dpcr-tab">
                      <button class="btn btn-primary float-right" onclick="modalSubmission('ipcr')"><i class="fas fa-bullhorn"></i> Call for Submission</button>
                    <br>
                    <br>
                    <table id="tbl" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Year</th>
                        <th>Period</th>
                        <th>Division</th>
                        <th style="width: 5%"></th>
                      </tr>
                      </thead>
                      <tbody>
                            @foreach($data['dpcr'] AS $dpcrs)
                                <tr>
                                  <td></td>
                                  <td>{{ $dpcrs->dpcr_year }}</td>
                                  <td>{{ $dpcrs->dpcr_period }}</td>
                                  <td>{{ countDPCR($dpcrs->dpcr_year,$dpcrs->dpcr_period,$dpcrs->dpcr_deadline) }}</td>
                                  <td><center><i class="fas fa-eye" style="cursor: pointer" onclick="showList({{ $dpcrs->dpcr_year }},'{{ $dpcrs->dpcr_period }}')"></i> view</center></td>
                                </tr>
                            @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="tab-pane fade" id="tabs-saln" role="tabpanel" aria-labelledby="tabs-saln-tab">
                    <button class="btn btn-primary float-right" onclick="modalSubmission('saln')"><i class="fas fa-bullhorn"></i> Call for Submission</button>
                    <br>
                    <br>
                     

                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
  </div>
</div>

<div class="modal fade" id="modal-submission">
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
                <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('performance/ipcr/create') }}">   -->
                {{ csrf_field() }}
                <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
                <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">
                    <div class="row option-div" id="option-ipcr">
                      <div class="col-12">
                          <strong>For the Year</strong><br>
                          <input type="number" class="form-control frm-input" name="ipcr_year">
                          <br>
                          <strong>Period</strong><br>
                          <select class="form-control" name="ipcr_period" id="ipcr_period">
                            <option value="January-June">January-June</option>
                            <option value="July-December">July-December</option>
                          </select>
                          <br>
                          <strong>Deadline</strong><br>
                          <input type="date" class="form-control frm-input" name="ipcr_deadline">
                          <br>
                      </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submitFrm()">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-dpcr">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">DPCR</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <strong>Year : </strong> <span id="dpcr_year_text"></span><br/>
                    <strong>Period : </strong> <span id="dpcr_period_text"></span><br/>
                  </div>
                </div>
                <table class="table table-condensed table-sm table-bordered">
                  <thead>
                    <th style="width: 5%"><center>#</center></th>
                    <th><center>Division</center></th>
                    <th><center>Rating</center></th>
                    <th><center>Date Submitted</center></th>
                    <th><center>IPCR</center></th>
                    <th><center>File</center></th>
                  </thead>
                  <tbody id="tbdy"></tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

function modalSubmission(opt)
{
    $("#modal-submission").modal('toggle');

    switch(opt)
    {
        case "ipcr":
            $("#modalOption").modal('toggle');
            $(".modal-title").text("IPCR");
            $("#frm_url_reset").val("{{ url('performance/index') }}");
            $("#frm_url_action").val("{{ url('performance/dpcr/create') }}");
        break;
    }
}

function submitFrm()
{
  $("#frm").submit();
}

function showList(yr,period)
{
  $("#tbdy").empty();
    $.getJSON( "{{ url('performance/dpcr/json') }}/"+yr+"/"+period, function( datajson ) {
                
              }).done(function(datajson) {
                var ctr = 1;
                jQuery.each(datajson,function(i,obj){
                        $("#tbdy").append("<tr><td align='center'>"+ctr+"</td><td>"+obj.division_acro+"</td><td align='center'>"+obj.dpcr_score+"</td><td align='center'>"+obj.submitted_at+"</td><td align='center'>"+obj.submitted+"/"+obj.total+"</td><td align='center'><a href='../../storage/app/"+obj.dpcr_file_path+"' target='_blank'><i class='fas fa-download'></i></a></td></tr>"); 
                    });
            }).fail(function() {
            });

    $("#dpcr_year_text").text(yr);
    $("#dpcr_period_text").text(period);
    $("#modal-dpcr").modal("toggle");
}
</script>

@endsection