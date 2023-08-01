@extends('template.master')

@section('CSS')
<style type="text/css">
    table,tr,td
    {
      padding: 5px !important;
    }
</style>
@endsection

@section('content')

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">HRD PLAN </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                        <div class="col-12">
                          <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                              <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="tab-degree" data-toggle="pill" href="#tab-degree-tab" role="tab" aria-controls="tab-degree-tab" aria-selected="true">Degree</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Non-Degree</a>
                                </li>
                              </ul>
                            </div>
                            <div class="card-body">
                              <div class="tab-content" id="custom-tabs-three-tabContent">
                                

                                <div class="tab-pane fade show active" id="tab-degree-tab" role="tabpanel" aria-labelledby="tab-degree-tab">
                                  <div class="float-right">

                                    <a href="{{ url('learning-development/print/hrd-plan-degree/'.$data['hrd_degree_id']) }}" class="btn btn-primary btn-success btn-sm" target="_blank"><i class="fas fa-print"></i></a>

                                  </div>
                                  @if(checkStatusHRD($data['hrd_degree_id']))
                                    @if(checkUserHRD('degree',Auth::user()->id,$data['hrd_plan_id']))
                                      <div class="float-right" style="margin-right: 5px"><button class="btn btn-primary btn-warning btn-sm"><i class="fas fa-lock"></i></button></div>
                                    @else
                                      <div class="float-right" style="margin-right: 5px"><button class="btn btn-primary btn-primary btn-sm" onclick="addRecord('degree')"><i class="fas fa-plus"></i></button></div>
                                    @endif
                                  @else
                                      <div class="float-right" style="margin-right: 5px"><button class="btn btn-primary btn-warning btn-sm"><i class="fas fa-lock"></i></button></div>
                                  @endif
                                <br>
                                <br>
                                
                                   <table class="table table-bordered">
                                     <tr>
                                        <td class="text-center" rowspan="2" style="vertical-align: middle;"><b>NAME</b></td>
                                        <td class="text-center" rowspan="2" style="vertical-align: middle;"><b>POSITION</b></td>
                                        <td class="text-center" colspan="2"><small><b>DEGREE PROGRAM</small></b></td>
                                        <td class="text-center" rowspan="2" style="vertical-align: middle;"><b>PROPOSED UNIVERSITY</b></td>
                                        <td class="text-center" colspan="2"><small><b>TARGET DATE (PLS CHECK)</small></b></td>
                                        <td class="text-center" rowspan="2" style="vertical-align: middle;"><b>REMARKS</b></td>
                                     </tr>
                                     <tr>
                                       <td class="text-center"><b><small>PhD</small></b></td>
                                       <td class="text-center"><b><small>MS</small></b></td>
                                       <td class="text-center"><b><small>1st sem of SY</small></b></td>
                                       <td class="text-center"><b><small>2nd  sem of SY</small></b></td>
                                     </tr>

                                     <tbody>
                                      <tr><td colspan="9">A. Local</td></tr>
                                     @foreach($data['list_degree_local'] AS $lists)
                                      
                                      <tr>
                                        <td>{{ $lists->fullname }}</td>
                                        <td>{{ $lists->position_desc }}</td>
                                        @if($lists->hrd_degree_program == 'PhD')
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                          <td></td>
                                        @else
                                          <td></td>
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                        @endif
                                        <td>{{ $lists->hrd_degree_university }}</td>
                                        @if($lists->hrd_degree_area == '1st sem of SY')
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                          <td></td>
                                        @else
                                          <td></td>
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                        @endif
                                        <td>{{ $lists->hrd_degree_remarks }}</td>
                                        <td>
                                          <!-- <span class="fas fa-edit" style="color: blue;cursor: pointer;" onclick="actionHRD('edit',{{ $lists->id}})"></span>  -->

                                          @if(checkUserDelete('degree',$lists->id))
                                          <span class="fas fa-trash" style="color: red;cursor: pointer;" onclick="actionHRD('delete-degree',{{ $lists->id}})"></span></td>
                                          @endif
                                      </tr>
                                     @endforeach

                                    <tr><td colspan="9">B. Foreign</td></tr>
                                     @foreach($data['list_degree_foreign'] AS $lists)
                                      
                                      <tr>
                                        <td>{{ $lists->fullname }}</td>
                                        <td>{{ $lists->position_desc }}</td>
                                        @if($lists->hrd_degree_program == 'PhD')
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                          <td></td>
                                        @else
                                          <td></td>
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                        @endif
                                        <td>{{ $lists->hrd_degree_university }}</td>
                                        @if($lists->hrd_degree_target == '1st sem of SY')
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                          <td></td>
                                        @else
                                          <td></td>
                                          <td class="text-center"><i class="fas fa-check"></i></td>
                                        @endif
                                        <td>{{ $lists->hrd_degree_remarks }}</td>

                                        @if(checkUserDelete('degree',$lists->id))
                                        <td>
                                          <span class="fas fa-trash" style="color: red;cursor: pointer;" onclick="actionHRD('delete-degree',{{ $lists->id}})"></span></td>
                                        @endif
                                          
                                      </tr>
                                     @endforeach
                                   </tbody>

                                   </table> 
                                   
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                  <div class="float-right">

                                    <a href="{{ url('learning-development/print/hrd-plan-non-degree/'.$data['hrd_degree_id']) }}" class="btn btn-primary btn-success btn-sm" target="_blank"><i class="fas fa-print"></i></a>

                                  </div>
                                
                                @if(checkStatusHRD($data['hrd_degree_id']))
                                  <div class="float-right" style="margin-right: 5px"><button class="btn btn-primary btn-primary btn-sm" onclick="addRecord('non-degree')"><i class="fas fa-plus"></i></button></div>
                                @else
                                  <div class="float-right" style="margin-right: 5px"><button class="btn btn-primary btn-warning btn-sm"><i class="fas fa-lock"></i></button></div>
                                @endif
                                
                                <br>
                                <br>
                                   <table class="table table-bordered" style="font-size: 10px">
                                    <tr>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>NAME</b></td>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>POSITION</b></td>
                                      <td align="center" style="vertical-align: middle; width: 5% !important" rowspan="2"><b>TRAINING PRIORITIZATION</b></td>
                                      <td align="center" style="vertical-align: middle;" colspan="8"><small><b>AREA OF DISCIPLINE</b></small></small></td>
                                      <td align="center" style="vertical-align: middle;" colspan="4"><small><b>TARGET DATE</b></small></td>
                                    </tr>
                                     <tr style="font-size: 10px">
                                        
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>MANAGEMENT/ SUPERVISORY/ LEADERSHIP</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>R&D RELATED TRAININGS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>SKILLS ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION & COMMUNICATION TECHNOLOGY (ICT)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION, EDUCATION & COMMUNICATION (IEC)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>VALUE ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>GENERAL ADMINISTRATION/ GOVERNANCE</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>OTHERS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q1</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q2</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q3</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q4</b></td>

                                     </tr>

                                     <tbody>

                                      <tr><td colspan="15">A. Local</td></tr>
                                       @foreach($data['list_non_degree_local'] AS $lists)
                                          <tr>
                                            <td>{{ $lists->fullname }}</td>
                                            <td>{{ $lists->position_desc }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ $lists->hrd_non_degree_priority }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Management/ Supervisory/ Leadership') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'R&d Related Trainings') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Skills Enhancement') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Information & Communication Technology (ict)') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Information, Education & Communication (iec)') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Value Enhancement') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'General Administration/ Governance') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Others') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q1) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q2) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q3) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q4) }}</td>


                                            @if(checkUserDelete('non-degree',$lists->id))
                                            <td style="vertical-align: middle;width: 2%"><span class="fas fa-trash" style="color: red;cursor: pointer;" onclick="actionHRD('delete-non-degree',{{ $lists->id}})"></span></td>
                                            @endif
                                          </tr>
                                       @endforeach


                                       <tr><td colspan="15">B. Foreign</td></tr>
                                       @foreach($data['list_non_degree_foreign'] AS $lists)
                                          <tr>
                                            <td>{{ $lists->fullname }}</td>
                                            <td>{{ $lists->position_desc }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ $lists->hrd_non_degree_priority }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Management/ Supervisory/ Leadership') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'R&d Related Trainings') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Skills Enhancement') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Information & Communication Technology (ict)') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Information, Education & Communication (iec)') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Value Enhancement') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'General Administration/ Governance') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getAreasDiscipline('check',$lists->id,'Others') }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q1) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q2) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q3) }}</td>
                                            <td align="center" style="vertical-align: middle;">{{ getQuarter('hrd',$lists->hrd_non_degree_target_q4) }}</td>

                                            @if(checkUserDelete('non-degree',$lists->id))
                                            <td style="vertical-align: middle;width: 2%"><span class="fas fa-trash" style="color: red;cursor: pointer;" onclick="actionHRD('delete-non-degree',{{ $lists->id}})"></span></td>
                                            @endif
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

                @if(checkStatusHRD($data['hrd_degree_id']))
                <div class="col-12">
                  @if(Auth::user()->usertype == 'Director')

                  <p style="color: red"><i>*Please be reminded that by submitting this form, you are affixing your signature.</i></p>
                    <span class="float-right"><button class="btn btn-info" onclick="actionHRD('submit-hrd-plan',{{ $data['hrd_degree_id'] }})">SUBMIT HRD PLAN</button></span>
                    <span class="float-right" style="margin-right: 1%" onclick="window.location = '{{ url("letter-request") }}'"><button class="btn btn-warning">SAVE AS DRAFT</button></span>
                  @else
                    <!-- <span class="float-right"><button class="btn btn-info" onclick="actionHRD('submit-hrd-plan-staff',{{ $data['hrd_degree_id'] }})">SUBMIT HRD PLAN TO DIRECTOR</button></span> -->
                  @endif
                </div>
                @endif

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
</div>
 </div>     


