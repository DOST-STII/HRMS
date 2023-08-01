@extends('template.master')

@section('CSS')

@endsection

@section('content')

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">LETTER FOR APPROVAL</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table" id="tbl">
                <thead>
                  <th style="width: 2%"></th>
                  <th>Item</th>
                        <th>Position</th>
                        <th style="width: 10%" class="text-center">Status</th>
                        <th style="width: 15%" class="text-center">Letter of Request</th>
                        <th style="width: 7%"></th>
                </thead>
                <tbody>
                  @foreach($data['list'] AS $lists)
                            <tr>
                              <td></td>
                              <td>
                                {{ getPlantillaItemInfo('number',$lists->plantilla_id) }}
                              </td>
                              
                              <td>
                                {{ getPlantillaItemInfo('position',$lists->plantilla_id) }}
                              </td>
                              <td align="center">{{ formatStatus($lists->request_status) }}</td>
                              <td align="center">{{ getFile('hiring','Letter of Request',$lists->id) }}</td>
                              <td>
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-list"></i>
                                    </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" onclick="actionRequest({{ $lists->id }})" style="color:blue"><i class="fas fa-check"></i> Receive</a>
                                        <a class="dropdown-item" href="{{ url('recruitment/history/'.$lists->id) }}" target="_blank"><i class="fas fa-history"></i> History</a>
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
      <form method="POST" id="frm" enctype="multipart/form-data" role="form">
      <!-- <form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('recruitment/clearance') }}"> -->
            {{ csrf_field() }}
            <input type="hidden" name="letterid" id="letterid" value="">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('recruitment/clearance') }}">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('recruitment/letter-approval') }}">
      </form>

      <div class="modal fade" id="modal-requirement">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title">REQUIREMENTS</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                  <tr>
                    <td colspan="3"><b>Evaluation</b></td>
                  </tr>
                  <tr>
                    <td style="width: 2%">1</td>
                    <td></td>
                    <td style="width: 5%"><input type="checkbox" name="" checked readonly></td>
                  </tr>
                </table>
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

  function showOption(id)
  {
    $("#modal-requirement").modal('toggle');
  }

  function actionRequest(id)
  {
    $("#letterid").val(id);
    $("#frm").submit();
  }
</script>
@endsection