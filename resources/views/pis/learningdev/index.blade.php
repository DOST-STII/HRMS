@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<h1>Learning and Development</h1>
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

  <div class="col-12">
          <div class="card card-primary card-outline card-outline-tabs">

                  <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-hrdplan" role="tab" aria-controls="tabs-hrdplan" aria-selected="true">HRD Plan</a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" id="tabs-non-degree-tab" data-toggle="pill" href="#tabs-non-degree" role="tab" aria-controls="tabs-non-degree-tab" aria-selected="false">Non-Degree</a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" id="tabs-studies-tab" data-toggle="pill" href="#tabs-studies" role="tab" aria-controls="tabs-studies-tab" aria-selected="false">Graduate Studies</a>
                      </li>

                    </ul>
                  </div>

              <div class="card-body">

                <div class="tab-content" id="custom-tabs-three-tabContent">

                  <div class="tab-pane fade show active" id="tabs-hrdplan" role="tabpanel" aria-labelledby="tabs-hrdplan-tab">
                      <button class="btn btn-primary float-right" onclick="modalOption('call-for-sub')"><i class="fas fa-bullhorn"></i> Call for Submission</button>
                    <br>
                    <br>
                    <table id="tbl" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Year</th>
                        <th>Deadline</th>
                        <th>Degree</th>
                        <th>Non-Degree</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th style="width: 5%"></th>
                      </tr>
                      </thead>
                      <tbody>
                          @foreach($data['hrd_plan'] AS $hrds)
                            <tr>
                              <td></td>
                              <td>{{ $hrds->hrd_year }}</td>
                              <td>{{ $hrds->hrd_deadline }}</td>
                              <td class="text-center" style='cursor:pointer' onclick="modalOption('show-degree',{{ $hrds->id }})">{{ getDivisionHRDPlan('non-degree',$hrds->id) }}</td>
                              <td class="text-center" style='cursor:pointer' onclick="modalOption('show-non-degree',{{ $hrds->id }})">{{ getDivisionHRDPlan('degree',$hrds->id) }}</td>
                              <td>{{ $hrds->hrd_status }}</td>
                              <td></td>
                              <td>
                                @if($hrds->hrd_status != 'Closed')
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-list"></i>
                                    </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  
                                  @if($hrds->hrd_status == 'On-going')
                                    <a class="dropdown-item" href="#" onclick="modalOption('send-hrdc',{{ $hrds->id }})"><i class="fas fa-forward"></i> Forward to HRDC Members</a>
                                  @elseif($hrds->hrd_status == 'Forwarded to HRDC members')
                                    <a class="dropdown-item" href="#" onclick="modalOption('send-oed',{{ $hrds->id }})"><i class="fas fa-forward"></i> Forward to OED</a>
                                  @elseif($hrds->hrd_status == 'Forwarded to OED')
                                    <a class="dropdown-item" href="#" onclick="modalOption('close-hrd',{{ $hrds->id }})"><i class="fas fa-check"></i> Close</a>
                                  @endif
                                    <a class="dropdown-item" href="#"><i class="fas fa-history"></i> History</a>
                                </div>
                                @endif

                              </td>
                            </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="tab-pane fade" id="tabs-non-degree" role="tabpanel" aria-labelledby="tabs-non-degree-tab">

                     <table id="tbl-degree" class="table table-bordered table-striped" style="width: 50% !important">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th><center>Year</center></th>
                        <th><center>Monitoring</center></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php $ctr = 1; ?>
                        @foreach(getHRDMonitoring() AS $hrds)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $hrds->hrd_year }}</td>
                            <td><center><a href="{{ url('learning-development/print/hrd-plan-monitoring-non-degree/'.$hrds->id) }}" target="_blank"><i class="fas fa-eye"></i></a></center></td>
                          </tr>
                        <?php $ctr++; ?>
                        @endforeach
                      </tbody>
                    </table>

                  </div>

                  <div class="tab-pane fade" id="tabs-studies" role="tabpanel" aria-labelledby="tabs-studies-tab">
                     
                     <!-- <table id="tbl-non-degree" class="table table-bordered table-striped" style="width: 50% !important">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th><center>Year</center></th>
                        <th><center>Monitoring</center></th>
                        <th style="width: 2%"></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php $ctr = 1; ?>
                        @foreach(getHRDMonitoring() AS $hrds)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $hrds->hrd_year }}</td>
                            <td><center><a href="{{ url('learning-development/print/hrd-plan-monitoring-degree/'.$hrds->id) }}" target="_blank"><i class="fas fa-eye"></i></a></center></td>
                            <td><center><i class="fas fa-edit"></i></center></td>
                          </tr>
                        <?php $ctr++; ?>
                        @endforeach
                      </tbody>
                    </table> -->

                    <p align="right"><a href="{{ url('learning-development/print/hrd-plan-monitoring-degree') }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Print in PDF</a></p>
                    <table id="tbl-degree" class="table table-bordered table-striped" style="width: 100% !important">
                    <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th><center>Staff</center></th>
                        <th><center>Degree Program</center></th>
                        <th><center>Field of Specialization</center></th>
                        <th><center>College/University</center></th>
                        <th><center>Period</center></th>
                        <th><center>Remarks</center></th>
                        <th style="width: 2%"></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php $ctr = 1 ?>
                        @foreach(getDegreeList2('degree') AS $lists)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ getStaffInfo($lists->user_id) }}</td>
                            <td>{{ $lists->hrd_degree_program }}</td>
                            <td>{{ $lists->hrd_degree_area }}</td>
                            <td>{{ $lists->hrd_degree_university }}</td>
                            <td>{{ $lists->hrd_degree_from . ' - ' .$lists->hrd_degree_to }}</td>
                            <td>{{ $lists->hrd_degree_remarks }}</td>
                            <td><center><i class="fas fa-edit" onclick="modalOption('edit-degree',{{ $lists->id }})" style="cursor: pointer"></i></center></td>
                          </tr>
                        <?php $ctr++; ?>
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
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form">   -->
                <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('learning-development/call-for-hrd-plan') }}">  
                {{ csrf_field() }}
                <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('learning-development/call-for-hrd-plan') }}">
                <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('learning-development/index') }}">
                <input type="hidden" name="tbl_id" id="tbl_id" value="">
                <input type="hidden" name="degree_id" id="degree_id" value="">

                <div class="row option-div" id="option-call">
                    <div class="col-6">
                      <strong>For the Year</strong><br>
                      <input type="number" class="form-control" name="hrd_year" value="{{ 1 + date('Y') }}">
                    </div>

                    <div class="col-6">
                      <strong>&nbsp</strong><br>
                      <input type="number" class="form-control" name="hrd_year2" value="{{ 5 + date('Y') }}">
                    </div>
                    <br>
                    <br>
                    <br>

                    <div class="col-md-12 mt-25">
                      <strong>Deadline</strong><br>
                      <input type="date" class="form-control" name="hrd_deadline" value="">
                    </div>
                </div>

                <div class="row option-div" id="option-division">
                    <div class="col-md-12">
                      <table class="table table-sm">
                        <thead>
                          <th style="width: 33%">Division</th>
                          <th style="width: 33%">File</th>
                          <th style="width: 33%">Date Uploaded</th>
                        </thead>
                        <tbody id="tbodydiv">
                          
                        </tbody>
                      </table>
                    </div>
                </div>

                <div class="row option-div" id="option-showhrdc">
                    <div class="col-md-12">
                      <table class="table table-sm">
                        <thead>
                          <th style="width: 50%">Member</th>
                          <th style="width: 50%">Date Received</th>
                        </thead>
                        <tbody id="tbodyhrdc">
                          
                        </tbody>
                      </table>
                    </div>
                </div>

                <div class="row option-div" id="option-hrdc">
                    <div class="col-md-12">
                      <strong>Consolidated HRD Plan</strong>
                      <div class="form-group">
                          <div class="custom-file">
                              <input type="file" class="custom-file-input" name="hrd_consolidated_file" id="customFile2">
                              <label class="custom-file-label" for="customFile2">Choose file</label>
                          </div>
                      </div>
                    </div>
                </div>

                <div class="row option-div" id="option-edit-degree">
                  <div class="col-md-6">
                      <strong>From</strong>
                      <div class="form-group">
                          <input type="text" class="form-control" name="hrd_degree_from" id="hrd_degree_from">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <strong>To</strong>
                      <div class="form-group">
                          <input type="text" class="form-control" name="hrd_degree_to" id="hrd_degree_to">
                      </div>
                    </div>
                  <div class="col-md-12">
                      <strong>Remarks</strong>
                      <div class="form-group">
                          <textarea class="form-control" name="hrd_degree_remarks" id="hrd_degree_remarks"></textarea>
                      </div>
                    </div>
                </div>


                <div class="row option-div" id="option-edit-degree-view">
                  <div class="col-md-6">
                      <strong>From</strong>
                      <div class="form-group">
                          <input type="text" class="form-control" name="view_hrd_degree_from" id="view_hrd_degree_from">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <strong>To</strong>
                      <div class="form-group">
                          <input type="text" class="form-control" name="view_hrd_degree_to" id="view_hrd_degree_to">
                      </div>
                    </div>
                </div>

                <!-- </form> -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submitFrm()">Save Changes</button>
              </form>
            </div>
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
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

