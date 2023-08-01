@extends('template.master')

@section('CSS')

@endsection

@section('content')

<div class="row">
  <form method="POST" id="frm" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="invitation_id" id="invitation_id" value="">
              <input type="hidden" name="invitation_answer" id="invitation_answer" value="">
              <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('invitation/answer') }}">
              <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('invitation/list') }}">
  </form>
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">List of Invitation</h3><!-- <div class="float-right"><button type="button" class="btn btn-primary" onclick="modalFunction('add-new-pantilla',0,'{{ url("plantilla/create") }}','{{ url("vacant-position") }}')"><i class="fas fa-plus"></i> ADD NEW</button></div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Position</th>
                  <th>Division</th>
                  <th>Vacancy Advise</th>
                  <th>URL</th>
                  <th style="width: 8%"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach(getMyInvitation('list') AS $lists)
                      <tr>
                        <td></td>
                        <td>{{ getVacantPlantillaInfo($lists->vacant_plantilla_id,'position_desc') }}</td>
                        <td>{{ getVacantPlantillaInfo($lists->vacant_plantilla_id,'division_acro') }}</td>
                        <td>{{ getFile('hiring','Vacancy Advise',$lists->vacant_plantilla_id) }}</td>
                        <td class="text-center">
                          @if($lists->interested == 'Yes')
                            <a href="{{ url('apply/'.getVacantPlantillaInfo($lists->vacant_plantilla_id,'request_for_hiring_id').'/'.getVacantPlantillaInfo($lists->vacant_plantilla_id,'plantilla_item_number')) }}" class="btn btn-success" target="_blank">Apply Now</a>
                          @endif
                        </td>
                        <td class="text-center">
                          @if($lists->interested == '')
                          <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <i class="fas fa-list"></i>
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" onclick="actionRequest('interested',{{ $lists->id }})"><i class="fas fa-check"></i> Interested</a>
                                    <a class="dropdown-item" href="#" onclick="actionRequest('notinterested',{{ $lists->id }})" style="color:red"><i class="fas fa-times"></i> Not Interested</a>
                                  </div>
                          @else
                            <a class="dropdown-item" href="#" onclick="actionRequest('delete',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i></a>
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
  function actionRequest(type,id)
  {
    $("#invitation_id").val(id);
    if(type == 'interested')
    {
      $("#invitation_answer").val('Yes');
    }
    else if(type == 'notinterested')
    {
      $("#invitation_answer").val('No');
    }
    else
    {
      $("#invitation_answer").val('Delete');
    }
    $("#frm").submit();
  }
</script>
@endsection