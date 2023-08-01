@extends('template.master')

@section('CSS')
  <link rel="stylesheet" href="{{ asset('multidate/bootstrap-datepicker.css') }}">
@endsection

@section('content')
<div class="float-right"><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-request"><i class="fas fa-plus"></i>NEW REQUEST</a></div>
<br>
<br>
<div class="row">
        <div class="col-12">
          <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-hiring" role="tab" aria-controls="tabs-hiring" aria-selected="true">Fill-up Vacant Position</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs-training-request" role="tab" aria-controls="tabs-training" aria-selected="true">Training</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tabs-training" role="tab" aria-controls="tabs-training" aria-selected="false">Non-Degree</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#tabs-hrd" role="tab" aria-controls="tabs-hrd" aria-selected="false">HRD Plan</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="tabs-hiring" role="tabpanel" aria-labelledby="tabs-hiring-tab">
                    
                    <br>
                    <br>
                    <table id="tbl" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Item</th>
                        <th>Position</th>
                        <th style="width: 10%" class="text-center">Status</th>
                        <th style="width: 15%" class="text-center">Letter of Request</th>
                        <th style="width: 15%" class="text-center">Vacancy Advice</th>
                        <th style="width: 10%" class="text-center" id="td-applicants" style="display: none">Applicants</th>
                        <th style="width: 7%"></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($data['request_list'] AS $lists)
                            <tr>
                              <td></td>
                              <td>
                                @if($lists->request_seen == null)
                                    <div class="badge-alert"><span class="badge badge-primary">Updated</span><br></div>
                                @endif
                                {{ getPlantillaItemInfo('number',$lists->plantilla_id) }}
                              </td>
                              
                              <td>
                                {{ getPlantillaItemInfo('position',$lists->plantilla_id) }}
                              </td>
                              <td align="center">{{ formatStatus($lists->request_status) }}</td>
                              <td align="center">{{ getFile('hiring','Letter of Request',$lists->id) }}</td>
                              <td align="center">
                                    {{ getFile('hiring','Vacancy Advice',$lists->id) }}
                              </td>
                              <td class="text-center">
                                @if($lists->request_status == 'FAD shortlisted applicants' || $lists->request_status == 'Division shortlisted applicants')
                                  {{ getApplicant('count',$lists->plantilla_id,$lists->id,$lists->request_status) }}
                                @else
                                  <small class="text-muted">-</small>
                                @endif
                              </td>
                              <td align="center">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-list"></i>
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if(!isset($lists->request_disapproved))
                                @if($lists->request_status == 'FAD shortlisted applicants')
                                  <a class="dropdown-item" href="{{ url('recruitment/list-of-applicants/'.$lists->plantilla_id.'/'.$lists->id ) }}" ><i class="fas fa-list"></i> Shortlist</a>
                                @endif

                                
                                
                                <a class="dropdown-item" href="{{ url('recruitment/history/'.$lists->id) }}" target="_blank"><i class="fas fa-history"></i> History</a>
                                  <a class="dropdown-item" href="#" onclick="modalOption('hiring','edit-request',{{ $lists->id }})"><i class="fas fa-trash"></i> Edit</a>

                                  @if($lists->request_status == 'Posted')
                                    <a class="dropdown-item" href="#" onclick="modalOption('hiring','repost-request',{{ $lists->id }},{{ $lists->plantilla_id }})" style="color:orange"><i class="fas fa-undo"></i> Repost</a>

                                  @endif

                                  <a class="dropdown-item" href="#" onclick="modalOption('hiring','delete-request',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i> Delete</a>

                                @else
                                 <a class="dropdown-item" href="{{ url('recruitment/history/'.$lists->id) }}" target="_blank"><i class="fas fa-history"></i> History</a>
                                  <a class="dropdown-item" href="#" onclick="modalOption('hiring','delete-request',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i> Delete</a>

                                 </div>
                              </td>
                                @endif
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="tab-pane fade show" id="tabs-training-request" role="tabpanel" aria-labelledby="tabs-hiring-tab">
                    
                    <br>
                    <br>
                    <table id="tbl4" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Title</th>
                        <th>Staff</th>
                        <th>Conducted by</th>
                        <th style="width: 10%" class="text-center">Status</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($data['training_request'] AS $lists)
                              <tr>
                                <td></td>
                                <td>{{ $lists->training_title }}</td>
                                <td>{{ getStaffInfo($lists->user_id) }}</td>
                                <td>{{ $lists->training_conducted_by }}</td>
                                <td>{{ $lists->training_status }}</td>
                              </tr>
                            @endforeach
                      </tbody>
                    </table>
                  </div>



                  <div class="tab-pane fade" id="tabs-training" role="tabpanel" aria-labelledby="tabs-training-tab">
                    
                    <div class="row">
                      <div class="col-9">
                       <table id="tbl3" class="table table-bordered table-striped" style="font-size: 12px">
                        <thead>
                        <tr>
                          <th style="width: 2%">#</th>
                          <th>Staff</th>
                          <th>Title</th>
                          <th>Conducted By</th>
                          <th>Type</th>
                          <th>Amount</th>
                          <th>Hours</th>
                          <th>Areas of Discipline</th>
                          <th>LD</th>
                          <th>Training Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data['training_list'] AS $lists)
                              <tr>
                                <td></td>
                                <td>{{ getStaffInfo($lists->user_id) }}</td>
                                <td>{{ $lists->training_title }}</td>
                                <td>{{ $lists->training_conducted_by }}</td>
                                <td>{{ $lists->training_type }}</td>
                                <td>{{ $lists->training_amount }}</td>
                                <td>{{ $lists->training_hours }}</td>
                                <td>{{ $lists->areas_of_discipline }}</td>
                                <td>{{ $lists->training_ld }}</td>
                                <td>{{ $lists->training_inclusive_dates }}</td>
                              </tr>
                            @endforeach
                        </tbody>
                      </table>
                      </div>

                      <div class="col-3">
                        <div class="card">
                          <div class="card-body">
                            <strong>List of staff total training</strong>
                            <table class="table table-bordered">
                              @foreach(getStaffDivision() AS $lists)
                                <tr>
                                  <td>{{ $lists->lname . "," .$lists->fname . " " . $lists->mname}}</td>
                                  <td style="width: 10%">
                                    {{ getTraining($lists->id) }}
                                  </td>
                                </tr>
                              @endforeach
                            </table>
                          </div>
                        </div>
                      </div>

                  </div>
                  </div>

                  <div class="tab-pane fade" id="tabs-hrd" role="tabpanel" aria-labelledby="tabs-hrd-tab">
                    <table id="tbl2" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 2%">#</th>
                        <th>Year</th>
                        <th>Deadline</th>
                        <th>Date Uploaded</th>
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($data['hrd_list'] AS $hrds)
                          <tr>
                            <td style="width: 2%"></td>
                            <td>{{ $hrds->hrd_year }}</td>
                            <td>{{ $hrds->hrd_deadline }}</td>
                            <td>{{ $hrds->hrd_file_uploaded }}</td>
                            <td class="text-center" style="width: 2%"><a href="{{ url('learning-development/hrd-plan/'.$hrds->id.'/'.$hrds->hrd_plan_id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> view</a></td>
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


      <div class="modal fade" id="modal-request">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request-for-hiring/create') }}">   -->
            {{ csrf_field() }}
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="plantillaid" id="plantillaid" value="">
            <input type="hidden" name="letterid" id="letterid" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('letter-request') }}">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('request-for-hiring/create') }}">

            <div class="modal-body">
              <div class="row" id="option-select">
                <div class="col-md-12">
                  <strong>Request for</strong><br>
                  <input type="radio" class='type-letter' name="typeletter" value="1" checked> Fill-up Vacant Position <input type="radio" class='type-letter' name="typeletter" value="2"> Training 
                </div>
              </div>
              <br>

              <div class="div-content" id="div-content-hiring">

                <div class="row">
                  <div class="col-md-12">
                    <strong>Vacant Position</strong><br>
                      <select class="form-control" name="plantilla_id" id="plantilla_id">
                        @foreach($data['vacant'] AS $vacants)
                          <option value="{{ $vacants->id }}">{{ $vacants->division_acro.' / '.$vacants->position_desc .' / '.$vacants->plantilla_item_number }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>

                <div class="row">
                  
                  <div class="col-md-12">
                    <strong>Letter of Request</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="request_attachment" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                  </div>
                  </div>

                  <div class="col-md-12">
                    <strong>Vacancy Advice</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="request_vacancy" id="customFile2">
                            <label class="custom-file-label" for="customFile2">Choose file</label>
                    </div>
                  </div>
                  </div>
              </div>
            </div>


              <div class="div-content" id="div-content-training" style="display: none">
                <div class="row">
                  <div class="col-md-12">
                    <strong>Staff Attending</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control" name="user_id">
                            @foreach(getStaffDivision() AS $value)
                              <option value="{{ $value->id }}">{{ $value->lname . ", " . $value->fname . " " . $value->mname }}</option>
                            @endforeach
                          </select>
                        </p>
                    <strong>Area of Discipline</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control" name="hrd_degree_area" id="hrd_degree_area">
                             <option value="Management/ Supervisory/ Leadership">Management/ Supervisory/ Leadership</option>
                             <option value="R&D Related Trainings">R&D Related Trainings</option>
                             <option value="Skills Enhancement">Skills Enhancement</option>
                             <option value="Information & Communication Technology (ICT)">Information & Communication Technology (ICT)</option>
                             <option value="Information, Education & Communication (IEC)">Information, Education & Communication (IEC)</option>
                             <option value="Value Enhancement">Value Enhancement</option>
                             <option value="General Administration/ Governance">General Administration/ Governance</option>
                             <option value="Others">Others</option>
                           </select>
                        </p>

                    <strong>Title of Seminar/Training</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control training" name="training_title" id="training_title" placeholder="" autocomplete="off">
                        </p>
                    <strong>Conducted By</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control training" name="training_conducted_by" id="training_conducted_by" placeholder="" autocomplete="off">
                        </p>
                    <strong>Inclusive Dates</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control date training" name="training_inclusive_date" id="training_inclusive_date" autocomplete="off"/>
                        </p>
                    
                  </div>
                  <div class="col-md-6">
                    <strong>Type</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control training" name="training_type" id="training_type">
                            <option value="Free">Free</option>
                            <option value="Funded">PCAARRD Funded</option>
                            <option value="Personal">Personal</option>
                          </select>
                        </p>
                  </div>
                  <div class="col-md-6" id="div-amount" style="display: none">
                      <strong>Amount</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" name="training_amount" id="training_amount" class="form-control training">
                        </p>
                  </div>
                  <div class="col-md-6">
                    <strong>Training Hours</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" class="form-control training" name="training_hours" id="training_hours" autocomplete="off"/>
                        </p>
                  </div>
                  <div class="col-md-12">
                    <strong>LD</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control select2 training" name="training_ld[]" id="training_ld" multiple>
                            <option value="Managerial">Managerial</option>
                            <option value="Supervisory">Supervisory</option>
                            <option value="Technical">Technical</option>
                          </select>
                        </p>
                  </div>
                  <div class="col-md-12">
                    <strong>Attachment</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="training_attachment" id="customFile2">
                            <label class="custom-file-label" for="customFile2">Choose file</label>
                    </div>
                  </div>
                </div>
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
        <div class="modal-dialog" style="max-width: 80% !important">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm2" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request-for-hiring/create') }}">   -->
            {{ csrf_field() }}
            <div class="modal-body">
              
              </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submitUpload()">Save changes</button>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>

<div class="modal" tabindex="-1" role="dialog" id="modal-edit-request">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">EDIT LETTER REQUEST</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" action="{{ url('dtr/update') }}">  
          {{ csrf_field() }}
          <input type="hidden" name="request_letter_id_update" id="request_letter_id_update" value="">
          <div class="row">
                  <div class="col-md-12">
                    <strong>Vacant Position</strong><br>
                      <select class="form-control" name="plantilla_id_update" id="plantilla_id_update">
                        @foreach($data['vacant'] AS $vacants)
                          <option value="{{ $vacants->id }}">{{ $vacants->division_acro.' / '.$vacants->position_desc .' / '.$vacants->plantilla_item_number }}</option>
                        @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-12">
                    <strong>Letter of Request (Upload new file)</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="request_attachment_update" id="customFile_update">
                            <label class="custom-file-label" for="customFile_update">Choose file</label>
                    </div>
                  </div>
                  </div>

                  <div class="col-md-12">
                    <strong>Vacancy Advice (Upload new file)</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="request_vacancy_update" id="customFile2_update">
                            <label class="custom-file-label" for="customFile2_update">Choose file</label>
                    </div>
                  </div>
                  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
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


  var t2 = $("#tbl2").DataTable();

    t2.on('order.dt search.dt', function () {
      t2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();


    var t3 = $("#tbl3").DataTable();

    t3.on('order.dt search.dt', function () {
      t3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();


  var t4 = $("#tbl4").DataTable();

    t4.on('order.dt search.dt', function () {
      t4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });



  $('.date').datepicker({
        multidate: true,
        format: "yyyy-mm-dd",
        });

  $(".type-letter").change(function(){
    $(".div-content").hide();
    console.log(this.value);
    switch(this.value)
    {
      case "1":
        $("#div-content-hiring").show();
        $("#frm_url_action").val("{{ url('request-for-hiring/create') }}");
      break;

      case "2":
        $("#div-content-training").show();
        $("#frm_url_action").val("{{ url('training/request/create') }}");
      break;

    }
  });


  
    $(".hiring_type").change(function(){
    $("#vacant").hide();
    console.log(this.value);
    switch(this.value)
    {
      case "2":
        $("#vacant").show();
      break;
    }
  });


  $("#training_type").change(function(){
    $("#div-amount").hide();
    if(this.value == "Funded"){
      $("#div-amount").show();
    }
  });

  function modalOption(type,act,id,id2 = null)
  {
    $("#tblid").val(id);
    $(".div-content").hide();
    if(type == 'hiring')
    {
      $("#option-select").hide();
      switch(act)
      {
        case 'edit-request':
          $("#modal-edit-request").modal('toggle');
          $("#reques_letter_id_update").val(id)
          $.getJSON( "{{ url('request-for-hiring/json') }}/"+id, function( datajson ) {
                  
                }).done(function(datajson) {
                  jQuery.each(datajson,function(i,obj){
                           $("#plantilla_id_update").val(obj.id);
                      });
              }).fail(function() {
              });
        break;

        case 'repost-request':
          $("#plantillaid").val(id2);
          $("#letterid").val(id);

          $("#frm_url_action").val("{{ url('request-for-hiring/repost') }}");
          $("#frm").submit();

        break;

        case 'delete-request':
          $("#frm_url_action").val("{{ url('request-for-hiring/delete') }}");
          $("#frm").submit();
        break;

        case 'upload-vacancy':
          $("#modal-request").modal('toggle');
          $("#frm_url_action").val("{{ url('recruitment/upload/vacancy-advise') }}");
          $("#div-content-vacancy").show();
        break;
      }
    }
    else
    {
      $("#frm_url_action").val("{{ url('request-for-training/delete') }}");
      $("#frm").submit();
    }
    
  }

  setInterval(function() {
      $(".badge-alert").fadeOut(1000);
    }, 5000);

  function modalOnSubmit()
  {
    $("#frm").submit();
  }

  $.getJSON( "{{ url('request-for-hiring-alert/clear') }}", function( datajson ) {
              }).done(function(datajson) {
            }).fail(function() {
     });


  function modalUpload(type,id = null)
  {
    switch(type){
      case 'hrd':
        window.location = "{{ url('learning-development/hrd-plan') }}/"+id;
      break;
    }
    
  }

  function submitUpload()
  {
    $("#frm2").submit();
  }
</script>
@endsection