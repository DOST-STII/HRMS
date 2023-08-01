@extends('template.master')

@section('CSS')

@endsection

@section('content')

<div class="row">
        <div class="col-10">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">CONTRACT OF SERVICE/JO</h2>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table" id="tbl">
                <thead>
                  <th style="width: 2%">#</th>
                  <th>Fullname</th>
                  <th>Division</th>
                  <th></th>
                </thead>
                <tbody>
                @foreach($data['employee'] as $cos)
                  <tr>
                      <td></td>
                      <td>{{ strtoupper($cos->lname.', '.$cos->fname) }}</td>
                      <td>{{ $cos->division_acro }}</td>
                      <td style="width:20%">
    <a href="{{ url('staff/attendance/' . date('m') . '/' . date('Y') . '/' . $cos->employment_id) }}" target="_blank">
        <i class="fas fa-print"></i> View DTR ({{ date('M, Y') }})</a>
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

        <div class="col-2">
          <div class="card">
            <div class="card-body">
              <table class="table table-stripped">
                <thead>
                  <th>Division</th>
                  <th>#</th>
                </thead>
                <tbody>
                  @foreach($data['countEmployee'] AS $divisions)
                        <tr><td>{{ $divisions->division_acro }}</td><td class="text-center">{{ $divisions->employee_count }}</td></tr>
                      @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
</div>

      <div class="modal fade" id="modal-option">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
              <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('plantilla/assign') }}">   -->
              {{ csrf_field() }}
              
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
<script type="text/javascript">
    $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });
</script>
@endsection