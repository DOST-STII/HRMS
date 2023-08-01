@extends('template.master')

@section('CSS')
  <link rel="stylesheet" href="{{ asset('multidate/bootstrap-datepicker.css') }}">
@endsection

@section('content')
<!--  -->
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Trainings</h3><!-- <div class="float-right"><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-request"><i class="fas fa-plus"></i>NEW REQUEST</a></div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th style="width: 20%">Title</th>
                  <th style="width: 10%" class="text-center">Conducted By</th>
                  <th style="width: 10%" class="text-center">Dates</th>
                  <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['list'] AS $lists)
                    <tr>
                      <td align="center"></td>
                      <td>{{ $lists->training_title }}</td>
                      <td>{{ $lists->training_conducted_by }}</td>
                      <td>{{ $lists->training_inclusive_dates }}</td>
                      <td class="text-center"><button class="btn btn-success btn-sm" onclick="updateModal({{ $lists->id }})"><i class="fas fa-edit"></i></button></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card --> </div>
        <!-- /.col -->
      </div>


<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Training</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
             <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request-for-hiring/create') }}">   -->
            {{ csrf_field() }}
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('trainings/update') }}">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('request-for-training/complete') }}">

            <strong>Does this training push through?</strong>
            <br>
            <input type="radio" class="training_go" name="training_go" value="Yes" checked> Yes <input type="radio" class="training_go" name="training_go" value="No"> No

            <div class="div-content" id="div-content-yes">
              <br>
                <strong>Report <i style="color: red"><b>*</b></i></strong>
                <div class="form-group">
                      <div class="custom-file">
                              <input type="file" class="custom-file-input" name="training_report" id="customFile" required>
                              <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                </div>
                <strong>Certificate <i style="color: red"><b>*</b></i></strong>
                <div class="form-group">
                      <div class="custom-file">
                              <input type="file" class="custom-file-input" name="training_certificate" id="customFile2" required>
                              <label class="custom-file-label" for="customFile2">Choose file</label>
                      </div>
              </div>
            </div>

            <div class="div-content" id="div-content-no" style="display: none">
              <br>
                <strong>Reason</strong>
                <textarea class="form-control" name="training_reason"></textarea>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitFrm()">Save changes</button>
      </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('JS')
<script src="{{ asset('multidate/bootstrap-datepicker.js') }}"></script>
<script>
  $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

  $('.date').datepicker({
        multidate: true,
        format: "yyyy-mm-dd",
        });

$(".training_go").change(function(){
    $(".div-content").hide();
    if(this.value == 'Yes')
    {
      $("#div-content-yes").show();
    }
    else
    {
      $("#div-content-no").show();
    }
});

function updateModal(id)
{
  $("#tblid").val(id);
  $("#modalUpdate").modal('toggle');
}

function submitFrm()
{
  $("#frm").submit();
}
</script>
@endsection