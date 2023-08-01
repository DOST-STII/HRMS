<!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4">
            <!-- <div class="info-box" style="cursor: pointer" onclick="window.location.replace('{{ url('dtr/request-leave') }}')"> -->
              <div class="info-box" style="cursor: pointer" onclick="showRequest('Apply for Leave')">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Apply for Leave</span>
                <span class="info-box-number">
                  
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <a href="{{ route('staff_to') }}">
          <div class="col-12 col-sm-6 col-md-4">
            <!-- <div class="info-box mb-3 request-for" style="cursor: pointer" onclick="window.location.replace('{{ url('dtr/request-for-to') }}')"> -->
             
                <div class="info-box" style="cursor: pointer">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-shuttle-van"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Apply for Travel Order</span>
                    <span class="info-box-number">
                      
                    </span>
                </div>
             
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-4">
            <!-- <div class="info-box mb-3" style="cursor: pointer" onclick="window.location.replace('{{ url('dtr/request-for-ot') }}')"> -->
              <div class="info-box" style="cursor: pointer" onclick="showRequest('Request for O.T. / CTO')">
              <span class="info-box-icon bg-success elevation-1"><i class="far fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Request for Overtime<br/>Apply for Compensatory Time-Off</span>
                <span class="info-box-number">
                  
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
         

          

        </div>
        <!-- /.row -->


