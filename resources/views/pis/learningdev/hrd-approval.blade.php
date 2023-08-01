@extends('template.master')

@section('CSS')

@endsection

@section('content')

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">HRD LIST</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered" id="tbl">
                <thead>
                  <th style="width: 2%">#</th>
                  <th style="width: 10%" class="text-center">Year</th>
                  <th style="width: 15%" class="text-center">File</th>
                  <th style="width: 15%" class="text-center">Status</th>
                  <th style="width: 5%"></th>
                </thead>
                <tbody>
                  @foreach($data['list'] AS $lists)
                    <tr>
                      <td></td>
                      <td class="text-center">{{ $lists->hrd_year }}</td>
                      <td class="text-center">{{ getFile('hrdc','',$lists->id) }}</td>
                      <td class="text-center">{{ $lists->hrd_status }}</td>
                      <td class="text-center">
                        @if($lists->received_at == null)
                          <button class="btn btn-primary btn-sm" onclick="modalOption('receive',{{ $lists->id }})"><i class="fas fa-check"></i> receive</button>
                        @endif
                      </td>
                    </tr>
                  @endforeach

                  @if(Auth::user()->division == 'O')

                  @foreach($data['hrd_oed_list'] AS $lists)
                    <tr>
                      <td></td>
                      <td class="text-center">{{ $lists->hrd_year }}</td>
                      <td class="text-center">{{ getFile('hrdc','',$lists->id) }}</td>
                      <td class="text-center">{{ $lists->hrd_status }}</td>
                      <td class="text-center">
                        <button class="btn btn-primary btn-sm" onclick="modalOption('upload-final',{{ $lists->id }})"><i class="fas fa-plus"></i></button>
                      </td>
                    </tr>
                  @endforeach

                  @endif
                  
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

      


<div class="modal fade" id="modalOption">
        <div class="modal-dialog">
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
                <input type="hidden" name="tbl_id" id="tbl_id" value="">
                <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
                <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">

                <div class="row" id="">
                    <div class="col-md-12">
                      <strong>Final HRD Plan</strong>
                      <div class="form-group">
                          <div class="custom-file">
                              <input type="file" class="custom-file-input" name="hrd_final_file" id="customFile2">
                              <label class="custom-file-label" for="customFile2">Choose file</label>
                          </div>
                      </div>
                    </div>
                </div>

              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submitFrm()">Save Changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

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

  function submitFrm()
  {
    $("#frm").submit();
  }


  function modalOption(type,id = null)
  {
    $("#tbl_id").val(id);

    switch(type)
    {
      case "receive":
        $("#frm_url_action").val("{{ url('learning-development/hrd-approval') }}");
        $("#frm").submit();
      break;

      case "upload-final":
        $("#modalOption").modal('toggle');
        $("#frm_url_action").val("{{ url('learning-development/oed-upload-final') }}");
      break;
    }

      $("#frm_url_reset").val("{{ url('learning-development/list-hrd-approval') }}");
      
  }
</script>
@endsection