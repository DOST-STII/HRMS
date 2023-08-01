@extends('template.master')
@section('CSS')
@endsection

@section('content')
<!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form">   -->

<form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('employee/update') }}">  
{{ csrf_field() }}
<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('employee/update') }}">
<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('update-employee/'.$data['empinfo']['id']) }}">
<input type="hidden" name="userid" id="userid" value="{{ $data['empinfo']['id'] }}">

<div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid" id="profile-picture"
                       src="{{ asset('../storage/app/'.$data['empinfo']['image_path']) }}"
                       alt="User profile picture" style="width: 400px !important">
                       <input type="file" id="my_file" name="photo" style="display: none;" accept="image/*"/>
                       <button type="button" class="btn btn-default float-center mt-2" id="update-photo-btn">Update Photo</button>
                       <input type="hidden" name="origphoto" id="origphoto" value="{{ $data['empinfo']['image_path'] }}" >
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
                            <input type="text" class="form-control" name="empcode" placeholder="Employee Code" value="{{ $data['empinfo']['username'] }}" required>
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
                      <div class="col-md-4">
                        
                        <strong><i class="fas fa-id-card mr-1"></i>RFID</strong>
                        <br>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="rfid" id="rfid" placeholder="RFID" value="{{ $data['rfid'] }}" required>
                        </p>
                      </div>

                    </div>

                    <hr>

                    <strong><i class="fas fa-user mr-1"></i> Fullname</strong>

                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-3"><input type="text" class="form-control" name="lname" placeholder="Last Name" value="{{ $data['empinfo']['lname'] }}" required></div>
                        <div class="col-md-4"><input type="text" class="form-control" name="fname" placeholder="First Name" value="{{ $data['empinfo']['fname'] }}" required></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="mname" placeholder="Middle Name" value="{{ $data['empinfo']['mname'] }}" required></div>
                        <div class="col-md-2"><input type="text" class="form-control" name="exname" placeholder="Name Extension" value="{{ $data['empinfo']['exname'] }}"></div>
                      </div>
                    </p>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-sitemap mr-1"></i> Division</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control select2" id="division" name="division" required>
                              <option value=""></option>
                              @foreach($data['division'] as $divisions)
                                  <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                              @endforeach
                            </select>
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
                    </div>

                    <div class="row" id="salary_cos" style="display:none">
                    <div class="col-md-3">
                        <strong>SALARY</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="text" class="form-control" name="salary" id="salary">
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong>ATM</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="text" class="form-control" name="atm" id="atm">
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong>ORS</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="text" class="form-control" name="ors" id="ors">
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong>Charging</strong>
                        <br>
                        <br>
                        <p class="text-muted">
                             <select class="form-control" name="charging" id="charging">
                                <option value="101">101</option>
                                <option value="184">184PS</option>
                                <option value="184MOOE">184MOOE</option>
                                <option value="184C">184 CocoLevy</option>
                             </select>
                        </p>
                    </div>
                      <div class="col-md-3">
                        <strong>Tax Rate (in decimal)</strong>
                        <br>
                        <br>
                        <p class="text-muted">
                             <input type="text" class="form-control" name="taxrate" id="taxrate">
                        </p>
                    </div>

                    <div class="col-md-3">
                        <strong>Position</strong>
                        <br>
                        <br>
                        <p class="text-muted">
                          <select class="form-control select2" id="position" name="position">
                              <option value=""></option>
                              </select>
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
                <!-- </form> -->
                <!-- <button type='submit' class="btn btn-success btn-lg float-right">Save</button> -->
               <button type='submit' class="btn btn-success btn-lg float-right"><i class="fas fa-save"></i> SAVE </button>
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
  $(document).ready(function(){
    $("#division").val("{{ $data['empinfo']['division'] }}").trigger('change');
    $("#usertype").val("{{ $data['empinfo']['usertype'] }}");
    $("#employmentstatus").val("{{ $data['empinfo']['employment_id'] }}");
    $("#position").val("{{ $data['empinfo']['position_id'] }}").trigger('change');
    $("#designation").val("{{ $data['empinfo']['designation_id'] }}").trigger('change');
    $("#special").val("{{ $data['empinfo']['plantilla_special'] }}");
    $("#steps").val("{{ $data['empinfo']['plantilla_step'] }}");
    $("#rfid").val("{{ $data['rfid'] }}");
    
    <?php
      switch($data['empinfo']['employment_id'])
      {
        case 2:
        case 3:
        case 5:
        case 6:
        case 7:
        case 8:
          echo "$('#item_number,#special,#steps,#remarks').attr('disabled',true); $('#salary_cos').show();$('#salary').val('".$data['salary']['salary']."');$('#atm').val('".$data['salary']['atm']."');$('#ors').val('".$data['salary']['ors']."');$('#charging').val('".$data['salary']['charging']."');$('#taxrate').val('".$data['salary']['tax_rate']."');$('#position').val('".$data['salary']['position_id']."');";
        break;
      }
    ?>

  });

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
    $("#salary_cos").hide();
    switch(this.value)
    {
      case "2":
      case "3":
      case "5":
      case "6":
      case "7":
      case "8":
        $("#item_number,#special,#steps,#remarks").attr('disabled',true);
        $("#salary_cos").show();
      break;
    }
  })


function submitFrm()
{
  $("#frm").submit();
}


</script>

@endsection