function modalOption(type,id = null)
{
  $(".option-div").hide();
  $(".modal-title").empty();
  $("#btnSubmit").show();

  switch(type)
  {
    case 'call-for-sub':
      $("#modalOption").modal('toggle');
      $(".modal-title").text("Call for Submission");
      $("#option-call").show();
      $("#frm_url_reset").val("{{ url('learning-development/index') }}");
      $("#frm_url_action").val("{{ url('learning-development/call-for-hrd-plan') }}");
      
    break;

    case 'show-non-degree':
      window.open('{{ url("learning-development/print/hrd-plan-consolidated") }}/' + id, '_blank');
    break;

    case 'show-degree':
      window.open('{{ url("learning-development/print/hrd-plan-consolidated-degree") }}/' + id, '_blank');
    break;

    case 'show-hrdc':
      $("#modalOption").modal('toggle');
      $("#btnSubmit").hide();
      $(".modal-title").text("HRDC Members");
      $("#option-showhrdc").show();

      $.getJSON( "{{ url('learning-development/hrdc-hrd-list/') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                $("#tbodydiv").empty();
                jQuery.each(datajson,function(i,obj){
                          $("#tbodyhrdc").append('<tr><td>'+obj.fullname+'</td><td>'+obj.received_at+'</td></tr>')
                    });
            }).fail(function() {
            });
    break;

    case 'send-hrdc':
      // $("#modalOption").modal('toggle');
      $("#tbl_id").val(id);
      // $(".modal-title").text("Send to HRDC Members");
      // $("#option-hrdc").show();
      $("#frm_url_reset").val("{{ url('learning-development/index') }}");
      $("#frm_url_action").val("{{ url('learning-development/send-to-hrdc') }}");
      submitFrm();
      
    break;

    case 'send-oed':
      $("#tbl_id").val(id);
      // $(".modal-title").text("Send to OED");
      // $("#option-oed").show();
      $("#frm_url_reset").val("{{ url('learning-development/index') }}");
      $("#frm_url_action").val("{{ url('learning-development/send-to-oed') }}");
      submitFrm();

    case 'close-hrd':
      $("#tbl_id").val(id);
      // $(".modal-title").text("Send to OED");
      // $("#option-oed").show();
      $("#frm_url_reset").val("{{ url('learning-development/index') }}");
      $("#frm_url_action").val("{{ url('learning-development/close-hrd') }}");
      submitFrm();
      
    break;

    case 'edit-degree':

      $.getJSON( "{{ url('learning-development/hrd-degree-json/') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                      $("#hrd_degree_from").val(obj.hrd_degree_from);
                      $("#hrd_degree_to").val(obj.hrd_degree_to);
                    });
            }).fail(function() {
            });

      $("#modalOption").modal('toggle');
      $(".modal-title").text("Edit Degree");
      $("#option-edit-degree").show();
      $("#degree_id").val(id);
      $("#frm_url_reset").val("{{ url('learning-development/index') }}");
      $("#frm_url_action").val("{{ url('learning-development/hrd-degree-update') }}");
      
    break;

    case 'view-degree':

      $("#modalOption").modal('toggle');
      $(".modal-title").text("View Degree");
      $("#option-edit-degree-view").show();
      
    break;
  }
  
}

function viewDegree()
{
  var view_from = $("#view_hrd_degree_from").val();
  var view_to = $("#view_hrd_degree_to").val();
  window.open('{{ url("learning-development/print/hrd-plan-monitoring-degree") }}/'+view_from+'/'+view_to, '_blank');
}

function submitFrm()
{
  $("#frm2").submit();
}

  </script>

@endsection