@extends('template.master')
@section('CSS')
  <link rel="stylesheet" href="{{ asset('multidate/bootstrap-datepicker.css') }}">
@endsection

@section('content')
<?php
//TO SET AN ACTIVE TAB
$tab_info = "";
$tab_info_content = "";
$tab_addinfo = "";
$tab_addinfo_content = "";
$tab_address = "";
$tab_address_content = "";
$tab_family = "";
$tab_family_content = "";
$tab_org = "";
$tab_org_content = "";
$tab_eligibility = "";
$tab_eligibility_content = "";
$tab_work = "";
$tab_work_content = "";
$tab_education = "";
$tab_education_content = "";
$tab_training = "";
$tab_training_content = "";
$tab_competency = "";
$tab_competency_content = "";
$tab_other = "";
$tab_other_content = "";
$tab_files = "";
$tab_files_content = "";

$tab_skills = "";
$tab_skills_content = "";
$tab_nonacademic = "";
$tab_nonacademic_content = "";
$tab_memberorg = "";
$tab_memberorg_content = "";
$tab_reference = "";
$tab_reference_content = "";
$tab_cases= "";
$tab_cases_content = "";



switch ($data['active_tab']) {
  case 'addinfo':
    # code...
      $tab_addinfo = "active";
      $tab_addinfo_content = "show active";
    break;
  case 'address':
    # code...
      $tab_address = "active";
      $tab_address_content = "show active";
    break;
  case 'family':
    # code...
      $tab_family = "active";
      $tab_family_content = "show active";
    break;
  case 'organization':
    # code...
      $tab_org = "active";
      $tab_org_content = "show active";
    break;
  case 'eligibility':
    # code...
      $tab_eligibility = "active";
      $tab_eligibility_content = "show active";
    break;
  case 'training':
    # code...
      $tab_training = "active";
      $tab_training_content = "show active";
    break;
  case 'competency':
    # code...
      $tab_competency = "active";
      $tab_competency_content = "show active";
    break;
  case 'work':
    # code...
      $tab_work = "active";
      $tab_work_content = "show active";
    break;
    case 'education':
    # code...
      $tab_education = "active";
      $tab_education_content = "show active";
    break;
  case 'other':
    # code...
      $tab_other = "active";
      $tab_other_content = "show active";
    break;
  case 'files':
    # code...
      $tab_files = "active";
      $tab_files_content = "show active";
    break;
  default:
      $tab_info = "active";
      $tab_info_content = "show active";
  break;
}

switch ($data['active_subtab']) {
  case 'skills':
  case 'na':  
      $tab_skills = "active";
      $tab_skills_content = "show active";
    break;
  case 'nonacademic':
      $tab_nonacademic = "active";
      $tab_nonacademic_content = "show active";
    break;
  case 'memberorg':
      $tab_memberorg= "active";
      $tab_memberorg_content = "show active";
    break;
  case 'reference':
      $tab_reference = "active";
      $tab_reference_content = "show active";
    break;
  case 'cases':
    $tab_cases = "active";
      $tab_cases_content = "show active";
}

?>

      <div class="modal fade" id="modal-photo">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title">UPLOAD PHOTO</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm-photo" enctype="multipart/form-data" role="form" action="{{ url('basicinfo/update-photo') }}">  
            {{ csrf_field() }}
            <!-- <input type="hidden" name="tblid" id="tblid" value=""> -->
            <div class="modal-body">
              <input type="file" class="form-control" name="uploadphoto" id="uploadphoto">
            </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Photo</button>
            </form>
            </div>
        </div>
      </div>
    </div>
