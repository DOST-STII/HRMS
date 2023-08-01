@extends('template.master')

@section('CSS')

@endsection

@section('content')

<form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('pdf/service-record') }}" target="_blank">
  {{ csrf_field() }}
  <input type="hidden" name="empid" id="empid">
  <input type="hidden" name="serviceoption" id="serviceoption">
  <input type="hidden" name="serviceto" id="serviceto">
</form>
<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Employee List</h3><div class="float-right"><a href="{{ url('add-new-employee') }}" class="btn btn-primary"><i class="fas fa-plus"></i> ADD NEW</a></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th style="width: 30%">Name</th>
                  <th>Employee Code</th>
                  <th>Division</th>
                  <th>Item Number</th>
                  <th>Status</th>
                  <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
               
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
</script>
@endsection