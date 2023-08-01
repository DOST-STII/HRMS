@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<h1>Succession Planning and Retirement </h1>
<!-- <div class="row">
  <div class="col-3">
    <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Training</span>
                <span class="info-box-number">-</span>
              </div>
            </div>
  </div>
</div> -->
  <br>
  <br>
<div class="row">
  <div class="col-lg-6 col-sm-12">
                <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-retiree" role="tab" aria-controls="tabs-competency" aria-selected="true">Retiree</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tabs-competency" role="tab" aria-controls="tabs-competency" aria-selected="false">Core Competency</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="tabs-retiree" role="tabpanel" aria-labelledby="tabs-hiring-tab">
                     <table class="table" id="tbl">
                        <thead>
                          <th>Fullname</th>
                          <th>Age</th>
                          <th>Competencies</th>
                        </thead>
                        <tbody>
                          @foreach($data['retiree'] as $retirees)
                            <tr><td>{{ $retirees->lname.", ".$retirees->fname." ".$retirees->mname }}</td>
                              <td>{{ $retirees->age }}</td>
                              <td>{{ $retirees->competencies }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                  </div>

                  <div class="tab-pane fade" id="tabs-competency" role="tabpanel" aria-labelledby="tabs-training-tab">

                    <p align="right"><button class="btn btn-primary" onclick="addNew()"> ADD </button></p>
                      <table class="table">
                        <thead>
                          <th>Description</th>
                          <th>Employee</th>
                        </thead>
                        <tbody>
                          @foreach($data['competency'] as $competencies)
                            <tr>
                              <td>{{ $competencies->core_desc }}</td>
                              <td>{{ $competencies->employees }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
  </div>
</div>

<div class="modal fade" id="modalOption">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Core Competency</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="frm_comp" enctype="multipart/form-data" action="{{ url('core-competency/create') }}">  
                {{ csrf_field() }}
              <div class="row">
                  <input type="text" class="form-control" name="competency_desc" placeholder="Description" required>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>

          </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

@endsection

@section('JS')

<!-- Sparkline -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- ChartJS -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(function () {
    var t = $("#tbl_1").DataTable();

  //   t.on('order.dt search.dt', function () {
  //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  //         cell.innerHTML = i+1;
  //     });
  // }).draw();

  });

function addNew()
  {
    $("#modalOption").modal('toggle');
  }
</script>
@endsection