<div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">

              <a href="{{ url('pdf/pds') }}" class="float-right btn btn-info btn-sm" target="_blank"><i class="fas fa-print"></i> Print PDS</a>
              <br>
              <br>
                <div class="text-center">
                  <img class="profile-user-img img-fluid"
                       src="{{ asset('storage/photos/'.$data['empinfo']['image_path']) }}"
                       alt="User profile picture" style="width: 400px !important">
                </div>

                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                <p class="text-muted text-center"></p>

                <!-- <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul> -->

                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-photo"><b>Upload Photo</b></button>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <!-- <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div> 
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
                  B.S. in Computer Science from the University of Tennessee at Knoxville
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">Malibu, California</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                <p class="text-muted">
                  <span class="tag tag-danger">UI Design</span>
                  <span class="tag tag-success">Coding</span>
                  <span class="tag tag-info">Javascript</span>
                  <span class="tag tag-warning">PHP</span>
                  <span class="tag tag-primary">Node.js</span>
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
              </div>
            </div> -->


            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills" style="font-size: 14px">
                  <li class="nav-item"><a class="nav-link {{ $tab_info }}" href="#basicinfo" data-toggle="tab">Employee Information</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_addinfo }}" href="#addinfo" data-toggle="tab">Additional Info</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_address }}" href="#address" data-toggle="tab">Address</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_family }}" href="#family" data-toggle="tab">Family</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_education }}" href="#education" data-toggle="tab">Educational Background</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_org }}" href="#organization" data-toggle="tab">Organization</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_eligibility }}" href="#eligibility" data-toggle="tab">Civil Service Eligibility</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_work }}" href="#workexp" data-toggle="tab">Work Experience</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_training }}" href="#trainings" data-toggle="tab">Learning and Development</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_competency }}" href="#competency" data-toggle="tab">Competency</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_other }}" href="#otherinfo" data-toggle="tab">Other Info</a></li>
                  <li class="nav-item"><a class="nav-link {{ $tab_files }}" href="#files" data-toggle="tab">Files</a></li>
                </ul>
              </div><!-- /.card-header -->
           
            </div>
  </div>
</div>
                  </div>

                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
