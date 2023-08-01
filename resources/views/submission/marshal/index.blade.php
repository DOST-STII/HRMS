@extends('template.master')

@section('CSS')
<style type="text/css">
  .inactive_text
  {
    color:#999;
  }

  .active_text
  {
    color:#222;
    font-weight: bold;
  }
</style>
@endsection

@section('content')

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">CALL FOR SUBMISSION</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table" id="tbl">
                <thead>
                  <th style="width: 2%">#</th>
                  <th>Description</th>
                  <th>Remarks</th>
                  <th>Deadline</th>
                  <th style="width: 10%" class="text-center">Attachment</th>
                  <th style="width: 10%"></th>
                </thead>
                <tbody>
                  @foreach($data['list'] AS $lists)
                    <tr>
                      <td></td>
                      <td>{{ $lists->sub_report }}</td>
                      <td>{{ $lists->sub_remarks }}</td>
                      <td>{{ $lists->sub_deadline }}</td>
                      <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->submission_file) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                      <td>
                        @if(isset($lists->submission_file))
                          <button class="btn btn-success btn-sm" onclick="modalFunction('update',{{ $lists->submission_list_id }},'{{ $lists->sub_report }}')"><i class="fas fa-file"></i> update file</button>
                        @else
                          <button class="btn btn-primary btn-sm" onclick="modalFunction('upload',{{ $lists->submission_list_id }},'{{ $lists->sub_report }}')"><i class="fas fa-file"></i> upload file</button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>


      <div class="modal fade" id="modal-upload">
        <div class="modal-dialog" id="modal-size">
          <div class="modal-content">

            <div class="overlay d-flex justify-content-center align-items-center" id="overlay_action" style="display: none !important">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>

            <div class="modal-header">
              <h4 class="modal-title"><i id="modal-icon" class="fas fa-file"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
              <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('invitation/create') }}">   -->
              {{ csrf_field() }}
              <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('submission-list/update') }}">
              <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('submission-list/division') }}">
              <input type="hidden" name="tblid" id="tblid" value="">
              <input type="hidden" name="report_type" id="report_type" value="">

              <div class="form-group">
              <div class="custom-file">
                      <input type="file" class="custom-file-input" name="submission_file" id="customFile">
                      <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
@endsection

@section('JS')

<script>

  $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

  function modalFunction(type,id,report_type)
  {
    $("#tblid").val(id);
    $("#report_type").val(report_type);
    $('#modal-upload').modal({
            toggle: true,
            backdrop: 'static',
            keyboard: false
            });

    switch(type)
    {
      case "upload":
        $("#modal-title").text("UPLOAD FILE");
      break;

      case "update":
        $("#modal-title").text("UPDATE FILE");
      break;
    }
  }
</script> 
@endsection