<div class="modal fade" id="modal-add">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="frm" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="hrd_plan_id" id="hrd_plan_id" value="{{ $data['hrd_plan_id'] }}">
                <input type="hidden" name="hrd_degree_id" id="hrd_degree_id" value="{{ $data['hrd_degree_id'] }}">
                <input type="hidden" name="hrd_plan_degree_id" id="hrd_plan_degree_id">
                <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
                <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">

                <div class="row" id="">

                    <div class="col-md-12 option-hrd">
                      <strong>Staff</strong>
                      <div class="form-group">
                          <input type="hidden" name="hrd_degree_staff" value="{{ Auth::user()->id }}">
                          {{ Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->mname }}
                      </div>

                      <div class="form-group-non-degree">
                        <strong>Training Prioritization</strong>
                        <div class="form-group w-25">
                           <input type="number" class="form-control" name="hrd_non_degree_priority" id="hrd_non_degree_priority">
                        </div>
                      </div>

                      <div class="form-group-degree">
                        <strong>Degree/Program</strong>
                        <div class="form-group">
                           <input type="radio" name="hrd_degree_program" value="PhD"> PhD &nbsp&nbsp
                           <input type="radio" name="hrd_degree_program" value="MS"> MS
                        </div>
                      </div>

                      <strong>Type</strong>
                      <div class="form-group">
                         <input type="radio" name="hrd_degree_type" value="Local" checked> Local &nbsp&nbsp
                         <input type="radio" name="hrd_degree_type" value="Foreign"> Foreign
                      </div>

                      <div class="form-group-degree">
                        <strong>Field/Area of Discipline</strong>
                        <input type="text" class="form-control" name="hrd_degree_area" id="hrd_degree_area">
                      </div> 

                      <div class="form-group-non-degree">
                        <strong>Field/Area of Discipline</strong>
                        <div class="row">
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="Management/ Supervisory/ Leadership"><small> Management/ Supervisory/ Leadership</small>
                          </div>
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="Information, Education & Communication (iec)"> <small>Information, Education & Communication (iec)</small>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="R&d Related Trainings"> <small>R&d Related Trainings</small>
                          </div>
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="Value Enhancement"> <small>Value Enhancement</small>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="Information & Communication Technology (ict)"> <small>Information & Communication Technology (ict)</small>
                          </div>
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="General Administration/ Governance"> <small>General Administration/ Governance</small>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                              <input type="checkbox" name="hrd_non_degree_areas[]" value="Skills Enhancement" class="form-group input-sm"> <small>Skills Enhancement</small>
                          </div>
                        </div>
                        <p>
                          <input type="text" class="form-control input-sm" name="hrd_non_degree_area_others" placeholder="Other Area of Discipline. Pls specify">
                        </p>

                          <br>
                      </div>  

                      <div class="form-group-degree">
                        <strong>Proposed University</strong>
                        <div class="form-group">
                           <input type="text" class="form-control" name="hrd_degree_university" id="hrd_degree_university">
                        </div>
                      </div>

                      <div class="form-group-degree">
                        <strong>Target Date (pls check)</strong>
                        <div class="form-group">
                           <input type="radio" name="hrd_degree_target" value="1st sem of SY"> 1st sem of SY  &nbsp&nbsp
                           <input type="radio" name="hrd_degree_target" value="2nd sem of SY"> 2nd sem of SY 
                        </div>
                      </div>

                      <div class="form-group-non-degree">
                        <strong>Target Date (pls check)</strong>
                        <div class="form-group">
                           <input type="checkbox" name="hrd_non_degree_target_q1" value="1"> Q1 &nbsp&nbsp
                           <input type="checkbox" name="hrd_non_degree_target_q2" value="1"> Q2 &nbsp&nbsp
                           <input type="checkbox" name="hrd_non_degree_target_q3" value="1"> Q3 &nbsp&nbsp
                           <input type="checkbox" name="hrd_non_degree_target_q4" value="1"> Q4 &nbsp&nbsp
                        </div>
                      </div>

                      <div class="form-group-degree">
                        <strong>Remarks</strong>
                        <div class="form-group">
                           <textarea class="form-control" name="hrd_degree_remarks" id="hrd_degree_remarks"></textarea>
                        </div>
                      <div class="form-group-degree">

                    </div>



                </div>

              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submitFrm()">Save Changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

  function submitFrm()
  {
    $("#frm").submit();
  }

  function addRecord(type)
  {
    $("#modal-add").modal('toggle');
    switch(type)
    {
        case 'degree':
          $(".form-group-degree").show();
          $(".form-group-non-degree").hide();
          $(".modal-title").text('ADD DEGREE PROGRAM');
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/save-hrd-plan-degree') }}");
        break;

        case 'non-degree':
          $(".form-group-degree").hide();
          $(".form-group-non-degree").show();
          $(".modal-title").text('ADD NON-DEGREE PROGRAM');
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/save-hrd-plan-non-degree') }}");
        break;
    }
    
  }

  function actionHRD(act,id)
  {
    switch(act)
    {
        case "edit":
          $("#modal-add").modal('toggle');
          $(".modal-title").text('EDIT DEGREE PROGRAM');
          $("#hrd_plan_degree_id").val(id);
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/update-hrd-plan-degree') }}");
          $.getJSON( "{{ url('learning-development/json/hrd-plan-degree') }}/" + id, function( datajson ) {
              }).done(function(datajson) {

                  jQuery.each(datajson,function(i,obj){
                        $("#hrd_degree_remarks").val(obj.hrd_degree_remarks);
                        $("#hrd_degree_university").val(obj.hrd_degree_university); 
                        $("#hrd_degree_staff").val(obj.user_id); 
                        $("#hrd_degree_area").val(obj.hrd_degree_area);

                        $("input[name=hrd_degree_program][value=" + obj.hrd_degree_program + "]").attr('checked', 'checked');
                        $("input[name=hrd_degree_type][value=" + obj.hrd_degree_type + "]").attr('checked', 'checked');
                        $("input[name=hrd_degree_target][value='" + obj.hrd_degree_target + "']").attr('checked', 'checked');
                      });

              }).fail(function() {
            });
        break;

        case "delete-degree":
          $("#hrd_plan_degree_id").val(id);
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/delete-hrd-plan-degree') }}");
          $("#frm").submit();
        break;

        case "delete-non-degree":
          $("#hrd_plan_degree_id").val(id);
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/delete-hrd-plan-non-degree') }}");
          $("#frm").submit();
        break;

        case "submit-hrd-plan":
          $("#frm_url_reset").val("{{ url('learning-development/hrd-plan/'.$data['hrd_degree_id'].'/'.$data['hrd_plan_id']) }}");
          $("#frm_url_action").val("{{ url('learning-development/submit-hrd-plan') }}");
          $("#frm").submit();
        break;

        case "submit-hrd-plan-staff":
          $("#frm_url_reset").val("{{ url('/') }}");
          $("#frm_url_action").val("{{ url('learning-development/submit-hrd-plan-staff') }}");
          $("#frm").submit();
        break;
    }
  }
</script>
@endsection