@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-12"  style="cursor: pointer;" onclick="window.open('{{ url('vacant-position') }}')">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Vacant Position</span>
                <span class="info-box-number">{{ getNotification('vacant') }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
</div>
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">RECRUITMENT</h3>
            <!-- /.card-header -->
          </div>
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                        <th>Item</th>
                        <th>Position</th>
                        <th style="width: 10%" class="text-center">Status</th>
                        <th style="width: 10%" class="text-center">Letter of Request</th>
                        <th style="width: 10%" class="text-center">Vacancy Advice</th>
                        <th style="width: 10%" class="text-center">Summary of Applicants</th>
                        <th class="text-center">Applicants</th>
                        <th class="text-center">Invites</th>
                        <th>Remarks</th>
                        <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['request_list'] AS $lists)
                      @if($lists->request_status != 'Disapproved' || $lists->request_status != 'Closed')

                          <tr>
                            <td></td>
                            <td>
                                {{ getPlantillaItemInfo('number',$lists->plantilla_id) }}
                              </td>
                              
                              <td>
                                {{ getPlantillaItemInfo('position',$lists->plantilla_id) }}
                              </td>
                            <td class="text-center">{{ formatStatus($lists->request_status) }}</td>
                            <td class="text-center">{{ getFile('hiring','Letter of Request',$lists->id) }}</td>
                            <td class="text-center">
                                <?php getFile('hiring','Vacancy Advice',$lists->id) ?>
                            </td>
                            <td class="text-center">{{ getFile('hiring','Summary of Applicants',$lists->id) }}</td>
                            <td class="text-center">{{ getApplicant('count',$lists->plantilla_id,$lists->id,$lists->request_status) }}</td>
                            <td class="text-center">{{ counInvites($lists->plantilla_id) }}</td>
                            <td>{{ $lists->request_desc }}</td>
                            <td>
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-list"></i>
                                    </button>
                              @if($lists->request_status == 'Received'  || $lists->request_status == 'Re-upload Vacancy Advise for Reposting')
                              
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" style="cursor: not-allowed;color: #999;" onclick="return false;"><i class="fas fa-thumbs-up"></i> Waiting for Vacancy Advise</a>

                              @elseif($lists->request_status == 'Cleared from OED' || $lists->request_status == 'Re-upload Vacancy Advise for Reposting')
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" onclick="modalOption('hiring','upload-vacancy',{{ $lists->id }})"><i class="fas fa-upload"></i> Upload Signed Letter Request/Vacancy Advice</a>
                              
                              @elseif($lists->request_status == 'Vacancy Advice Uploaded')
                                @if($lists->plantilla_id != null)
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a class="dropdown-item" href="http://122.2.24.207/helpdesk/public/" target="_blank"><i class="fas fa-hands-helping"></i> Request for Posting</a>
                                      <a class="dropdown-item" href="#" onclick="actionRequest('posted','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-check"></i> Post Vacancy</a>
                                      <a class="dropdown-item" href="#" onclick="actionRequest('reupload','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-file-upload"></i> Re-upload Vacancy Advise</a>
                                @else
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a class="dropdown-item" href="http://122.2.24.207/helpdesk/public/" target="_blank"><i class="fas fa-hands-helping"></i> Request for Posting</a>
                                      <a class="dropdown-item" href="#" onclick="actionRequest('approve','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-check"></i> Assign Item</a>
                                      <a class="dropdown-item" href="#" onclick="actionRequest('reupload','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-file-upload"></i> Re-upload Vacancy Advise</a>
                                @endif
                                    
                               @elseif($lists->request_status == 'Vacancy Posted')
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" aria-labelledby="dropdownMenuButton" href="{{ url('recruitment/list-of-applicants/'.$lists->plantilla_id.'/'.$lists->id) }}"><i class="fas fa-list"></i> Initial Screening</a>
                                        <a class="dropdown-item" aria-labelledby="dropdownMenuButton" href="#" onclick="callInvite({{ $lists->plantilla_id }})"><i class="fas fa-bullhorn"></i> Call for Invitation</a>
                              @elseif($lists->request_status == 'Pending')
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" onclick="actionRequest('receive','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-check"></i> Receive</a>
                              @elseif($lists->request_status == 'Division shortlisted applicants')
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" onclick="actionRequest('send-to-psb','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-check"></i> Send list to PSB</a>
                              @elseif($lists->request_status == 'Sent to PSB')
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" onclick="actionRequest('upload-psb-result','{{ $lists->plantilla_id }}',{{ $lists->id }})"><i class="fas fa-check"></i> Upload PSB Result</a>
                              @elseif($lists->request_status == 'Uploaded PSB Result')
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ url('recruitment/list-of-applicants/'.$lists->plantilla_id.'/'.$lists->id) }}"><i class="fas fa-user"></i> Select Applicant</a>
                              @else
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">      
                              @endif

                                  <a class="dropdown-item" href="{{ url('recruitment/history/'.$lists->id) }}" target="_blank"><i class="fas fa-history"></i> History</a>
                                  <a class="dropdown-item" href="#" onclick="actionRequest('disapprove','{{ $lists->plantilla_id }}',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i> Disapprove</a>
                                </div>
                            </td>
                          </tr>
                      
                      @endif
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

      <div class="modal fade" id="modal-request-approve">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('request-for-hiring/action') }}"> -->
          
            {{ csrf_field() }}
            <input type="hidden" name="status" id="status">
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="plantilla_id" id="plantilla_id" value="">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('recruitment/index') }}">

            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <strong>Plantilla Item Number</strong>
                  <input type="text" class="form-control" name="plantilla_item_number" id="plantilla_item_number">
                </div>
                <div class="col-md-12">
                  <strong>Position Title</strong>
                  <select class="form-control select2" id="position" name="position">
                    <option value=""></option>
                    @foreach($data['position'] as $positions)
                        <option value="{{ $positions->position_id }}">{{ $positions->position_desc }}</option>
                                  @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <strong>Division</strong>
                  <select class="form-control select2" id="division" name="division">
                    <option value=""></option>
                    @foreach($data['division'] as $divisions)
                        <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <strong>Salary</strong>
                  <input type="text" class="form-control" name="plantilla_salary" id="plantilla_salary">
                </div>
                <div class="col-md-12">
                  <strong>Steps</strong>
                  <select class="form-control" id="plantilla_steps" name="plantilla_steps">
                                  <option value="" selected></option>
                                  @for ($i = 1; $i <= 9;$i++)
                                      <option value="{{ $i }}">{{ $i }}</option>
                                  @endfor
                                </select> 
                </div>
                <div class="col-md-12">
                  <br>
                  <strong>Special</strong>
                  <input type="radio" name="plantilla_special" id="plantilla_special_yes" value="1"> YES <input type="radio" name="plantilla_special" id="plantilla_special_no" value=""> NO 
                </div>
              </div>
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