<!-- RESET PASSWORD MODAL-->
      <div class="modal fade" id="modal-request-for">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-request-for-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="POST" id="frm_request" enctype="multipart/form-data">
              {{ csrf_field() }}
              
              @if(Auth::user()->usertype == 'Marshal')
              <div class="form-group">
                <strong>Employee</strong>
              
                <p class="text-muted">
                      <select class="form-control" name="userid2" id="userid2">
                        @foreach(getStaffDivision() AS $divs)
                          <option value="{{ $divs->id }}">{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>
                        @endforeach
                      </select>
                </p>
              </div>
              @else
                <input type="hidden" name="userid2"  id="userid2" value="{{ Auth::user()->id }}">
              @endif

              <div id="cto-choice" style="display:none;">
              <input type="hidden" value="" name="ctorequest" id="ctorequest">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="requestot" name="request_cto" value="request_ot" checked>
                        <label for="requestot">
                          Request O.T
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="requestcto" name="request_cto" value="apply_cto">
                        <label for="requestcto">
                          Leave Application (CTO)
                        </label>
                      </div>
                    </div>


                    </div>
</div>

              <!-- LEAVE TYPE -->
              <div class="div-request" id="div-request-leave">
                <div id="option-leave">
                  <strong>Leave Type</strong>
                    <br>
                    <p class="text-muted">
                      <select class="form-control" name='leave_id' id='leave_id'>
                        
                        <?php
                            $lv = App\Leave_type::whereNotIn('id',[5,4,13,14,15,16,19])->get();
                            foreach ($lv as $key => $lvs) {
                                echo '<option value="'.$lvs->id.'">'.$lvs->leave_desc.'</option>';
                            }
                        ?>
                      </select>
                    </p>
                    <!-- <small class="badge badge-warning">Pls note that .....</small> -->
                  </div>

                  <div id="cto_bal" style="display: none;">
                          <div class="alert alert-warning" id="ctobalance">
                              
                          </div>
                  </div>

                  <strong>Duration</strong>
                    <div class="form-group">

                      <div class="input-group" id="option-leave-duration">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration" name="leave_duration">
                      </div>

                      <div class="input-group" id="option-leave-duration2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration2" name="leave_duration2">
                      </div>

                      <div class="input-group" id="option-leave-duration3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration3" name="leave_duration3">
                      </div>


                      <div class="input-group" id="option-leave-duration4">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration4" name="leave_duration4">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <p class="text-muted">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_wholeday" name="leave_time" value="wholeday" checked>
                        <label for="leave_time_wholeday">
                          Whole day
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_am" name="leave_time" value="AM">
                        <label for="leave_time_am">
                          AM
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_pm" name="leave_time" value="PM">
                        <label for="leave_time_pm">
                          PM
                        </label>
                      </div>
                    </div>
                    </div>
                    </p>

                    <div id="option-vl-select">
                      <br>
                      <strong>In case of Vacation/Special Privilege Leave</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vl_select1" name="vl_select" value="Within the Philippines" checked>
                          <label for="vl_select1">
                            Within the Philippines
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vl_select2" name="vl_select" value="Abroad">
                          <label for="vl_select2">
                            Abroad
                          </label>
                        </div>
                      </p>
                      <input type="text" class="form-control" name="vl_select_specify" id="vl_select_specify"  placeholder="Specify" style="display:none">
                    </div>

                    <div id="option-sl-select">
                      <br>
                      <strong>In case of Sick Leave</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="sl_select1" name="sl_select" value="Hospital" checked>
                          <label for="sl_select1">
                            Hospital
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="sl_select2" name="sl_select" value="Out Patient">
                          <label for="sl_select2">
                            Out Patient
                          </label>
                        </div>
                      </p>
                      <input type="text" class="form-control" name="sl_select_specify" id="sl_select_specify"  placeholder="Specify Illness">
                    </div>

                    <div id="option-to">
                      <strong>Vehicle</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_official1" name="vehicle" value="Official" checked>
                          <label for="vehicle_official1">
                            Official
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_personal2" name="vehicle" value="Personal">
                          <label for="vehicle_personal2">
                            Personal
                          </label>
                        </div>

                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_personal3" name="vehicle" value="Public Utility Vehicle">
                          <label for="vehicle_personal3">
                            Public Utility Vehicle
                          </label>
                        </div>
                      </p>

                      <strong>Per Diem</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="perdiem_yes" name="perdiem" value="YES" checked>
                          <label for="perdiem_yes">
                            Will Claim
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="perdiem_no" name="perdiem" value="NO">
                          <label for="perdiem_no">
                            Will Not Claim
                          </label>
                        </div>
                      </p>

                      <strong>Cash Advance</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="checkbox" id="cash_adv_local" name="cash_adv" value="Local">
                          <label for="cash_adv_local">
                            Local
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="checkbox" id="cash_adv_for" name="cash_adv" value="Foreign">
                          <label for="cash_adv_for">
                            Foreign
                          </label>
                        </div>
                      </p>

                      <strong>Place</strong>
                      <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="place" id="place">
                        </p>

                      <strong>Purpose</strong>
                      
                      <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="purpose" id="purpose">
                        </p>

                    </div>

                    <div id="option-cto">
                      <div id="div_request_ot">
                          <strong>Reason</strong>
                          <br>
                          
                          <p class="text-muted">
                            <input type="text" class="form-control" name="ot_purpose" id="ot_purpose">
                          </p>

                          <strong>Expected Output</strong>
                          <br>
                          <p class="text-muted">
                            <input type="text" class="form-control" name="ot_output" id="ot_output">
                          </p>
                          </div>

                          <br>
                          <p>
                            <a href="{{ asset('files/OT_Guidelines.pdf') }}" target="_blank"><i class="fas fa-file-pdf"></i> Guidelines on Overtime Services</a>
                          </p>
                          <p>
                            <a href="{{ asset('files/OT_Guidelines2.pdf') }}" target="_blank"><i class="fas fa-file-pdf"></i> Joint Circular CSC-DBM No. 02 2015</a>
                          </p>
                    </div>

                    <div id="option-wfh">
                      <strong>Reason</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="wfh_reason" id="wfh_reason">
                      </p>

                      <strong>Expected Output</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="wfh_output" id="wfh_output">
                      </p>
                      <input type="hidden" value="" name="wfhrequest" id="wfhrequest">
                      
                      
                      <center><span class="panel panel-success"><a href="{{ asset('files/Health_Declaration_Form.pdf') }}" target="_blank">Download Health Declaration Form</a></span></center>
                    </div>


                    <div id="option-remarks">
                      <strong>Remarks</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="remarks" id="remarks">
                      </p>
                    </div>

                    <div id="option-desc" style="display: none;">
                      <strong>Description</strong>
                      <br>
                      <p class="text-muted" id="leave_def">
                        <?php
                          $lv = getLeaveInfo2(1);
                          echo $lv['leave_def'];
                        ?>
                      </p>
                    </div>


                </div>
                
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="modalOnSubmit()">Submit</button>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->