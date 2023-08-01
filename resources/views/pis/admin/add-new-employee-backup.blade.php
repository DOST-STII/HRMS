@extends('template.master')
@section('CSS')
@endsection

@section('content')
<form method="POST" id="frm" enctype="multipart/form-data" role="form">  
<!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('employee/create') }}">   -->
{{ csrf_field() }}
<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('employee/create') }}">
<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('list-of-staff') }}">
<div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid"
                       src="{{ asset('AdminLTE-3.0.2/dist/img/add-photo.png') }}"
                       alt="User profile picture" style="width: 400px !important">
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
                  <li class="nav-item"><a class="nav-link active" href="#basicinfo" data-toggle="tab">Basic Info</a></li><!-- 
                  <li class="nav-item"><a class="nav-link" href="#addinfo" data-toggle="tab">Additional Info</a></li>
                  <li class="nav-item"><a class="nav-link" href="#workexp" data-toggle="tab">Work Exp</a></li>
                  <li class="nav-item"><a class="nav-link" href="#trainings" data-toggle="tab">Trainings</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="basicinfo">

                    <div class="row">
                      <div class="col-md-3">
                        <strong><i class="fas fa-barcode mr-1"></i> Employee Code</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="text" class="form-control" name="empcode" placeholder="Employee Code" required>
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-sitemap mr-1"></i> Division</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="division" name="division" required>
                              @foreach($data['division'] as $divisions)
                                  <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                              @endforeach
                            </select>
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-envelope mr-1"></i> User Type</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="usertype" name="usertype" required>
                              <option value="Staff">Staff</option>
                              <option value="Director">Director</option>
                              <option value="Marshal">Marshal</option>
                            </select>
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-address-card mr-1"></i> Status</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="statustype" name="statustype" required>
                              <option value="1">Permanent</option>
                              <option value="2">Job Contract</option>
                            </select>
                          </p>
                      </div>
                    </div>


                   <div class="row">
                      <div class="col-md-3">
                        <strong><i class="fas fa-calendar mr-1"></i> First Day of Service</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                             <input type="date" class="form-control" name="fdservice">
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-sitemap mr-1"></i> Division</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="division" name="division" required>
                              @foreach($data['division'] as $divisions)
                                  <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                              @endforeach
                            </select>
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-envelope mr-1"></i> User Type</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="usertype" name="usertype" required>
                              <option value="Staff">Staff</option>
                              <option value="Director">Director</option>
                              <option value="Marshal">Marshal</option>
                            </select>
                          </p>
                      </div>
                      <div class="col-md-3">
                        <strong><i class="fas fa-address-card mr-1"></i> Status</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <select class="form-control" id="statustype" name="statustype" required>
                              <option value="1">Permanent</option>
                              <option value="2">Job Contract</option>
                            </select>
                          </p>
                      </div>
                    </div>
                    <hr>

                    <strong><i class="fas fa-user mr-1"></i> Fullname</strong>

                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-3"><input type="text" class="form-control" name="lname" placeholder="Last Name" required></div>
                        <div class="col-md-4"><input type="text" class="form-control" name="fname" placeholder="First Name" required></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="mname" placeholder="Middle Name" required></div>
                        <div class="col-md-2"><input type="text" class="form-control" name="exname" placeholder="Name Extension"></div>
                      </div>
                    </p>

                    <hr>

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Residential Address</strong>

                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-12"><input type="text" class="form-control" name="address" placeholder="Residential Address"></div>
                      </div>
                    </p>

                    <hr>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-phone mr-1"></i> Telephone No.</strong> 
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="telno" placeholder="Telephone No.">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-mobile mr-1"></i> Cellphone No.</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="cellno" placeholder="Cellphone No.">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-envelope mr-1"></i> Email Address</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="email" class="form-control" name="email" placeholder="Email Address">
                          </p>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-calendar mr-1"></i> Date of Birth</strong> 
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="date" class="form-control" name="birthdate">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Place of Birth</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="cellno" placeholder="Municipal/Province">
                          </p>
                      </div>
                    </div>

                    <hr>
                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-flag mr-1"></i> Citizenship</strong> 
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="birthdate" placeholder="Citizenship">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-flag mr-1"></i> Citizenship Type</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="cellno" placeholder="Citizenship Type">
                          </p>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      <div class="col-md-4">
                        <strong><i class="fas fa-ruler mr-1"></i> Height(m)</strong> 
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="empheight" placeholder="Height(m)">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-weight mr-1"></i> Weight(kg)</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="empweight" placeholder="Weight(kg)">
                          </p>
                      </div>
                      <div class="col-md-4">
                        <strong><i class="fas fa-tint mr-1"></i> Blood Type</strong>
                        <br>
                        <br>
                          <p class="text-muted">
                            <input type="email" class="form-control" name="empblood" placeholder="">
                          </p>
                      </div>
                    </div>

                  </div>

                  <div class="tab-pane" id="addinfo">
                    <strong><i class="fas fa-barcode mr-1"></i> Employee Code</strong>
                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-4"><input type="text" class="form-control" name="empcode" placeholder="Employee Code"></div>
                      </div>
                    </p>
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
                
                <button type='submit' class="btn btn-success btn-lg float-right">Save</button>
                <!-- <button type='button' class="btn btn-success btn-lg float-right" onclick="submitFormAjax('frm')">Save</button> -->
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

@endsection