@extends('template.master')

@section('CSS')

@endsection

@section('content')

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Vacant Position</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Item Number</th>
                  <th>Position Title</th>
                  <th>Division</th>
                  <th style="width: 8%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['plantilla'] as $plantillas)
                        <tr>
                          <td></td>
                          <td>{{ $plantillas->plantilla_item_number }}</td>
                          <td>{{ $plantillas->position_desc }}</td>
                          <td>{{ getDivision($plantillas->division_id) }}</td>
                          <td class="text-center">
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" onclick="showOption({{ $plantillas->id }})"><i class="fas fa-folder"></i> Requirements</a>

                                <a class="dropdown-item" href="#" class="text-muted" style="color: #AAA"><i class="fas fa-edit" style="color: #AAA"></i> Applicants</a>
                                <!-- <a class="dropdown-item" href="#" onclick="modalFunction('assign-plantilla',{{ $plantillas->id }},'{{ url("plantilla/assign") }}','{{ url("vacant-position") }}')"><i class="fas fa-exchange-alt"></i> Assign to an Employee</a> -->
                                <a class="dropdown-item" href="#" style="color:red" onclick="showOption('delete-plantilla','{{ $plantillas->request_for_hiring_id }}',{{ $plantillas->id }})"><i class="fas fa-trash"></i> Delete</a>
                              </div>
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
</script>
@endsection