</div>


      <div class="modal fade" id="modal-option">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('performance/ipcr-staff/create') }}">   -->
            {{ csrf_field() }}
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">
            <!-- <input type="hidden" name="trainingid" id="trainingid" value="">
            <input type="hidden" name="organizationid" id="organizationid" value="">
            <input type="hidden" name="eligibilityid" id="eligibilityid" value=""> -->
            <input type="hidden" name="tblid" id="tblid" value="">
            <div class="modal-body">

              <!-- ADD NEW TRANING -->
              <div class="div-content" id="div-add-new-training">
                <div class="row">
                  <div class="col-md-6">
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
                    <strong>Type</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control training" name="training_type" id="training_type">
                            <option value="Free">Free</option>
                            <option value="Funded">PCAARRD Funded</option>
                            <option value="Personal">Personal</option>
                          </select>
                        </p>

                    <div id="div-amount" style="display: none">
                      <strong>Amount</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" name="training_amount" id="training_amount" class="form-control training">
                        </p>
                  </div>
                </div>
                  <div class="col-md-6">
                    <strong>Training Hours</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" class="form-control training" name="training_hours" id="training_hours" autocomplete="off"/>
                        </p>
                    <strong>LD</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control select2 training" name="training_ld[]" id="training_ld" multiple>
                            <option value="Managerial">Managerial</option>
                            <option value="Supervisory">Supervisory</option>
                            <option value="Technical">Technical</option>
                          </select>
                        </p>

                    <!-- <div id="div-others" style="display: none">
                      <strong>Others</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" name="training_ld_others" id="training_ld_others" class="form-control">
                        </p>
                    </div> -->

                   
                    <strong>Areas of Discipline</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control training" name="areasdiscip" id="areasdiscip">                            
                            <option value=""></option>
                            <option value="Management/ Supervisory/ Leadership">Management/ Supervisory/ Leadership</option>
                            <option value="R&d Related Trainings">R&d Related Trainings</option>
                            <option value="Skills Enhancement">Skills Enhancement</option>
                            <option value="Information & Communications Technology (ICT)">Information & Communications Technology (ICT)</option>
                            <option value="Information, Education & Communication (ICT)">Information, Education & Communication (IEC)</option>
                            <option value="Value Enhancement">Value Enhancement</option>
                            <option value="General Administration/ Governance">General Administration/ Governance</option>
                          </select>
                        </p>

                       <strong>Certificate (10mb max)</strong>
                        <br>
                        <p>
                          <input type="file" name="training_certificate" class="form-control training" accept="application/pdf">
                        </p>
                       <strong>Training Report (10mb max)</strong>
                        <br>
                        <p>
                          <input type="file" name="training_report" class="form-control training" accept="application/pdf">
                        </p>
                  </div>
                </div>
              </div>
              <!-- END ADD NEW TRANING -->

              <!-- BASIC INFO -->
              <div class="div-content" id="div-basic-info">
                <div class="row">
                  <div class="col-md-3">
                      <strong>Fullname</strong>
                      <input type="text" class="form-control basicinfo" name="lname" id="lname" placeholder="Last name">
                  </div>
                  <div class="col-md-4">
                      <strong>&nbsp</strong>
                      <input type="text" class="form-control basicinfo" name="fname" id="fname" placeholder="First name">
                  </div>
                  <div class="col-md-3">
                      <strong>&nbsp</strong>
                      <input type="text" class="form-control basicinfo" name="mname" id="mname" placeholder="Middle name">
                  </div>
                  <div class="col-md-2">
                      <strong>&nbsp</strong>
                      <input type="text" class="form-control basicinfo" name="exname" id="exname" placeholder="Extension">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>Birthdate</strong>
                      <input type="date" class="form-control basicinfo" name="birthdate" id="birthdate">
                  </div>
                  <div class="col-md-4">
                      <strong>Place of Birth</strong>
                      <input type="text" class="form-control basicinfo" name="placeofbirth" id="placeofbirth" placeholder="Place of birth">
                  </div>
                  <div class="col-md-2">
                      <strong>Sex</strong>
                      <select class="form-control basicinfo" name="sex" id="sex">
                        <option value=""></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <strong>Civil Status</strong>
                      <select class="form-control select2 basicinfo" name="civilstatus" id="civilstatus">
                        <option value=""></option>
                        <option value="Annuled">Annuled</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Married">Married</option>
                        <option value="Single">Single</option>
                        <option value="Seperated">Separated</option>
                        <option value="Widowed">Widowed</option>
                      </select>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>Citizenship</strong>
                      <select class="form-control select2 basicinfo" name="citizenship[]" id="citizenship" multiple="multiple">
                        <option value=""></option>
                        <option value="Filipino">Filipino</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <strong>Citizen Type</strong>
                      <select class="form-control basicinfo" name="citizentype" id="citizentype">
                        <option value=""></option>
                        <option value="By birth">By birth</option>
                        <option value="By naturalization">By naturalization</option>
                        <option value="Dual Citinzenship">Dual Citizenship</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <strong>Height(cm)</strong>
                      <input type="number" class="form-control basicinfo" name="info_height" id="info_height">
                  </div>
                  <div class="col-md-3">
                      <strong>Weight(kg)</strong>
                      <input type="number" class="form-control basicinfo" name="info_weight" id="info_weight">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>Blood Type</strong>
                      <select class="form-control basicinfo" name="bloodtype" id="bloodtype">
                        <option value="A">A</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="AB">AB</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="B">B</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O">O</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                      </select>
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-md-4">
                      <strong>Email</strong>
                      <input type="email" class="form-control address" name="email" id="email" value="{{ Auth::user()->email }}">
                  </div>
                  <div class="col-md-4">
                      <strong>Cell number</strong>
                      <input type="text" class="form-control address" name="contact_cellnum" id="contact_cellnum">
                  </div>
                </div>
              </div>
              <!-- END BASIC INFO -->

              <!-- ADD INFO -->
              <div class="div-content" id="div-add-info">
                <div class="row">
                  <div class="col-md-3">
                      <strong>Pagibig No.</strong>
                      <input type="text" class="form-control basicinfo" name="pagibig" id="pagibig" value="">
                  </div>
                  <div class="col-md-3">
                      <strong>PhilHealth No.</strong>
                      <input type="text" class="form-control basicinfo" name="philhealth" id="philhealth">
                  </div>
                  <div class="col-md-3">
                      <strong>SSS No.</strong>
                      <input type="text" class="form-control basicinfo" name="sss" id="sss">
                  </div>
                  <div class="col-md-3">
                      <strong>TIN</strong>
                      <input type="text" class="form-control basicinfo" name="tin" id="tin">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>GSIS ID No.</strong>
                      <input type="text" class="form-control basicinfo" name="gsis_id" id="gsis_id">
                  </div>
                  <div class="col-md-3">
                      <strong>GSIS Policy No.</strong>
                      <input type="text" class="form-control basicinfo" name="gsis_policy" id="gsis_policy">
                  </div>
                  <div class="col-md-3">
                      <strong style="font-size: 13px">GSIS Business Partner No.</strong>
                      <input type="text" class="form-control basicinfo" name="gsis_bp" id="gsis_bp">
                  </div>
                  <div class="col-md-3">
                      <strong style="font-size: 13px">Landbank ATM</strong>
                      <input type="text" class="form-control basicinfo" name="landbank_atm" id="landbank_atm">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>Govt. Issued ID</strong>
                      <!-- <input type="text" class="form-control basicinfo" name="gov" id="gov"> -->
                      <select class="form-control" name="gov" id="gov">
                        <option value="Passport">Passport</option>
                        <option value="Driver’s License">Driver’s License</option>
                        <option value="Voter’s ID">Voter’s ID</option>
                        <option value="Postal ID">Postal ID</option>
                        <option value="PRC ID">PRC ID</option>
                        <option value="Senior Citizen ID">Senior Citizen ID</option>
                        <option value="OFW ID">OFW ID</option>
                        <option value="UMID">UMID</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <strong>ID No.</strong>
                      <input type="text" class="form-control basicinfo" name="gov_id" id="gov_id">
                  </div>
                  <div class="col-md-3">
                      <strong>Date/Place Issued</strong>
                      <input type="text" class="form-control basicinfo" name="gov_place_date" id="gov_place_date">
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3">
                      <strong>CTC No.</strong>
                      <input type="text" class="form-control basicinfo" name="ctc" id="ctc">
                  </div>
                  <div class="col-md-3">
                      <strong>Date Issued</strong>
                      <input type="text" class="form-control basicinfo" name="ctc_date" id="ctc_date">
                  </div>
                  <div class="col-md-3">
                      <strong>Place Issued</strong>
                      <input type="text" class="form-control basicinfo" name="ctc_place" id="ctc_place">
                  </div>
                </div>
              </div>
              <!-- END ADD INFO -->

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
<script src="{{ asset('multidate/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">

@foreach($data['cases'] as $cs)
      $("input[name={{ $cs->case_admin }}][value={{ $cs->case_ans }}]").attr('checked', 'checked');
      $("input[name={{ $cs->case_admin }}_remarks]").val("{{ $cs->case_remarks }}");
@endforeach

  $(document).ready(function() {
    var t = $('.tbl').DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();


      $('.date').datepicker({
        multidate: true,
        format: "yyyy-mm-dd",
        });


    


    } );


  $("#training_type").change(function(){
      $("#div-amount").hide();
      if(this.value == "Funded") {
          $("#div-amount").show();
      }
  });

  // $("#training_ld").change(function(){
  //     $("#div-others").hide();
  //     alert(alert($(this).find(':selected:last').val()));
  //     if(this.value == "Others") {
  //         $("#div-others").show();
  //     }
  // });

//   $('#training_ld').on('select2:select', function (e) {
//       $("#div-others").hide();
//       var data = e.params.data;
//       if(data.text == "Others") {
//           $("#div-others").show();
//       }
// });


  function resetForm()
  {

  }


  </script>
@endsection