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
              <h3 class="card-title">Training Report List</h3><!-- <div class="float-right"><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-request"><i class="fas fa-plus"></i>NEW REQUEST</a></div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th style="width: 20%">Title</th>
                  <th style="width: 20%">Staff</th>
                  <th style="width: 10%" class="text-center">Conducted By</th>
                  <th style="width: 5%" class="text-center">Report</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['list'] as $lists)
                      <tr>
                        <td>#</td>
                        <td>{{ $lists->training_title }}</td>
                        <td>{{ getStaffInfo($lists->user_id) }}</td>
                        <td>{{ $lists->training_conducted_by }}</td>
                        <td class="text-center">
                          @if($lists->training_report != null)
                          <a href="{{ asset('../storage/app/'.$lists->training_report) }}" target="_blank"><i class="fas fa-paperclip"></i></td>
                          @else
                            <b>-</b>
                          @endif
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
</script>
@endsection