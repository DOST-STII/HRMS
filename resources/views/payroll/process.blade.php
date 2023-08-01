@extends('template.master')

@section('CSS')
  <!-- MAINE INCLUDE MO TO -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Preview/Process Payroll</h3>
              <div class="card-tools">

                  <button type="button" class="btn bg-primary" onclick="submitFrm()">
                    <?php
                      //GET NEXT MONtr
                      //$payroll_mon = date('n');
                      //CHECK PROCESS MONTH
                      $proc = App\Payroll\PrevInfotbl::orderBy('fldPrevID','DESC')->first();

                      if($proc['fldMonth'] == date('m') && $proc['fldYear'] == date('Y'))
                      {
                        $payroll_mon = date('n',strtotime('first day of +1 month'));
                        //$payroll_mon = date('n');
                      }
                      else
                      {
                        $payroll_mon = date('n');
                      }
                      //$payroll_mon = date('n',strtotime('first day of +1 month'));
                      
                      $yr = date('Y');
                      
                      $payroll_year = $yr;

                      if($payroll_mon == 13)
                      {
                        $payroll_mon = 1;
                        $payroll_year = $yr++;
                      }

                      $curr_mon = date('F',mktime(0, 0, 0, $payroll_mon, 10));

                      $montrs = array(1 => "JAN", 2 =>"FEB", 3 => "MAR", 4 => "APR", 5 => "MAY", 6 => "JUN", 7 => "JUL", 8 => "AUG", 9 => "SEP", 10 => "OCT", 11 => "NOV", 12 => "DEC");

                      $next_mon = $montrs[$payroll_mon];

                    ?>
                    <i class="fas fa-cog"></i> Proccess {{ $curr_mon }}
                  </button>
                  

                  <button type="button" class="btn bg-info" onclick="previewPayroll({{ $payroll_mon }},{{ $payroll_year }})">
                    <i class="fas fa-eye"></i> Preview
                  </button>

                  <!-- <form id="frm" metrod="POST"> -->
                  <form id="frm2" method="POST" action="{{ url('payroll/create') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('payroll/create') }}">
                    <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('payroll/process') }}">
                    <input type="hidden" name="path" value="{{ $payroll_year.'-'.date('m',strtotime($curr_mon)).'_'.$next_mon }}">
                    <input type="hidden" name="payrollmon" value="{{ $next_mon }}">
                  </form>


                  <form id="frm3" method="POST" action="{{ url('payroll/preview') }}" target="_blank">
                    {{ csrf_field() }}
                    <input type="hidden" name="payrollmon" id="payrollmon" value="">
                    <input type="hidden" name="payrollyear" id="payrollyear" value="">
                  </form>

                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-trree-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-trree-home-tab" data-toggle="pill" href="#custom-tabs-trree-home" role="tab" aria-controls="custom-tabs-trree-home" aria-selected="true">Deductions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-trree-home-tab" data-toggle="pill" href="#custom-tabs-trree-home2" role="tab" aria-controls="custom-tabs-trree-home2" aria-selected="true">Other Deductions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-trree-profile-tab" data-toggle="pill" href="#custom-tabs-trree-profile" role="tab" aria-controls="custom-tabs-trree-profile" aria-selected="false">Text File for Landbank</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-trree-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-trree-home" role="tabpanel" aria-labelledby="custom-tabs-trree-home-tab">
                    <table class="table" id="tbl_deduc" style="width: 100%;font-size:10px">
                      <thead>
                      <th>ID</th>
                        <th style="width: 25% !important;">Employee</th>
                        <th>Basic Salary</th>
                        <th>Net Salary</th>
                        <th>Rep Allow</th>
                        <th>Trans Allow</th>
                        <th>Comp Adj</th>
                        <th>ITW</th>
                        <th>SIC</th>
                        <th>Phil Health</th>
                        <th>HMDF</th>
                        <th>HMDF 2</th>
                        <th>GSIS Policy</th>
                        <th>GSIS Conso</th>
                        <th>GSIS EML</th>
                        <th>GSIS Educ</th>
                        <th>GSIS Opt Policy</th>
                        <th>GSIS CP</th>
                        <th>GSIS MP</th>
                        <th>GSIS GFAL</th>
                        <th>HMDF MP</th>
                        <th>HMDF Housing</th>
                        <th>CDC FD</th>
                        <th>CDC SD</th>
                        <th>CDC Loan</th>
                        <th>PMPC HMO</th>
                        <th>PMPC FD</th>
                        <th>PMPC SD</th>
                        <th>PMPC Loan</th>
                        <th>Landbank</th>
                        <th>Others</th>
                        <th>Total Deduction</th>
                        <th>Week1</th>
                        <th>Week2</th>
                        <th>Week3</th>
                        <th>Week4</th>
                      </thead>
                      <tbody>
                        <?php
                          $user = App\User::whereIn('employment_id',[1,11,13,14,15])->where('payroll',1)->get();


                          foreach ($user as $key => $users) {

                          $plantilla = getPlantillaInfo($users->username);
                          
                          if($plantilla)
                          {
                            //COMP
                            $ra = 0;
                            $ta = 0;
                            $pera = 0;
                            $addcom = 0;

                            $comp = App\Payroll\Compensation::where('empCode',$users->username)->get();
                            foreach ($comp as $c => $comps) {
                                switch ($comps->compID) {
                                    case 1:
                                            $pera = $comps->compAmount;
                                        break;
                                     case 2:
                                          $addcom = $comps->compAmount;
                                      break;
                                    case 3:
                                            $ra = $comps->compAmount;
                                        break;
                                    case 4:
                                            $ta = $comps->compAmount;
                                        break;
                                }
                            }
                            $salary = $plantilla['plantilla_salary'] + $pera;
                            $basic = $plantilla['plantilla_salary'];
                          }
                          else
                          {
                            $salary = 0;
                          }

                          
                          $d_total = 0;

                          //MANDA
                          $d_itw = 0;
                          $d_sic = 0;
                          //$d_ph = 0;
                          $d_hmdf = 0;

                          //PHILHEALTH
                          $d_ph = computePhil($basic);
                                              
                                              if($d_ph >= 1600)
                                              {
                                                $d_ph = 1600;
                                                $d_total += $d_ph;
                                              }
                                              else
                                              {
                                                $d_total += $d_ph;
                                              }

                          $deductions = getDeductions($users->username);
                          
                          foreach ($deductions as $kd => $vd) {
                            
                                  switch ($vd->deductID) {
                                      case 1:
                                              $d_itw = $vd->deductAmount;
                                              $d_total += $vd->deductAmount;
                                          break;
                                      case 2:
                                              //$d_sic = $vd->deductAmount;
                                              // $d_sic = $basic * 0.09;
                                              // $d_total += $d_sic;
                                          break;
                                      case 3:
                                              //$d_ph = $vd->deductAmount;
                                              //$d_ph = $basic * 0.015;
                                              // $d_ph = computePhil($basic);
                                              
                                              // if($d_ph >= 1600)
                                              // {
                                              //   $d_ph = 1600;
                                              //   $d_total += $d_ph;
                                              // }
                                              // else
                                              // {
                                              //   $d_total += $d_ph;
                                              // }
                                                
                                          break;
                                      case 4:
                                              $d_hmdf = $vd->deductAmount;
                                              $d_total += $vd->deductAmount;
                                          break;
                                  }
                            }

                            $d_sic = $basic * 0.09;
                            if($d_sic > 0)
                            {
                              $d_total += $d_sic;
                            }

                            //LOANS
                            $d_hmdf2 = 0;
                            $d_gspol = 0;
                            $d_gscon = 0;
                            $d_gseml = 0;
                            $d_gseduc = 0;
                            $d_gsopt = 0;
                            $d_gscp = 0;
                            $d_gsmp = 0;
                            $d_gsgfal = 0;
                            $d_hmdfmp = 0;
                            $d_hmdfhouse = 0;
                            $d_cdcfd = 0;
                            $d_cdcsd = 0;
                            $d_cdcloan = 0;
                            $d_pmpchmo = 0;
                            $d_pmpcfd = 0;
                            $d_pmpcsd = 0;
                            $d_pmpcloan = 0;
                            $d_ldp = 0;
                            $d_others = 0;
                            

                            $other_txt = "";

                            $loans = getPersonalLoans($users->username);

                            foreach ($loans as $kls => $vls) 
                            {
                              
                                switch ($vls->SERV_CODE) {
                                    case "302C":
                                            $d_hmdf2 = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                    break;
                                    case "305":
                                            $d_gspol = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "319":
                                            $d_gscon = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "316":
                                            $d_gseml = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "315":
                                            $d_gseduc = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "305A":
                                            $d_gsopt= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "319C":
                                            $d_gscp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "319B":
                                            $d_gsmp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "319A":
                                            $d_gsgfal= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "302A":
                                            $d_hmdfmp= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "302B":
                                            $d_hmdfhouse= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "920":
                                            //$d_cdcfd = $vls->DED_AMOUNT;
                                            if($vls->DED_AMOUNT > 0)
                                              $d_cdcfd = $basic * 0.02;
                                            else 
                                              $d_cdcfd = 0;

                                              $d_total += $d_cdcfd;
                                        break;
                                    case "922":
                                            $d_cdcsd= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "921":
                                            $d_cdcloan= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "933":
                                            $d_pmpchmo= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "930":
                                            //$d_pmpcfd = $vls->DED_AMOUNT;
                                            //$d_pmpcfd = $basic * 0.02;
                                            if($vls->DED_AMOUNT > 0)
                                              $d_pmpcfd = $basic * 0.02;
                                            else 
                                              $d_pmpcfd = 0;

                                              $d_total += $d_pmpcfd;
                                        break;
                                    case "932":
                                            $d_pmpcsd= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "931":
                                            $d_pmpcloan= $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    case "321":
                                            $d_ldp = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                        break;
                                    default:
                                            $d_others = $vls->DED_AMOUNT;
                                            $d_total += $vls->DED_AMOUNT;
                                            $other_txt .= $vls->SERV_CODE.",";
                                    break;
                                }
                            }

                            $netsalary = $salary - $d_total;
                            //WARNING
                            $warning = "";
                            if($netsalary < 5000)
                            {
                              $warning = "class = 'text-danger' style='font-weight:bold'";
                            }

                            //FOR DB
                            for ($i=1; $i <= 4 ; $i++) 
                            { 
                                $sal = getWeekSalary($users->username,$netsalary,$i,1);
                                switch($i)
                                {
                                    case 1:
                                        $wk1 = $sal;
                                    break;

                                    case 2:
                                    case 3:
                                        $wk = $sal;
                                    break;

                                    case 4:
                                        $wk4 = $sal;
                                    break;
                                }
                            }

                             echo "
                             <tr>
                             <td>".$users->username."</td>
                             <td>".getStaffInfo($users->id,'fullname')."</td>
                             <td>".formatCash($basic)."</td>
                             <td ".$warning.">".formatCash($netsalary)."</td>
                             <td style='cursor:pointer;' onclick='editValue(4,\"".$users->username."\",3)' class='text-primary'><b>".formatCash($ra)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(4,\"".$users->username."\",4)' class='text-primary'><b>".formatCash($ta)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(4,\"".$users->username."\",2)' class='text-primary'><b>".formatCash($addcom)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(1,\"".$users->username."\",1)' class='text-primary'><b>".formatCash($d_itw)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(1,\"".$users->username."\",2)' class='text-primary'><b>".formatCash($d_sic)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(1,\"".$users->username."\",3)' class='text-primary'><b>".formatCash($d_ph)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(1,\"".$users->username."\",4)' class='text-primary'><b>".formatCash($d_hmdf)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"302C\")' class='text-primary'><b>".formatCash($d_hmdf2)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"305\")' class='text-primary'><b>".formatCash($d_gspol)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"319\")' class='text-primary'><b>".formatCash($d_gscon)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"316\")' class='text-primary'><b>".formatCash($d_gseml)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"315\")' class='text-primary'><b>".formatCash($d_gseduc)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"305A\")' class='text-primary'><b>".formatCash($d_gsopt)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"319C\")' class='text-primary'><b>".formatCash($d_gscp)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"319B\")' class='text-primary'><b>".formatCash($d_gsmp)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"319A\")' class='text-primary'><b>".formatCash($d_gsgfal)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"302A\")' class='text-primary'><b>".formatCash($d_hmdfmp)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"302B\")' class='text-primary'><b>".formatCash($d_hmdfhouse)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"920\")' class='text-primary'><b>".formatCash($d_cdcfd)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"922\")' class='text-primary'><b>".formatCash($d_cdcsd)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"921\")' class='text-primary'><b>".formatCash($d_cdcloan)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"933\")' class='text-primary'><b>".formatCash($d_pmpchmo)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"930\")' class='text-primary'><b>".formatCash($d_pmpcfd)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"932\")' class='text-primary'><b>".formatCash($d_pmpcsd)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"931\")' class='text-primary'><b>".formatCash($d_pmpcloan)."</b></td>
                             <td style='cursor:pointer;' onclick='editValue(2,\"".$users->username."\",\"321\")' class='text-primary'><b>".formatCash($d_ldp)."</b></td>
                             <td><b><input type='hidden' value='".$other_txt."'>".formatCash($d_others)."</b></td>
                             <td><b>".formatCash($d_total)."</b></td>
                             <td><b>".formatCash($wk1)."</b></td>
                             <td><b>".formatCash($wk)."</b></td>
                             <td><b>".formatCash($wk)."</b></td>
                             <td><b>".formatCash($wk4)."</b></td>
                             </tr>";
                             
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade show" id="custom-tabs-trree-home2" role="tabpanel" aria-labelledby="custom-tabs-trree-home-tab2">
                    <table class="table" id="tbl_deduc_other" style="width: 100%;">
                      <thead>
                        <th>Employee</th>
                        <th>Description</th>
                        <th>Amount</th>
                      </thead>
                      <tbody>
                        <?php

                          $loan = App\Payroll\PersonalLoan::whereNotIn('SERV_CODE',['302C','305','319','316','305A','319C','319B','302A','302B','920','922','921','930','305','932','931','321'])->get();

                          foreach ($loan as $key => $loans) {
                            $user = App\User::where('username',$loans->fldEmpCode)->first();

                            if($user)
                            {
                                echo "<tr>
                                    <td>".getStaffInfo($user['id'],'fullname')."</td>
                                    <td>".$loans->ORG_ACRO." - ".$loans->SERV_ACRO."</td>
                                    <td style='cursor:pointer;' onclick='editValue(2,\"".$loans->fldEmpCode."\",\"".$loans->SERV_CODE."\")' class='text-primary'>".formatCash($loans->DED_AMOUNT)."</td>    
                               </tr>";
                            }
                            
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-trree-profile" role="tabpanel" aria-labelledby="custom-tabs-trree-profile-tab">

              <table width="100%" id="tbl_ldp" class="table" style="border:1px solid #DDD;" cellpadding="5">
                  <thead>
                    <th>YEAR</th>
                    <th>MONTH</th>
                    <th>WEEK1</th>
                    <th>WEEK2</th>
                    <th>WEEK3</th>
                    <th>WEEK4</th>
                    <th><center>ACTION</center></th>
                  </thead>
                  <tbody>
                    @foreach(getPayrollList2() AS $dirs)
                        <tr>
                          <td>{{ $dirs->fldYear }}</td>
                          <td>
                            <span style='display:none'>{{ $dirs->fldMonth }}</span>
                            <?php
                              //echo $dirs->fldMonth;
                              $mon2 = date('F',mktime(0, 0, 0, $dirs->fldMonth, 10));
                              echo strtoupper(date('F',strtotime($mon2)));
                            ?>
                            </td>
                          <td><a href='{{ url("payroll/text-file/".$dirs->fldMonth."/".$dirs->fldYear."/1" ) }}' target='_blank'>TEXT WEEK 1</a></td>
                          <td><a href='{{ url("payroll/text-file/".$dirs->fldMonth."/".$dirs->fldYear."/2" ) }}' target='_blank'>TEXT WEEK 2</a></td>
                          <td><a href='{{ url("payroll/text-file/".$dirs->fldMonth."/".$dirs->fldYear."/3" ) }}' target='_blank'>TEXT WEEK 3</a></td>
                          <td><a href='{{ url("payroll/text-file/".$dirs->fldMonth."/".$dirs->fldYear."/4" ) }}' target='_blank'>TEXT WEEK 4</a></td>
                          <td><center><button class="btn btn-primary" onclick='previewPayroll({{ $dirs->fldMonth }},{{ $dirs->fldYear }})'>Print</center></td>
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
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
<div class="modal" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="txt_title">EDIT VALUE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" id="frm_deducedit" enctype="multipart/form-data" role="form" action="{{ url('payroll/salary-deduc-manda-loan-edit') }}">  
        {{ csrf_field() }}
              <input type="hidden" class="form-control" name="deduc_type" id="deduc_type" value="">
              <input type="text" class="form-control" name="deduc_val" id="deduc_val" value="">
              <input type="hidden" class="form-control" name="org_serv" id="org_serv"  value="">
              <input type="hidden" class="form-control" name="comp_id" id="comp_id"  value="">
              <input type="hidden" class="form-control" name="deduc_username" id="deduc_username"  value="">
      </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="submitFrm2()">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('JS')
<!-- MAINE INCLUDE MO TO -->
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $('#tbl_deduc').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                className: "bg-success",
                text: '<i class="fas fa-file-excel"></i> Export to Excel'
            }
        ]
    } );

    $('#tbl_deduc_other,#tbl_ldp').DataTable({
        "order": [[ 0, "desc" ],[ 1, "desc" ]]
    } );

    function editValue(ty,empcode,id = null,org = null,serv = null)
    {
      $("#txt_title").empty().text("EDIT VALUE");
      $("#deduc_username").val(empcode);
      $("#org_serv").val(id);
      if(ty == 1)
      {
        $("#deduc_type").val(1);
      
        $.getJSON( "{{ url('payroll/salary-deduc-manda/json/') }}/"+empcode+"/"+id, function( datajson ) {
              }).done(function(datajson) {
                $("#deduc_val").val(datajson);
            }).fail(function() {
          });
      }
      else if(ty == 2)
      {
        $("#deduc_type").val(2);

        $.getJSON( "{{ url('payroll/salary-deduc-loan/json/') }}/"+empcode+"/"+id, function( datajson ) {
              }).done(function(datajson) {
                
                $("#deduc_val").val(datajson);

            }).fail(function() {
              $("#txt_title").empty().text("ADD ENTRY");
              $("#deduc_type").val(3);
          });

      }
      else if(ty == 4)
      {
        $("#deduc_type").val(4);
        $("#comp_id").val(id);

        $.getJSON( "{{ url('payroll/emp-comp/json/') }}/"+empcode+"/"+id, function( datajson ) {
              }).done(function(datajson) {
                
                $("#deduc_val").val(datajson);

            }).fail(function() {
              $("#txt_title").empty().text("ADD ENTRY");
          });
      }
      
      $("#modalEdit").modal("toggle");
    }

    function submitFrm()
    {
      $("#overlay").show();
      $("#frm2").submit();
    }

    function submitFrm2()
    {
      $("#overlay").show();
      $("#frm_deducedit").submit();
    }

    function previewPayroll($mon,$yr)
    {
      $("#payrollmon").val($mon);
      $("#payrollyear").val($yr);
      
      $("#frm3").submit();
    }
</script>
@endsection