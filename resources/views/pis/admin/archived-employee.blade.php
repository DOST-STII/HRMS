@extends('template.master')

@section('CSS')
<style type="text/css">
  tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
@endsection

@section('content')

<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Employee List</h3>
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
                </tr>
                </thead>
                <tbody>
                    @foreach($data['employee'] as $employees)
                        <tr>
                          <td></td>
                          <td>{{ $employees->lname . ", " .$employees->fname . " " .$employees->mname . " " .$employees->palntilla_id }}</td>
                          <td>{{ $employees->username }}</td>       
                          <td>{{ $employees->division_acro }}</td>                          
                          <td>{{ $employees->plantilla_item_number }}</td>
                          <td>{{ $employees->employment_desc }}</td>
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
$(document).ready(function() {
    $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });
 
} );


</script>
@endsection