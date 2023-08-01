@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Employee Information</h3>
              <div class="float-right">
                <select class="form-control" name="empid" id="empid">
                  @foreach(getAllUser() AS $users)
                    <option value="{{ $users->id }}">{{ $users->lname.", ".$users->fname." ".$users->mname }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#payroll-info-tab" role="tab" aria-controls="payroll-info-tab" aria-selected="true">Information</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-membership-tab" data-toggle="pill" href="#payroll-membership-tab" role="tab" aria-controls="payroll-membership-tab" aria-selected="false">Membership</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-loans-tab" data-toggle="pill" href="#payroll-loans-tab" role="tab" aria-controls="payroll-loans-tab" aria-selected="false">Personal Loans</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-deduction-tab" data-toggle="pill" href="#payroll-deduction-tab" role="tab" aria-controls="payroll-deduction-tab" aria-selected="false">Deductions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-compensation-tab" data-toggle="pill" href="#payroll-compensation-tab" role="tab" aria-controls="payroll-deduc-tab" aria-selected="false">Compensations</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-lwop-tab" data-toggle="pill" href="#payroll-lwop-tab" role="tab" aria-controls="payroll-deduc-tab" aria-selected="false">LWOP</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-deffered-tab" data-toggle="pill" href="#payroll-deffered-tab" role="tab" aria-controls="payroll-deffered-tab" aria-selected="false">Deferred Deductions</a>
                  </li> -->
                  <li class="nav-item">
                    <a class="nav-link" id="custom-payroll-prev-lwop-tab" data-toggle="pill" href="#payroll-prev-lwop-tab" role="tab" aria-controls="payroll-prev-lwop-tab" aria-selected="false">Other Compensation</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="payroll-info-tab" role="tabpanel">
                     <div class="row">
                       <div class="col-lg-4 col-md-4 col-sm-12">
                          <?php
                            $empinfo = getStaffAllInfo($data['empid']);
                            $empid = getStaffID($data['empid']);
                            
                            //COMPENSATION
                            $compensation = getCompensation($empinfo['username']);

                            //TOTAL COMPESATION
                            $total_comp = 0;
                            foreach ($compensation as $key => $value) {
                              $total_comp += $value->compAmount;
                            }

                            //DEDUCTION 
                            $deductions = getDeductions($empinfo['username']);
                            $plantilla = getPlantillaInfo($empinfo['username']);
                            
                            //SALARY
                            $salary = $plantilla['plantilla_salary'] + $total_comp;
                            $basic_salary = $plantilla['plantilla_salary'];

                            //TOTAL DEDUCTION

                            //PERSONAL LOAN
                            $loans = getPersonalLoans($empinfo['username']);

                            //TOTAL DEDUCTION
                            $total_deduc = 0;
                            foreach ($deductions as $key => $value) {
                                $total_deduc += $value->deductAmount;
                            }

                            foreach ($loans as $key => $value) {
                                $total_deduc += $value->DED_AMOUNT;
                            }

                          ?>
                          <p><b>Employee : </b>{{ $empinfo['username'] }} {{ $empinfo['lname'].', '.$empinfo['fname'].' '.$empinfo['mname'] }}</p>
                          
                          <table width="100%" style="border:1px solid #DDD;" cellpadding="5">
                            <tr>
                              <td style="width: 30%"><b>Position : </b></td>
                              <td>{{ $plantilla['position_desc'] }}</td>
                            </tr>
                            <tr>
                              <td><b>Emp Stat  : </b></td>
                              <td>{{ $plantilla['employment_desc'] }}</td>
                            </tr>
                            <tr>
                              <td><b>Pay Stat  : </b></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td><b>Emp Date  : </b></td>
                              <td>{{ date('m/d/Y',strtotime($plantilla['first_emp_date'])) }}</td>
                            </tr>
                            <tr>
                              <td><b>Eff Date : </b></td>
                              <td><?php
                                  if($plantilla['plantilla_date_to'] == '0000-00-00')
                                  {
                                      echo "00/00/0000";
                                  }
                                  else
                                  {
                                    echo date('m/d/Y',strtotime($plantilla['plantilla_date_to']));
                                  }
                                 ?></td>
                            </tr>
                            <tr>
                              <td><b>HDMF No : </b></td>
                              <td>{{ $empid['addinfo_pagibig'] }}</td>
                            </tr>
                            <tr>
                              <td><b>GSIS No : </b></td>
                              <td>{{ $empid['addinfo_gsis_id'] }}</td>
                            </tr>
                            <tr>
                              <td><b>Philhealth : </b></td>
                              <td>{{ $empid['addinfo_philhealth'] }}</td>
                            </tr>
                            <tr>
                              <td><b>TIN : </b></td>
                              <td>{{ $empid['addinfo_tin'] }}</td>
                            </tr>
                          </table>
                     </div>

                     <div class="col-lg-4 col-md-4">
                          <p><b>Division : </b>{{ $empinfo['division_acro'] }}</p>

                          <table width="100%" style="border:1px solid #DDD;" cellpadding="3">
                            <tr>
                              <td colspan="2" align="center"><b>Deductions</b></td>
                            </tr>
                            @foreach($deductions AS $lists)
                              @if($lists->deductCode != null)
                              <tr>
                                <td><b>{{ $lists->deductName }}</b></td>
                                <td align="right">{{ formatCash($lists->deductAmount) }}</td>
                              </tr>
                              @endif
                            @endforeach
                            
                            <?php $totalloans = 0; ?>
                            @foreach($loans AS $lists)
                            <?php
                              $totalloans += $lists->DED_AMOUNT;
                            ?>  
                            @endforeach
                            <tr>
                              <td><b>Personal Loans</b></td>
                              <td align="right">{{ formatCash($totalloans) }}</td>
                            </tr>

                            <tr>
                              <td><b>Gross Salary</b></td>
                              <td align="right">{{ formatCash($salary) }}</td>
                            </tr>
                            <tr>
                              <td><b>Deduction</b></td>
                              <td align="right">({{ formatCash($total_deduc) }})</td>
                            </tr>

                            <tr>
                              <td><b>Net Salary</b></td>
                              <td align="right">{{ formatCash($salary - $total_deduc) }}</td>
                            </tr>
                             
                          </table>

                      </div>

                      <div class="col-lg-4 col-md-4">
                        <p>&nbsp</p>
                           <table width="100%" style="border:1px solid #DDD;" cellpadding="5">
                            <tr>
                              <td><b>Basic  Salary</b></td>
                              <td align="right">{{ formatCash($basic_salary) }}</td>
                            </tr>

                            @foreach($compensation AS $lists)
                              <tr>
                                <td><b>{{ $lists->compCode }}</b></td>
                                <td align="right">{{ formatCash($lists->compAmount) }}</td>
                            </tr>
                            @endforeach
                          </table>

                      </div>

                     </div>
                  </div>

                  <div class="tab-pane fade" id="payroll-membership-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm" onclick="addRecord('membership')"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Organization</th>
                         <th>Number</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                         @foreach(getMembership($empinfo['username']) AS $lists)
                          <tr>
                            <td>{{ $ctr++ }}</td>
                            <td>{{ $lists->ORG_NAME }}</td>
                            <td>{{ $lists->NUMBER }}</td>
                            <td align="center"><i class="fas fa-edit text-primary"></i> &nbsp <i class="fas fa-trash text-danger"></i></td>
                          </tr>
                          <?php $ctr++ ?>
                         @endforeach

                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-loans-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm" onclick="addRecord('loan')"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Code</th>
                         <th>Org</th>
                         <th>Service</th>
                         <th>ID No.</th>
                         <th>Start Date</th>
                         <th>End Date</th>
                         <th>Deduction per Month</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                         @foreach($loans AS $lists)
                          <tr>
                            <td>{{ $ctr++ }}</td>
                            <td>{{ $lists->SERV_CODE }}</td>
                            <td>{{ $lists->ORG_ACRO }}</td>
                            <td>{{ $lists->SERV_DESC }}</td>
                            <td>{{ $lists->SERV_NO }}</td>
                            <td></td>
                            <td></td>
                            <td align="right">{{ formatCash($lists->DED_AMOUNT) }}</td>
                            <td align="center"><i class="fas fa-edit text-primary"></i> &nbsp <i class="fas fa-trash text-danger"></i></td>
                          </tr>
                          <?php $ctr++ ?>
                         @endforeach

                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-deduction-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm" onclick="addRecord('deduction')"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Deduction Code</th>
                         <th class="text-right">Deduction per Month</th>
                         <th style="width:5%"></th>
                       </thead>
                        <?php $ctr = 1; ?>
                         @foreach($deductions AS $lists)
                          <tr>
                            <td>{{ $ctr++ }}</td>
                            <td>{{ $lists->deductCode }}</td>
                            <td align="right">{{ formatCash($lists->deductAmount) }}</td>
                            <td align="center"><i class="fas fa-edit text-primary"></i> &nbsp <i class="fas fa-trash text-danger"></i></td>
                          </tr>
                          <?php $ctr++ ?>
                         @endforeach
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-compensation-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm"  onclick="addRecord('compensation')"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Compensation</th>
                         <th class="text-right">Compensation Amount</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                         @foreach($compensation AS $lists)
                          <tr>
                            <td>{{ $ctr++ }}</td>
                            <td>{{ $lists->compCode }}</td>
                            <td align="right">{{ formatCash($lists->compAmount) }}</td>
                            <td align="center"><i class="fas fa-edit text-primary"></i> &nbsp <i class="fas fa-trash text-danger"></i></td>
                          </tr>
                          <?php $ctr++ ?>
                         @endforeach
                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-lwop-tab" role="tabpanel">
                     
                  </div>

                  <div class="tab-pane fade" id="payroll-deffered-tab" role="tabpanel">

                  </div>

                  <div class="tab-pane fade" id="payroll-prev-lwop-tab" role="tabpanel">

                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>


<div class="modal" tabindex="-1" role="dialog" id="modalAddRecord">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ADD RECORD</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" action="{{ url('dtr/update') }}">  
          {{ csrf_field() }}

          <div class="payroll-div" id="payroll-membership">
            <strong>Membership</strong>
            <br>
            <select class="form-control" name="payroll_mem" id="payroll_mem">
              
            </select>
            <br>
            <strong>Number</strong>
            <br>
            <input type="text" class="form-control" name="payroll_mem_num">
          </div>

          <div class="payroll-div" id="payroll-loan">
            <strong>Personal Loan</strong>
            <br>
            <select class="form-control" name="payroll_loan" id="payroll_loan">
            </select>
            <br>

            <div class="row">
              <div class="col-6">
                <strong>Total Amount</strong>
                  <br>
                  <input type="number" class="form-control" name="payroll_loan_amt">
              </div>
              <div class="col-6">
                <strong>Number of Months</strong>
                  <br>
                  <input type="number" class="form-control" name="payroll_loan_mon">
              </div>
            </div>

            <br>
            <strong>Deduction</strong>
            <br>
            <div class="row">
              <div class="col-6">
                <strong>Salary</strong>
                  <br>
                  <input type="number" class="form-control" name="payroll_loan_salary">
              </div>
              <div class="col-6">
                <strong>Magna Carta</strong>
                  <br>
                  <input type="number" class="form-control" name="payroll_loan_mc">
              </div>
            </div>

          </div>


          <div class="payroll-div" id="payroll-deduction">
            <strong>Deduction</strong>
            <br>
            <select class="form-control" name="payroll_deduc" id="payroll_deduc">
              
            </select>
            <br>
            <strong>Deduction per Month</strong>
            <br>
            <input type="number" class="form-control" name="payroll_deduc_mon">
          </div>


          <div class="payroll-div" id="payroll-compensation">
            <strong>Compensation</strong>
            <br>
            <select class="form-control" name="payroll_comp" id="payroll_comp">
              
            </select>
            <br>
            <strong>Compensation Amount</strong>
            <br>
            <input type="number" class="form-control" name="payroll_comp_amt">
          </div>


        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    
    </div>
  </div>
</div>
@endsection



@section('JS')

<script>
  $('#empid').val({{ $data['empid'] }});

  $("#empid").change(function(){
    window.location.replace("{{ url('payroll/emp') }}/" + this.value);
  })

  $('.table').DataTable();


function addRecord(type)
{
  $(".payroll-div").hide();

  switch(type)
  {
    case "membership":
       $("#payroll_mem").empty();
       $.getJSON( "{{ url('payroll/json/') }}/"+type, function( datajson ) {
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                       $("#payroll_mem").append("<option value='"+obj.ORG_CODE+"'>"+obj.ORG_ACRO+"</option>");  
                    });
              }).fail(function() {
            });
      $("#payroll-membership").show();
    break;


    case "loan":
       $("#payroll_loan").empty();
       $.getJSON( "{{ url('payroll/json/') }}/"+type, function( datajson ) {
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                       $("#payroll_loan").append("<option value='"+obj.SERV_CODE+"'>"+obj.ORG_ACRO+"-"+obj.SERV_ACRO+"</option>");  
                    });
              }).fail(function() {
            });
      $("#payroll-loan").show();
    break;

    case "deduction":
       $("#payroll_deduc").empty();
       $.getJSON( "{{ url('payroll/json/') }}/"+type, function( datajson ) {
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                       $("#payroll_deduc").append("<option value='"+obj.deductID+"'>"+obj.deductCode+"</option>");  
                    });
              }).fail(function() {
            });
      $("#payroll-deduction").show();
    break;


    case "compensation":
       $("#payroll_comp").empty();
       $.getJSON( "{{ url('payroll/json/') }}/"+type, function( datajson ) {
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                       $("#payroll_comp").append("<option value='"+obj.compID+"'>"+obj.compName+"</option>");  
                    });
              }).fail(function() {
            });
      $("#payroll-compensation").show();
    break;



  }

  $("#modalAddRecord").modal('toggle');
}
</script>
@endsection