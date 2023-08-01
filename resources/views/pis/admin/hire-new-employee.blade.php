@extends('template.master')
@section('CSS')
@endsection

@section('content')
<form method="POST" id="frm" enctype="multipart/form-data" role="form">  
<!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('add-new-hire-employee') }}">   -->
{{ csrf_field() }}
<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('add-new-hire-employee') }}">
<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('/') }}">
<input type="hidden" name="job_application_id" id="job_application_id" value="{{ $data['applicant']['id'] }}">
<div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid" id="profile-picture"
                       src="{{ asset('../storage/app/'.$data['applicant']['file_photo'] ) }}"
                       alt="User profile picture" style="width: 400px !important">
                       <!-- <input type="file" id="my_file" name="photo" style="display: none;" accept="image/*"/> -->
                       <!-- <button type="button" class="btn btn-default float-center mt-2" id="update-photo-btn">Update Photo</button> -->
                </div>

              </div>
              <!-- /.card-body -->
            </div>

          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#basicinfo" data-toggle="tab" style="background-color: #FFF;color: #555;font-weight: bold">BASIC INFORMATION</a></li><!-- 
                  <li class="nav-item"><a class="nav-link" href="#addinfo" data-toggle="tab">Additional Info</a></li>
                  <li class="nav-item"><a class="nav-link" href="#workexp" data-toggle="tab">Work Exp</a></li>
                  <li class="nav-item"><a class="nav-link" href="#trainings" data-toggle="tab">Trainings</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="basicinfo">
                    <div class="row">
                      <div class="col-md-4">
                        
                        <strong><i class="fas fa-qrcode mr-1"></i> Employee Code</strong>
                        <br>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="empcode" placeholder="Employee Code" required>
                        </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-address-card mr-1"></i> Status</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="employmentstatus" name="employmentstatus" required>
                              <option value=""></option>
                              @foreach($data['employment'] as $employments)
                                  <option value="{{ $employments->employment_id }}">{{ $employments->employment_desc }}</option>
                              @endforeach
                            </select>
                          </p>
                      </div>
                    </div>

                    <hr>
                    <center><h4><b>PROFILE</b></h4></center>
                    <strong><i class="fas fa-user mr-1"></i> Fullname</strong>

                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-3"><input type="text" class="form-control" name="lname" placeholder="Last Name" value="{{ $data['applicant']['lname'] }}" readonly></div>
                        <div class="col-md-4"><input type="text" class="form-control" name="fname" placeholder="First Name" value="{{ $data['applicant']['fname'] }}" readonly></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="mname" placeholder="Middle Name" value="{{ $data['applicant']['mname'] }}" readonly></div>
                        <div class="col-md-2"><input type="text" class="form-control" name="exname" placeholder="Name Extension"></div>
                      </div>
                    </p>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-hashtag mr-1"></i> Item Number</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="text" class="form-control" name="item_number" id="item_number" value="{{ $data['applicant']['plantilla_item_number'] }}" readonly>

                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-user-circle mr-1"></i> Position</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="position_desc" id="position_desc" value="{{ $data['applicant']['position_desc'] }}" readonly>
                            <input type="hidden" class="form-control" name="position" id="position" value="{{ $data['applicant']['position_id'] }}">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-user-circle mr-1"></i> Designation</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control select2" id="designation" name="designation" required>
                              <option value=""></option>
                              @foreach($data['designation'] as $designations)
                                  <option value="{{ $designations->designation_id }}">{{ $designations->designation_desc }}</option>
                              @endforeach
                            </select>
                          </p>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-id-card mr-1"></i> Special?</strong>
                        <br>
                        <br>
                          <p class="text-muted">

                            <?php
                              $special = null;
                              if($data['applicant']['plantilla_special'] == 1)
                              {
                                $special = "YES";
                              }
                              elseif ($data['applicant']['plantilla_special'] == 0) {
                                $special = "NO";
                              }
                            ?>

                            <input type="text" class="form-control" name="special_desc" id="special_desc" value="{{ $special }}" readonly>
                            <input type="hidden" class="form-control" name="special" id="special" value="{{ $data['applicant']['plantilla_special'] }}">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-sort-numeric-up-alt mr-1"></i> Step</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="steps" id="steps" value="{{ $data['applicant']['plantilla_steps'] }}" readonly>
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-calendar mr-1"></i> First Day of Service</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="date" class="form-control" name="fldservice">
                          </p>
                      </div>
                    </div>


                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-sitemap mr-1"></i> Division</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="division_desc" id="division_desc" value="{{ getDivision($data['applicant']['division_id']) }}" readonly>
                            <input type="hidden" class="form-control" name="division" id="division" value="{{ $data['applicant']['division_id'] }}">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-address-card mr-1"></i> Usertype</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="usertype" name="usertype" required>
                              <option value=""></option>
                              <option value="Administrator">Administrator</option>
                              <option value="Director">Director</option>
                              <option value="Marshal">Marshal</option>
                              <option value="Staff">Staff</option>
                            </select>
                          </p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-money-bill-alt mr-1"></i> Salary</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                              <input type="text" class="form-control" name="salary" id="salary" value="P{{ formatNumber('currency',$data['applicant']['plantilla_salary']) }}" readonly>
                          </p>
                      </div>
                    </div>

                  </div>

                  <div class="tab-pane" id="addinfo">
                  </div>

                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="workexp">
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="trainings">
                  </div>
                  <!-- /.tab-pane -->
                </div>

                <hr>

                <center><h4><b>ATTACHMENT</b></h4></center>
                <div class="row">
                  <div class="col-lg-4">

                    <strong>Appointment</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_appointment" id="file_appointment" required>
                            <label class="custom-file-label" for="file_appointment">Choose file</label>
                    </div>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <strong>Oath of Office</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_oath" id="file_oath" required>
                            <label class="custom-file-label" for="file_oath">Choose file</label>
                    </div>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <strong>Report for Duty</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_duty" id="file_duty" required>
                            <label class="custom-file-label" for="file_duty">Choose file</label>
                    </div>
                    </div>
                  </div>


                </div>
                <hr>
                <button type='submit' class="btn btn-success btn-lg float-right"><i class="fas fa-save"></i> SAVE </button>
                <!-- <button type='submit' class="btn btn-success btn-lg float-right">Save</button> -->
                </form>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>

@endsection
@section('JS')
<script type="text/javascript">

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#profile-picture').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("input[id='my_file']").change(function(){
        readURL(this);
    });


  $(".profile-user-img,#update-photo-btn").click(function() {
      $("input[id='my_file']").click();
  });

  $("#employmentstatus").change(function(){
    $("#item_number,#special,#steps,#remarks").attr('disabled',false);
    switch(this.value)
    {
      case "2":
      case "3":
      case "5":
      case "6":
      case "7":
      case "8":
        $("#item_number,#special,#steps,#remarks").attr('disabled',true);
      break;
    }
  })

</script>

@endsection