<div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Upload PSB Result</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form">   -->
            <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request-for-hiring/upload-psb') }}">  
            {{ csrf_field() }}
            <input type="hidden" name="request_id" id="request_id" value="">

            <div class="modal-body">
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="psb_file" id="customFile2">
                            <label class="custom-file-label" for="customFile2">Choose file</label>
                    </div>
                  </div>
            
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Upload</button>
              </form>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

<div class="modal fade" id="modal-send-to-psb">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Send to PSB</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form">   -->
            <form method="POST" id="frm3" enctype="multipart/form-data" action="{{ url('request-for-hiring/send-to-psb') }}">  
            {{ csrf_field() }}
            <input type="hidden" name="tblid2" id="tblid2" value="">
            <input type="hidden" name="plantilla_id2" id="plantilla_id2" value="">

            <div class="modal-body">
                    <div class="form-group">
                    <strong>Deadline</strong>
                    <p><input type="date" class="form-control" name="deadline" id="deadline"></p>
                  </div>
            
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Send</button>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-vacancy">
  <div class="modal-dialog" style="max-width: 30% !important">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Signed Letter of Request/Vacancy Advice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_vacancy" enctype="multipart/form-data" role="form" action="{{ url('recruitment/upload/vacancy-advice') }}">
            {{ csrf_field() }}
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="plantillaid" id="plantillaid" value="">
            <input type="hidden" name="letterid" id="letterid" value="">
            <input type="hidden" name="letterstatus" id="letterstatus" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('recruitment/upload/vacancy-advice') }}">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('recruitment/index') }}">
            <strong><u>Signed</u> Letter of Request</strong>
                    <div class="form-group">
                      <div class="custom-file">
                              <input type="file" class="custom-file-input" name="request_letter" id="customFile2">
                              <label class="custom-file-label" for="customFile2">Choose file</label>
                      </div>
                    </div>
            <strong><u>Signed</u> Vacancy Advice</strong>
                    <div class="form-group">
                      <div class="custom-file">
                              <input type="file" class="custom-file-input" name="request_vacancy" id="customFile">
                              <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                    </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submitUpload()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
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


  function modalOption(type,act,id,id2 = null)
  {
    $("#tblid").val(id);
    $("#letterid").val(id);
    $("#letterstatus").val(act);
    $("#modal-vacancy").modal('toggle'); 
  }

  function actionRequest(act,plantilla_id,id)
  {
    $("#tblid,#tblid2").val(id);
    $("#plantilla_id,#plantilla_id2").val(plantilla_id);

    if(act == 'send-to-psb')
    {
      $("#modal-send-to-psb").modal('toggle');
    }
    else
    {
      if(act == 'approve')
      {
        $("#frm_url_action").val("{{ url('request-for-hiring/approve') }}");
        $("#modal-request-approve").modal('toggle');
      }
      else if(act == 'upload-psb-result')
      {
        $("#request_id").val(id);
        $("#modal-upload").modal('toggle');
      }
      else
      {
        $("#frm_url_action").val("{{ url('request-for-hiring/action') }}");
        $("#status").val(act);
        $("#frm").submit();
      }
    }

    
  }
  function callInvite(id)
  {
    window.location.href = "{{ url('invitation/select') }}/"+id;
   
  }
  function modalOnSubmit()
  {
    $("#frm").submit();
  }

  function submitUpload()
  {
    $("#overlay").show();
    $("#frm_vacancy").submit();
  }
</script>
@endsection