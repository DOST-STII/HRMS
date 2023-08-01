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
                    @foreach($data['employee'] as $employees)
                        <tr>
                          <td></td>
                          <td>{{ $employees->lname . ", " .$employees->fname . " " .$employees->mname . " " .$employees->plantilla_id }}</td>
                          <td>{{ $employees->username }}</td>
                          <td>{{ $employees->division_acro }}
                              <input type="hidden" id="{{ $employees->id }}_employment_status_id" value="{{ $employees->employment_id }}"></td>                          
                          <td>
  
                          </td>
                          <td>{{ $employees->employment }}</td>
                          <td>
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ url('update-employee/'.$employees->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                <!-- <a class="dropdown-item" href="#"><i class="fas fa-print"></i> Print PDS</a> -->
                                <a class="dropdown-item" href="#" onclick="modalFunction('service-record',{{ $employees->id }},'{{ url("service-record") }}','{{ url("list-of-employees") }}')"><i class="fas fa-folder"></i> Service Record</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('reset-password',{{ $employees->id }},'{{ url("reset-password") }}','{{ url("list-of-employees") }}')"><i class="fas fa-key"></i> Reset Password</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('transfer',{{ $employees->id }})"><i class="fas fa-arrow-up"></i> Transfer</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('change-status',{{ $employees->id }},'{{ url("change-status") }}','{{ url("list-of-employees") }}')"><i class="fas fa-exchange-alt"></i> Change Status</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('delete-employee',{{ $employees->id }},'{{ url("employee/delete") }}','{{ url("list-of-employees") }}')" style="color:red"><i class="fas fa-trash"></i> Delete</a>
                              </div>
                          </td>
                        </tr>
                    @endforeach

                    @foreach($data['employee_temp'] as $employees)
                        <tr>
                          <td></td>
                          <td>{{ $employees->lname . ", " .$employees->fname . " " .$employees->mname . " " .$employees->palntilla_id }}</td>
                          <td>{{ $employees->username }}</td>
                          <td>{{ $employees->division_acro }}<input type="hidden" id="{{ $employees->id }}_employment_status_id" value="{{ $employees->employment_id }}"></td>                          
                          <td>{{ $employees->plantilla_item_number }}</td>
                          <td>{{ $employees->employment_desc }}</td>
                          <td>
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ url('update-employee/'.$employees->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-print"></i> Print PDS</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('service-record',{{ $employees->id }})"><i class="fas fa-folder"></i> Service Record</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('reset-password',{{ $employees->id }})"><i class="fas fa-key"></i> Reset Password</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('transfer',{{ $employees->id }})"><i class="fas fa-arrow-up"></i> Transfer</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('change-status',{{ $employees->id }})"><i class="fas fa-exchange-alt"></i> Change Status</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('delete-employee',{{ $employees->id }})" style="color:red"><i class="fas fa-trash"></i> Delete</a>
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

      <!-- RESET PASSWORD MODAL-->
      <div class="modal fade" id="modal-option">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('employee/create') }}">   -->
            {{ csrf_field() }}
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('list-of-employees') }}">
            <input type="hidden" name="tbl_name" id="tbl_name" value="">
            <input type="hidden" name="tbl_name_id" id="tbl_name_id" value="">

            <div class="modal-body">

              <!-- DIV FOR RESET PASSWORD -->
              <div class="row div-content" id="div-reset-password">
                <div class="col-md-12">
                  <center><h3>The default password is <b>"qweasdzxc"</b> click <span class="text-primary"><b>Save changes</b></span> to continue</h3></center>
                </div>
              </div>
               <!-- END DIV FOR RESET PASSWORD -->

              <!-- SERVICE RECORD -->
              <div class="div-content" id="div-service-record">
                <strong>Employee Purpose</strong>
                        <br>
                        <br>
                        <div class="row">
                          <div class="col-md-12" id="service-record-col-1">
                            <p class="text-muted">
                            <select class="form-control" id="servicerecord" name="servicerecord">
                                  <option value=""></option>
                                  <option value="Attendance To">Attendance To</option>
                                  <option value="GSIS">GSIS</option>
                                  <option value="Official/Travel trip to">Official/Travel trip to</option>
                                  <option value="For whatever legal purpose this may serve">For whatever legal purpose this may serve</option> 
                            </select>
                          </p>
                          </div>
                          <div class="col-md-6" id="service-record-col-2" style="display: none;">
                            <input type="text" class="form-control" name="servicerecordto" id="servicerecordto">
                          </div>
                        </div>
                          
              </div>
              <!-- END SERVICE RECORD -->


               <!-- DIV TRANSFER -->
              <div class="div-content" id="div-transfer">
                <strong>Division</strong>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="transfer_division" name="transfer_division">
                              @foreach(getDivisionList() as $divs)
                                  <option value="{{ $divs->division_id }}">{{ $divs->division_acro }}</option>
                              @endforeach
                            </select>
                          </p>
                 <strong>Designation</strong>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="transfer_division_desig" name="transfer_division_desig">
                              @foreach(getDesignationList() as $desig)
                                  <option value="{{ $desig->designation_id }}">{{ $desig->designation_desc }}</option>
                              @endforeach
                            </select>
                          </p>
                <strong>Date of Transfer</strong>
                <br>
                <input type="date" class="form-control" name="transfer_division_date">
              </div>
              <!-- END DIV TRANSFER -->

              <!-- DIV CHANGE STATUS -->
              <div class="div-content" id="div-change-status">
                  <strong>Status</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="employmentstatus" name="employmentstatus">
                              <option value=""></option>
                              @foreach($data['employment'] as $employments)
                                  <option value="{{ $employments->employment_id }}">{{ $employments->employment_desc }}</option>
                              @endforeach
                            </select>
                          </p>
              </div>
              <!-- END DIV CHANGE STATUS -->



            </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="modalOnSubmit()">Save changes</button>
            
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

  // $(function () {
  //   var t = $("#tbl").DataTable();
  //   var t2 = $('#example2').DataTable({
  //     "paging": true,
  //     "lengthChange": false,
  //     "searching": false,
  //     "ordering": true,
  //     "info": true,
  //     "autoWidth": false,
  //   });

  //   t.on('order.dt search.dt', function () {
  //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  //         cell.innerHTML = i+1;
  //     });
  // }).draw();

  // });

  function deleteEmployee(id)
  {
        $("#empid").val(id);
        $("#frm").submit();
  }

  function modalFunction(title,id,url,url2)
  {
    $(".div-content").hide();
    $("#frm_url_reset").val("{{ url('list-of-employees') }}");
    $('#tbl_name_id,#empid').val(id);

    switch(title)
    {
      case "reset-password":
        $("#frm_url_action").val("{{ url('reset-password') }}");
        $("#modal-option").modal('toggle');
        $("#modal-title").empty().text("Reset Password");
        $("#icon-title").removeClass().addClass('fas fa-key');
        $("#div-reset-password").show();
      break;

      case "transfer":
        $("#frm_url_action").val("{{ url('transfer-employee') }}");
        $("#modal-option").modal('toggle');
        $("#modal-title").empty().text("Transfer");
        $("#icon-title").removeClass().addClass('fas fa-arrow-up');
        $("#div-transfer").show();
      break;

      case "change-status":
        $("#frm_url_action").val("{{ url('change-status') }}");
        $("#modal-option").modal('toggle');
        $("#modal-title").empty().text("Change Status");
        $("#icon-title").removeClass().addClass('fas fa-exchange-alt');
        $("#div-change-status").show();
        $('#employmentstatus').val($("#"+id+"_employment_status_id").val());
      break;

      case "service-record":
        $("#frm_url_action").val("{{ url('service-record') }}");
        $("#modal-option").modal('toggle');
        $("#modal-title").empty().text("Service Record");
        $("#icon-title").removeClass().addClass('fas fa-folder');
        $("#div-service-record").show();
      break;

      case "delete-employee":
        $("#frm_url_action").val("{{ url('employee/delete') }}");
        $("#frm").submit();
      break;
    }

  }

$("#servicerecord").change(function(){
  //DEFAULT
  $("#service-record-col-1").removeClass().addClass('col-md-12');
  $("#service-record-col-2").hide();

  switch(this.value)
  {
    case "Attendance To":
    case "Official/Travel trip to":
      $("#service-record-col-1").removeClass().addClass('col-md-6');
      $("#service-record-col-2").show();
    break;
  }
});

  function modalOnSubmit()
  {
    switch($("#modal-title").text())
    {
      case "Service Record":
        $("#serviceoption").val($("#servicerecord").val());
        $("#serviceto").val($("#servicerecordto").val());
        $("#frm2").submit();
        // var id = $("#tbl_name_id").val();
        // var win = window.open('{{ url("pdf/service-record") }}/' + id, '_blank');
        // win.focus();
      break;

      default:
         $("#frm").submit();
      break;
    }
  }


</script>
@endsection