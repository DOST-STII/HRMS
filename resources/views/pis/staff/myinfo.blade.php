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
                <div class="card-body">
                    <div class="tab-content">
                        <div class="{{ $tab_info_content }} tab-pane" id="basicinfo">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('basicinfo',{{ $data['empinfo']['id'] }})"><i class="fas fa-edit"></i> <b>EDIT</b></p>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Fullname</strong>
                                <p class="text-muted">
                                    {{ $data['empinfo']['lname'].', '.$data['empinfo']['fname'].' '.$data['empinfo']['mname'].' '.$data['empinfo']['exname'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Birthdate</strong>
                                <p class="text-muted">
                                    {{ date('M d, Y',strtotime($data['empinfo']['birthdate'])) }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Place of Birth</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_placeofbirth'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Sex</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_sex'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Civil Status</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_civilstatus'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Citizenship</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_citizenship'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Citizen Type</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_citizentype'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Height</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_height'] }} cm
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Weight</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_weight'] }} kg
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Blood Type</strong>
                                <p class="text-muted">
                                    {{ $data['basicinfo']['basicinfo_bloodtype'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                        
                        </div>

                        <div class="{{ $tab_addinfo_content }} tab-pane" id="addinfo">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('addinfo',{{ $data['empinfo']['id'] }})"><i class="fas fa-edit"></i> <b>EDIT</b></p>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Pag-ibig No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_pagibig'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>PhilHealth No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_philhealth'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>SSS No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_sss'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>TIN</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_tin'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>GSIS ID No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gsis_id'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>GSIS Policy No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gsis_policy'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>GSIS Business Partner No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gsis_bp'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Landbank ATM</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_atm'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Govt. Issued ID</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gov'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>ID No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gov_id'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Date/Place Issued</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_gov_place_date'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>CTC No.</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_ctc'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Date Issued</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_ctc_date'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Place Issued</strong>
                                <p class="text-muted">
                                    {{ $data['addinfo']['addinfo_ctc_place'] }}
                                </p>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_address_content }} tab-pane" id="address">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('address',{{ $data['empinfo']['id'] }})"><i class="fas fa-edit"></i> <b>EDIT</b></p>
                            <div class="row">
                            <div class="col-md-12">
                                <strong>Residential Address:</strong>
                                <p class="text-muted">
                                    {{ $data['add_residential']['residential_add_street']." ".$data['add_residential']['residential_add_no']." ".$data['add_residential']['residential_add_subd']." ".$data['add_residential']['brgy_desc'].", ".$data['add_residential']['mun_desc'].", ".$data['add_residential']['prov_desc'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Zipcode</strong>
                                <p class="text-muted">
                                    {{ $data['add_residential']['residential_add_zipcode'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Telephone No.</strong>
                                <p class="text-muted">
                                    {{ $data['add_residential']['residential_add_phone'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-12">
                                <strong>Permanent Address:</strong>
                                <p class="text-muted">
                                    {{ $data['add_permanent']['permanent_add_street']." ".$data['add_permanent']['permanent_add_no']." ".$data['add_permanent']['permanent_add_subd']." ".$data['add_permanent']['brgy_desc'].", ".$data['add_permanent']['mun_desc'].", ".$data['add_permanent']['prov_desc'] }}
                                </p>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                            <div class="col-md-3">
                                <strong>Zipcode</strong>
                                <p class="text-muted">
                                    {{ $data['add_permanent']['permanent_add_zipcode'] }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <strong>Telephone No.</strong>
                                <p class="text-muted">
                                    {{ $data['add_permanent']['permanent_add_phone'] }}
                                </p>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_family_content }} tab-pane" id="family">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('family',{{ $data['empinfo']['id'] }})"><i class="fas fa-edit"></i> <b>EDIT</b></p>
                            <div class="row">
                            <div class="col-md-4">
                                <strong>Spouse's Information</strong>
                                <p class="text-muted">
                                    <b>Name : </b> {{ $data['family']['fam_spouse_lname'].', '.$data['family']['fam_spouse_fname'].' '.$data['family']['fam_spouse_mname'].' '.$data['family']['fam_spouse_exname'] }}<br/>
                                    <b>Occupation : </b>{{ $data['family']['fam_spouse_occ'] }}<br/>
                                    <b>Employer's Name : </b>{{ $data['family']['fam_spouse_emp'] }}<br/>
                                    <b>Business Address : </b>{{ $data['family']['fam_spouse_emp_add'] }}<br/>
                                    <b>Telephone No. : </b>{{ $data['family']['fam_spouse_tel'] }}<br/>
                                </p>
                            </div>

                            <div class="col-md-4">
                                <strong>Father's Name</strong>
                                <p class="text-muted">
                                    {{ $data['family']['fam_father_lname'].', '.$data['family']['fam_father_fname'].' '.$data['family']['fam_father_mname'].' '.$data['family']['fam_father_exname'] }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <strong>Mother's Maiden Name</strong>
                                <p class="text-muted">
                                    {{ $data['family']['fam_mother_lname'].', '.$data['family']['fam_mother_fname'].' '.$data['family']['fam_mother_mname'] }}
                                </p>
                            </div>
            
                            </div>
                            <div class="row">
                        
                                <div class="col-md-12">
                                <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-children',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD</b></p>
                                <strong>Children</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Date of Birth</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['child'] as $childs)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $childs->children_name }}</td>
                                            <td>{{ $childs->children_birthdate }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-children',{{ $childs->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-children',{{ $childs->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_education_content }} tab-pane" id="education">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-education',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD</b></p>
                            <div class="row">
                                <div class="col-md-12">
                                <strong>Educational Background</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Level</th>
                                        <th>Name of School</th>
                                        <th>Degree Course</th>
                                        <th>Dates</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['education'] as $educations)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $educations->educ_level_desc }}</td>
                                            <td>{{ $educations->educ_school }}</td>
                                            <td>{{ $educations->educ_course }}</td>
                                            <td>{{ $educations->educ_date_from.'-'.$educations->educ_date_to }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-education',{{ $educations->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-education',{{ $educations->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_org_content }} tab-pane" id="organization">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-organization',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD</b></p>
                            <div class="row">
                                <div class="col-md-12">
                                <strong>Civic/Non-Government/People/Voluntary Organizations</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Inclusive Dates</th>
                                        <th>Number of Hours</th>
                                        <th>Position</th>
                                        <th>Nature of Work</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['organization'] as $organizations)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $organizations->org_name }}</td>
                                            <td>{{ $organizations->org_add }}</td>
                                            <td>{{ formatDate($organizations->org_date_from)." to ".formatDate($organizations->org_date_to) }}</td>
                                            <td>{{ $organizations->org_hours }}</td>
                                            <td>{{ $organizations->org_position }}</td>
                                            <td>{{ $organizations->org_nature }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-organization',{{ $organizations->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-organization',{{ $organizations->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_eligibility_content }} tab-pane" id="eligibility">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-eligibility',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD</b></p>
                            <div class="row">
                            <div class="col-md-12">
                                <strong>Career Service Eligibility</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Career Service/RA 1080</th>
                                        <th>Rating</th>
                                        <th>Date of Exam</th>
                                        <th>Place of Examination</th>
                                        <th>License Number</th>
                                        <th>Date of Validity</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['eligibility'] as $eligibilities)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $eligibilities->cse_title }}</td>
                                            <td>{{ $eligibilities->cse_rating }}</td>
                                            <td>{{ $eligibilities->cse_date}}</td>
                                            <td>{{ $eligibilities->cse_place }}</td>
                                            <td>{{ $eligibilities->cse_license_num }}</td>
                                            <td>{{ $eligibilities->cse_license_date }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-eligibility',{{ $eligibilities->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-eligibility',{{ $eligibilities->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div> 
                        </div>

                        <div class="{{ $tab_work_content }} tab-pane" id="workexp">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-work',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD</b></p>

                            <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="font-size: 12px">
                                    <thead>
                                        <th>#</th>
                                        <th>Inclusive Dates</th>
                                        <th>Company</th>
                                        <th>Position Title</th>
                                        <th>Monthly Salary</th>
                                        <th>Salary Grade & Step Increment</th>
                                        <th>Appointment Status</th>
                                        <th>Gov't. Service</th>
                                        <th style="width: 7% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>

                                    @foreach($data['work_agency'] as $works2)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>
                                            <?php
                                                if($works2->plantilla_date_to == NULL)
                                                {
                                                echo date('M d, Y',strtotime($works2->plantilla_date_from));
                                                }
                                                else
                                                {
                                                echo date('M d, Y',strtotime($works2->plantilla_date_from))." - ".date('M d, Y',strtotime($works2->plantilla_date_to));
                                                }
                                            ?>
                                            
                                            </td>
                                            <td>PCAARRD</td>
                                            <td>{{ $works2->position_desc }}</td>
                                            <td>P {{ formatNumber('currency',$works2->plantilla_salary) }}</td>
                                            <td>{{ $works2->salary_grade }}</td>
                                            <td>Permanent</td>
                                            <td>Yes</td>
                                            <td></td>
                                            
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach

                                    @foreach($data['work'] as $works)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>
                                            <?php
                                                if($works->workexp_date_to == NULL)
                                                {
                                                $date_to = 'Present';
                                                }
                                                else
                                                {
                                                $date_to = date('M d, Y',strtotime($works->workexp_date_to));
                                                }
                                            ?>
                                            {{ date('M d, Y',strtotime($works->workexp_date_from)).' - '. $date_to }}
                                            </td>
                                            <td>{{ $works->workexp_company }}</td>
                                            <td>{{ $works->workexp_title }}</td>
                                            <td>P {{ formatNumber('currency',$works->workexp_salary) }}</td>
                                            <td>{{ $works->workexp_salary_grade }}</td>
                                            <td>{{ $works->workexp_empstatus }}</td>
                                            <td>{{ $works->workexp_gov_desc }}</td>
                                            <td>
                                            
                                            <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-work',{{ $works->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-work',{{ $works->id }})"></i></td>
                                            
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach

                                    
                                    </tbody>
                                </table>
                            </div>
                            </div> 
                        </div>

                        <div class="{{ $tab_training_content }} tab-pane" id="trainings">
                            <p class="float-left"><h4>Total Hours : <b><u>{{ formatNumber('number',$data['total_training_hours'][0]) }}</u></b> &nbsp&nbsp&nbsp&nbsp Total Investment : <b><u>{{ formatNumber('currency',$data['total_training_amount'][0]) }}</u></b></h4></p>
                            <p align="right" onclick="modalOption('add-training')" style="cursor: pointer;color: #0074c2"><i class="fas fa-plus"></i> <b>ADD</b></p>
                            <table class="table tbl" style="font-size: 14px">
                            <thead>
                                <th>#</th>
                                <th style="width: 100px !important">Title of Activity</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>No. of Hours</th>
                                <th>Date</th>
                                <th>Certificate</th>
                                <th>Training Report</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <?php $ctr = 1 ?>
                                @foreach($data['training'] as $trainings)
                                    <?php
                                        // if($trainings->approved == 1)
                                        // {
                                        //     $tr = "";
                                        //     $panel = '';
                                        // }
                                        // else
                                        // {
                                        //     $tr = "style='color:#AAA'";
                                        //     $panel = '<span class="badge badge-secondary">Pending for Approval</span><br>';
                                        // }
                                    ?>
                                    <tr><td>{{ $ctr }}</td><td>{{ $trainings->training_title }}</td><td>{{ $trainings->training_type }}</td><td>{{ formatNumber('currency',$trainings->training_amount) }}</td><td>{{ $trainings->training_hours }}</td><td>
                                    <?php
                                        // $arr = explode(',',$trainings->training_inclusive_dates);
                                        // foreach ($arr as $value) {
                                        //   # code...
                                        //       echo date('Y-m-d',strtotime($value))."<br/>";
                                        // }
                                    ?>
                                    {{ $trainings->training_inclusive_dates }}
                                    </td>
                                    <td align="center">
                                    <?php
                                        if($trainings->training_certificate != null && $trainings->training_certificate != "")
                                        {
                                        echo "<a href='".asset('storage/training_certificates/'.$trainings->training_certificate)."' target='_blank'><i  class='fas fa-paperclip'></i></a>";
                                        }
                                    ?>    
                                    </td>

                                    <td align="center">
                                    <?php
                                    if($trainings->training_report != null && $trainings->training_report != "")
                                        {
                                        echo "<a href='".asset('storage/training_reports/'.$trainings->training_report)."' target='_blank'><i  class='fas fa-paperclip'></i></a>";
                                        }
                                    ?>    
                                    </td>

                                    <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-training',{{ $trainings->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-training',{{ $trainings->id }})"></i></td></tr>
                                    <?php $ctr++ ?>
                                @endforeach
                            </tbody>
                            </table>
                        </div>

                        <div class="{{ $tab_competency_content }} tab-pane" id="competency">
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-competency-duty',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD DUTY</b></p>
                            <div class="row">
                                <div class="col-md-12">
                                <strong>Duties and Responsibilities</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Task</th>
                                        <th>% of Work</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['competency_duty'] as $duties)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $duties->task }}</td>
                                            <td>{{ $duties->task_percent }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-competency-duty',{{ $duties->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-competency-duty',{{ $duties->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <br>
                            <br>
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-competency',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD COMPETENCY</b></p>
                            <div class="row">
                                <div class="col-md-12">
                                <strong>Competencies</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th style="width: 30% !important">Degree of Importance to Job</th>
                                        <th style="width: 30% !important">Curret Skill Level</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['competency'] as $competencies)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $competencies->competency_desc }}</td>
                                            <td>{{ $competencies->competency_job }}</td>
                                            <td>{{ $competencies->competency_skill }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-competency',{{ $competencies->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-competency',{{ $competencies->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <br>
                            <br>
                            <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-competency-training',{{ $data['empinfo']['id'] }})"><i class="fas fa-plus"></i> <b>ADD TRAININGS NEEDED</b></p>
                            <div class="row">
                                <div class="col-md-12">
                                <strong>Training Programs Needed</strong>
                                <br>
                                <br>
                                <table class="table" style="font-size: 14px">
                                    <thead>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th style="width: 10% !important"></th>
                                    </thead>
                                    <tbody>
                                    <?php $ctr = 1 ?>
                                    @foreach($data['competency_training'] as $trainings)
                                        <tr>
                                            <td>{{ $ctr }}</td>
                                            <td>{{ $trainings->training_desc }}</td>
                                            <td><i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-competency-training',{{ $trainings->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-competency-training',{{ $trainings->id }})"></i></td>
                                        </tr>
                                    <?php $ctr++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="{{ $tab_other_content }} tab-pane" id="otherinfo">
                            <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist" style="font-size: 13px">
                                    <li class="nav-item">
                                    <a class="nav-link {{$tab_skills}}" id="tab-skills-id" data-toggle="pill" href="#tab-skills" role="tab" aria-controls="tab-skills" aria-selected="true">Special Skills/Hobbies</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link {{$tab_nonacademic}}" id="tab-recognition-id" data-toggle="pill" href="#tab-recognition" role="tab" aria-controls="tab-recognition" aria-selected="false">Non-Academic Distinctions/Recognition</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link {{$tab_memberorg}}" id="tab-association-id" data-toggle="pill" href="#tab-association" role="tab" aria-controls="tab-association" aria-selected="false">Membership in Association/Organization</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                    <a class="nav-link {{$tab_reference}}" id="tab-references-id" data-toggle="pill" href="#tab-references" role="tab" aria-controls="tab-references" aria-selected="false">References</a>
                                    </li>

                                    <li class="nav-item">
                                    <a class="nav-link {{$tab_cases}}" id="tab-cases-id" data-toggle="pill" href="#tab-cases" role="tab" aria-controls="tab-cases" aria-selected="false">Administrative Cases</a>
                                    </li>
                                </ul>

                                    <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade {{$tab_skills_content}}" id="tab-skills" role="tabpanel" aria-labelledby="tab-skills">
                                        <br>
                                        <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-skill')"><i class="fas fa-plus"></i> <b>ADD</b></p>
                                        <table class="table tbl">
                                        <thead>
                                            <th style="width: 50px !important">#</th>
                                            <th>Description</th>
                                            <th style="width: 50px"></th>
                                        </thead>
                                        <tbody>
                                            <?php $ctr = 1 ?>
                                            @foreach($data['skill'] as $skills)
                                                <tr>
                                                    <td>{{ $ctr }}</td>
                                                    <td>{{ $skills->skill_desc }}</td>
                                                    <td>
                                                    <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-skill',{{ $skills->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-skill',{{ $skills->id }})"></i></td>
                                                </tr>
                                            <?php $ctr++ ?>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade {{$tab_nonacademic_content}}" id="tab-recognition" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                        <br>
                                        <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-recognition')"><i class="fas fa-plus"></i> <b>ADD</b></p>
                                        <table class="table tbl">
                                        <thead>
                                            <th style="width: 50px !important">#</th>
                                            <th>Description</th>
                                            <th style="width: 50px"></th>
                                        </thead>
                                        <tbody>
                                            <?php $ctr = 1 ?>
                                            @foreach($data['recognition'] as $recognitions)
                                                <tr>
                                                    <td>{{ $ctr }}</td>
                                                    <td>{{ $recognitions->academic_desc }}</td>
                                                    <td>
                                                    <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-recognition',{{ $recognitions->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-recognition',{{ $recognitions->id }})"></i></td>
                                                </tr>
                                            <?php $ctr++ ?>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade {{$tab_memberorg_content}}" id="tab-association" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                                        <br>
                                        <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-association')"><i class="fas fa-plus"></i> <b>ADD</b></p>
                                        <table class="table tbl">
                                        <thead>
                                            <th style="width: 50px !important">#</th>
                                            <th>Description</th>
                                            <th style="width: 50px"></th>
                                        </thead>
                                        <tbody>
                                            <?php $ctr = 1 ?>
                                            @foreach($data['association'] as $associations)
                                                <tr>
                                                    <td>{{ $ctr }}</td>
                                                    <td>{{ $associations->assoc_desc }}</td>
                                                    <td>
                                                    <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-association',{{ $associations->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-association',{{ $associations->id }})"></i></td>
                                                </tr>
                                            <?php $ctr++ ?>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div> 
                                    <div class="tab-pane fade {{$tab_reference_content}}" id="tab-references" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
                                        <br>
                                        <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-reference')"><i class="fas fa-plus"></i><b>ADD</b></p> 
                                        <table class="table tbl">
                                        <thead>
                                            <th style="width: 50px !important">#</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Tel No.</th>
                                            <th style="width: 10%"></th>
                                        </thead>
                                            <?php $ctr = 1 ?>
                                            @foreach($data['reference'] as $references)
                                                <tr>
                                                    <td>{{ $ctr }}</td>
                                                    <td>{{ $references->reference_name }}</td>
                                                    <td>{{ $references->reference_add }}</td>
                                                    <td>{{ $references->reference_telno }}</td>
                                                    <td>
                                                    <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-reference',{{ $references->id }})"></i> <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('delete-reference',{{ $references->id }})"></i></td>
                                                </tr>
                                            <?php $ctr++ ?>
                                            @endforeach
                                        
                                        </table>
                                    </div>

                                    <div class="tab-pane fade {{$tab_cases_content}}" id="tab-cases" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
                                        <br>
                                        <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalOption('add-cases')"><i class="fas fa-plus"></i> <b>UPDATE</b></p>
                                        <table class="table tbl">
                                        <tr>
                                            <td>
                                            <form method="POST" id="frm_case" enctype="multipart/form-data" role="form" action="{{ url('cases/create') }}">
                                            {{ csrf_field() }}
                                            34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed
                                            <br/>  
                                            &nbsp&nbsp&nbsp a. Within the third degree?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case34a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case34a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case34a_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp&nbsp&nbsp b. Within the fourth degree (for Local Government Unit - Career Employees)?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case34b" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case34b" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="case34b_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>35. a.Have you ever been found guilty of any administrative offense</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case35a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case35a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case35a_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp&nbsp&nbsp b. Have you ever been criminally charged before any court?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case35b" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case35b" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="case35b_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                            <td>36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case36a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case36a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case36a_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                            <td>37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case37a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case37a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case37a_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                            <td>38. a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case38a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case38a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case37a_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp&nbsp&nbsp b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case38b" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case38b" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="case37b_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                            <td>39. Have you acquired the status of an immigrant or permanent resident of another country?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case39a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case39a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case39a_remarks" placeholder="If Yes"></td>
                                        </tr>

                                        <tr>
                                        <td>
                                            40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:
                                            <br/>  
                                            &nbsp&nbsp&nbsp a. Are you a member of any indigenous group?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40a" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40a" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td style="width:20%"><input type="text" class="form-control" name="case40a_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp&nbsp&nbsp b. Are you a person with disability?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40b" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40b" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="case40b_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp&nbsp&nbsp c. Are you a solo parent?</td>
                                            <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40c" id="inlineRadio1" value="Yes">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="case40c" id="inlineRadio2" value="No" checked>
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="case40c_remarks" placeholder="If Yes"></td>
                                        </tr>
                                        </table>
                                        </form>
                                    </div>
                                    </div>
                            </div> 
                            </div>
                        </div>

                    </div>
                </div>
           
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

  function resetForm()
  {

  }

  function modalOption(type,id = null)
  {
    $("#tblid").val(id);
    $(".div-content").hide();
    switch(type)
    {
      case "basicinfo":

          $("#modal-option").modal('toggle');
          $("#modal-title").text("BASIC INFORMATION");
          $("#frm_url_action").val("{{ url('basicinfo/check') }}");
          $("#frm_url_reset").val("{{ url('personal-information/info/na') }}");
          $("#div-basic-info").show();

          //USER INFO
          $("#lname").val("{{ $data['empinfo']['lname'] }}");
          $("#fname").val("{{ $data['empinfo']['fname'] }}");
          $("#mname").val("{{ $data['empinfo']['mname'] }}");
          $("#exname").val("{{ $data['empinfo']['exname'] }}");
          $("#birthdate").val("{{ $data['empinfo']['birthdate'] }}");

          //BASIC INFO
          $("#placeofbirth").val("{{ $data['basicinfo']['basicinfo_placeofbirth'] }}");
          $("#info_height").val("{{ $data['basicinfo']['basicinfo_height'] }}");
          $("#info_weight").val("{{ $data['basicinfo']['basicinfo_weight'] }}");
          //SELECT
          $("#sex").val("{{ $data['basicinfo']['basicinfo_sex'] }}");
          $("#bloodtype").val("{{ $data['basicinfo']['basicinfo_bloodtype'] }}");
          $("#citizentype").val("{{ $data['basicinfo']['basicinfo_citizentype'] }}");
      break;

      case "addinfo":
        $("#modal-option").modal('toggle');
        $("#modal-title").text("ADDIONAL INFORMATION");
        $("#frm_url_action").val("{{ url('addinfo/check') }}");
        $("#frm_url_reset").val("{{ url('personal-information/addinfo/na') }}");
        $("#div-add-info").show();

        $("#pagibig").val("{{ $data['addinfo']['addinfo_pagibig'] }}");
        $("#philhealth").val("{{ $data['addinfo']['addinfo_philhealth'] }}");
        $("#sss").val("{{ $data['addinfo']['addinfo_sss'] }}");
        $("#tin").val("{{ $data['addinfo']['addinfo_tin'] }}");
        $("#gsis_id").val("{{ $data['addinfo']['addinfo_gsis_id'] }}");
        $("#gsis_policy").val("{{ $data['addinfo']['addinfo_gsis_policy'] }}");
        $("#gsis_bp").val("{{ $data['addinfo']['addinfo_gsis_bp'] }}");
        // $("#partner").val("{{ $data['addinfo']['addinfo_partner'] }}");
        $("#landbank_atm").val("{{ $data['addinfo']['addinfo_atm'] }}");
        $("#gov").val("{{ $data['addinfo']['addinfo_gov'] }}");
        $("#gov_id").val("{{ $data['addinfo']['addinfo_gov_id'] }}");
        $("#gov_place_date").val("{{ $data['addinfo']['addinfo_gov_place_date'] }}");
        $("#ctc").val("{{ $data['addinfo']['addinfo_ctc'] }}");
        $("#ctc_date").val("{{ $data['addinfo']['addinfo_ctc_date'] }}");
        $("#ctc_place").val("{{ $data['addinfo']['addinfo_ctc_place'] }}");
      break;

  }
}
  

  


  </script>
@endsection