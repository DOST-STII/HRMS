@extends('template.master')

@section('CSS')
<style type="text/css">
  .inactive_text
  {
    color:#999;
  }

  .active_text
  {
    color:#222;
    font-weight: bold;
  }

  .list-group.panel > .list-group-item {
  border-bottom-right-radius: 4px;
  border-bottom-left-radius: 4px
}

</style>
@endsection

@section('content')

<div class="row">
        <div class="col-10">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">CALL FOR SUBMISSION</h3><div class="float-right"><button type="button" class="btn btn-primary" onclick="modalFunction('create-submission')"><i class="fas fa-bullhorn"></i> ALERT DIVISION</button></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table" id="tbl">
                <thead>
                  <th style="width: 2%">#</th>
                  <th>Description</th>
                  <th>Remarks</th>
                  <th>Deadline</th>
                  <th style="width: 10%" class="text-center">Entries</th>
                  <th style="width: 2%"></th>
                </thead>
                <tbody>
                  @foreach($data['list'] AS $lists)
                  <tr>
                    <td></td>
                    <td>{{ $lists->sub_report }}</td>
                    <td>{{ $lists->sub_remarks }}</td>
                    <td>{{ $lists->sub_deadline }}</td>
                    <td class="text-center"><a href="#" style="text-decoration: none" onclick="showContent('{{ $lists->sub_report }}')">{{ getSubmissionCount("$lists->sub_report",$lists->id,'response')."/".getSubmissionCount("$lists->sub_report",$lists->id,'total') }}</a></td>
                    <td class="text-center">
                      <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" onclick="modalFunction('edit-submission',{{ $lists->id }})"><i class="fas fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#" style="color:red" onclick="modalFunction('delete-submission',{{ $lists->id }})"><i class="fas fa-trash"></i> Archive</a>
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
        <div class="col-2">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">ARCHIVE</h3>
            </div>
            <div class="card-body">
                    <div id="MainMenu">
        <div class="list-group panel">
         <?php
            $yr = (date("Y")-1);
            $yr2 = (date("Y")-6);

            for($x = $yr; $x >= $yr2; $x--)
            {
              echo '<a href="#year_'.$x.'" class="list-group-item list-group-item-info" data-toggle="collapse" data-parent="#MainMenu">'.$x.'<i class="fa fa-caret-down float-right"></i></a>
                  <div class="collapse" id="year_'.$x.'">';

                    echo '<a href="#year_'.$x.'_mar" class="list-group-item" data-toggle="collapse" data-parent="#year_'.$x.'_ipcr">MAR<i class="fa fa-caret-down float-right"></i></a>
                    <div class="collapse list-group-submenu" id="year_'.$x.'_mar">';
                      foreach (getMonths() as $value) {
                        echo '<a href="#" class="list-group-item" data-parent="#year_'.$x.'_mar">'.$value.'</a>';
                      }
                    echo '</div>';

                    echo '<a href="#year_'.$x.'_mer" class="list-group-item" data-toggle="collapse" data-parent="#year_'.$x.'_mer">MER<i class="fa fa-caret-down float-right"></i></a>
                    <div class="collapse list-group-submenu" id="year_'.$x.'_mer">';
                      foreach (getMonths() as $value) {
                        echo '<a href="#" class="list-group-item" data-parent="#year_'.$x.'_mer">'.$value.'</a>';
                      }
                    echo '</div>';

                    echo '<a href="#year_'.$x.'_ipcr" class="list-group-item" data-toggle="collapse" data-parent="#year_'.$x.'_ipcr">IPCR<i class="fa fa-caret-down float-right"></i></a>
                    <div class="collapse list-group-submenu" id="year_'.$x.'_ipcr"><a href="#" class="list-group-item" data-parent="#year_'.$x.'_ipcr">ALL DIVISION</a>';
                      
                      foreach(getDivisionList() AS $divisions)
                      {
                          echo '<a href="#" class="list-group-item" data-parent="#year_'.$x.'_ipcr">'.$divisions->division_acro.'</a>';
                      }

                    echo '</div>';

                    echo '<a href="#year_'.$x.'_saln" class="list-group-item" data-toggle="collapse" data-parent="#year_'.$x.'_ipcr">SALN<i class="fa fa-caret-down float-right"></i></a>
                    <div class="collapse list-group-submenu" id="year_'.$x.'_saln"><a href="#" class="list-group-item" data-parent="#year_'.$x.'_saln">ALL DIVISION</a>';
                      
                      foreach(getDivisionList() AS $divisions)
                      {
                          echo '<a href="#" class="list-group-item" data-parent="#year_'.$x.'_saln">'.$divisions->division_acro.'</a>';
                      }

                    echo '</div>';

                  echo '</div>';
            }

            ?>




        </div>
      </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>


      <div class="modal fade" id="modal-alert">
        <div class="modal-dialog modal-lg" id="modal-size">
          <div class="modal-content">

            <div class="overlay d-flex justify-content-center align-items-center" id="overlay_action" style="display: none !important">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>

            <div class="modal-header">
              <h4 class="modal-title"><i id="modal-icon" class="fas" ></i></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
              <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('submission/create') }}">   -->
              {{ csrf_field() }}
              <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
              <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('submission/list') }}">
              <input type="hidden" name="tblid" id="tblid" value="">

              <div class="div-content" id="div-content-create">
                <table class="table table-borderless">
                  <thead>
                    <th style="width: 5%"></th>
                    <th>Type of Report</th>
                    <th>Remarks</th>
                    <th class="text-center">Deadline</th>
                  </thead>
                  <tbody>
                    @if(checkIfTrainingExistReport() > 0)
                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_train_report" value="Training Report"></td>
                      <td class="inactive_text" id="td_check_report_train_report">Training Report</td>
                      <td>
                        <input type="text" class="form-control" name="check_report_remarks[]" id="remarks_check_report_train_report" disabled value="">
                      </td>
                      <td>
                        <i>Auto generated</i>
                        <input type="date" class="form-control" id="deadline_check_report_train_report" name="deadline_check_report[]" readonly style="display: none">
                      </td>
                    </tr>
                    @endif

                    @if(checkIfTrainingExistCertificate() > 0)
                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_train_cert" value="Training Certificate"></td>
                      <td class="inactive_text" id="td_check_report_train_cert"">Training Certificate</td>
                      <td>
                        <input type="text" class="form-control" name="check_report_remarks[]" id="remarks_check_report_train_cert" disabled value="">
                      </td>
                      <td>
                        <i>Auto generated</i>
                        <input type="date" class="form-control" id="deadline_check_report_train_report" name="deadline_check_report[]" readonly style="display: none">
                      </td>
                    </tr>
                    @endif
                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_mar" value="Monthly Accomplishment Report"></td>
                      <td class="inactive_text" id="td_check_report_mar">Monthly Accomplishment Report</td>
                      <td>
                        <select class="form-control" name="check_report_remarks[]" id="remarks_check_report_mar" disabled>
                          <option value="January {{ date('Y') }}">January {{ date('Y') }}</option>
                          <option value="February {{ date('Y') }}">February {{ date('Y') }}</option>
                          <option value="March {{ date('Y') }}">March {{ date('Y') }}</option>
                          <option value="April {{ date('Y') }}">April {{ date('Y') }}</option>
                          <option value="May {{ date('Y') }}">May {{ date('Y') }}</option>
                          <option value="June {{ date('Y') }}">June {{ date('Y') }}</option>
                          <option value="July {{ date('Y') }}">July {{ date('Y') }}</option>
                          <option value="August {{ date('Y') }}">August {{ date('Y') }}</option>
                          <option value="September {{ date('Y') }}">September {{ date('Y') }}</option>
                          <option value="October {{ date('Y') }}">October {{ date('Y') }}</option>
                          <option value="November {{ date('Y') }}">November {{ date('Y') }}</option>
                          <option value="December {{ date('Y') }}">December {{ date('Y') }}</option>
                        </select>
                      </td>
                      <td><input type="date" class="form-control" id="deadline_check_report_mar" name="deadline_check_report[]" disabled></td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_mer" value="Monthly Executive Report"></td>
                      <td class="inactive_text" id="td_check_report_mer">Monthly Executive Report</td>
                      <td>
                        <select class="form-control" name="check_report_remarks[]" id="remarks_check_report_mer" disabled>
                          <option value="January {{ date('Y') }}">January {{ date('Y') }}</option>
                          <option value="February {{ date('Y') }}">February {{ date('Y') }}</option>
                          <option value="March {{ date('Y') }}">March {{ date('Y') }}</option>
                          <option value="April {{ date('Y') }}">April {{ date('Y') }}</option>
                          <option value="May {{ date('Y') }}">May {{ date('Y') }}</option>
                          <option value="June {{ date('Y') }}">June {{ date('Y') }}</option>
                          <option value="July {{ date('Y') }}">July {{ date('Y') }}</option>
                          <option value="August {{ date('Y') }}">August {{ date('Y') }}</option>
                          <option value="September {{ date('Y') }}">September {{ date('Y') }}</option>
                          <option value="October {{ date('Y') }}">October {{ date('Y') }}</option>
                          <option value="November {{ date('Y') }}">November {{ date('Y') }}</option>
                          <option value="December {{ date('Y') }}">December {{ date('Y') }}</option>
                        </select>
                      </td>
                      <td><input type="date" class="form-control" id="deadline_check_report_mer" name="deadline_check_report[]" disabled></td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_saln" value="SALN"></td>
                      <td class="inactive_text" id="td_check_report_saln">SALN</td>
                      <td>
                        <select class="form-control" name="check_report_remarks[]" id="remarks_check_report_saln" disabled>
                          <?php
                            $now = date('Y');
                            $initial = $now - 5;
                            for ($i=$initial; $i < $now; $i++) { 
                              echo "<option value='$i'>$i</option>";
                            }
                          ?>
                        </select>
                      </td>
                      <td><input type="date" class="form-control" id="deadline_check_report_saln" name="deadline_check_report[]" disabled></td>
                    </tr>

                    <tr>
                      <td><input type="checkbox" class="chck" name="check_report[]" id="check_report_ipcr" value="SALN"></td>
                      <td class="inactive_text" id="td_check_report_ipcr">IPCR</td>
                      <td>
                        <select class="form-control" name="check_report_remarks[]" id="remarks_check_report_ipcr" disabled>
                          <option value="January-June {{ (date('Y')) - 1 }}">January-June {{ (date('Y')) - 1 }}</option>
                          <option value="July-December {{ (date('Y')) - 1 }}">July-December {{ (date('Y')) - 1 }}</option>
                        </select>
                      </td>
                      <td><input type="date" class="form-control" id="deadline_check_report_ipcr" name="deadline_check_report[]" disabled></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="div-content" id="div-content-update" style="display: none !important">
                <strong>REPORT</strong>
                <select class="form-control" name="update_report_type" id="update_report_type">
                    <option value="Monthly Accomplishment Report">Monthly Accomplishment Report</option>  
                    <option value="Monthly Executive Report">Monthly Executive Report</option>  
                    <option value="SALN">SALN</option>  
                    <option value="IPCR">IPCR</option>  
                </select>
                <br>

                <strong>REMARKS</strong>
                <input type="text" class="form-control" name="update_report_remarks" id="update_report_remarks">

                <br>
                <strong>DEADLINE</strong>
                <input type="date" class="form-control" name="update_report_deadline" id="update_report_deadline">
              </div>
              
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

  $("#remarks_check_report_mar,#remarks_check_report_mer").val("{{ date('F Y') }}");
  $("#remarks_check_report_saln").val("{{ (date('Y')) - 1 }}")


  $(".chck").change(function(){
    $(".td_text").addClass('inactive_text');
      chckBox(this.id,$(this).is(':checked'));
  });

  function chckBox(id,state)
  {
    $("#td_"+id).removeClass('active_text').addClass('inactive_text');
    $("#deadline_"+id).prop('disabled',true);
    $("#remarks_"+id).prop('disabled',true);

    if(state == true)
    {
      $("#td_"+id).removeClass('inactive_text').addClass('active_text');
      $("#deadline_"+id).prop('disabled',false);
      $("#remarks_"+id).prop('disabled',false);
    }
  }

  function modalFunction(type,id = null)
  {
    $(".div-content").hide();
    $("#tblid").val(id);
    
    switch(type)
    {
      case "create-submission":
        $('#modal-alert').modal({
            toggle: true,
            backdrop: 'static',
            keyboard: false
            });

        $("#frm_url_action").val("{{ url('submission/create') }}");
        $("#div-content-create").show();
        $("#div-content-update").hide();
        $("#modal-size").removeClass("modal-sm").addClass("modal-lg");
        $("#modal-icon").removeClass("fa-edit").addClass("fa-bullhorn");
      break;

      case "edit-submission":
        $('#modal-alert').modal({
            toggle: true,
            backdrop: 'static',
            keyboard: false
            });

        $("#frm_url_action").val("{{ url('submission/update') }}");
        $("#div-content-create").hide();
        $("#div-content-update").show();
        $("#modal-size").removeClass("modal-lg").addClass("modal-sm");
        $("#modal-icon").removeClass("fa-bullhorn").addClass("fa-edit");

        $.getJSON( "{{ url('submission/json') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                          $("#update_report_type").val(obj.sub_report); 
                          $("#update_report_remarks").val(obj.sub_remarks); 
                          $("#update_report_deadline").val(obj.sub_deadline);           
                    });
            }).fail(function() {
            });

      break;
      case "delete-submission":
        $("#div-content-create").show();
        $("#frm_url_action").val("{{ url('submission/delete') }}");
        $("#frm").submit();
      break;
    }   
  }

  function showContent(type)
  {
    switch(type)
    {
        case "Training Report":
            window.open('{{ url("submission/list/training-report") }}', '_blank');
        break;

        case "Training Certificate":
            window.open('{{ url("submission/list/training-certificate") }}', '_blank');
        break;
    }
  }
</script> 
@endsection