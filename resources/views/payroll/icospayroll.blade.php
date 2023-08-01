@extends('template.master')

@section('CSS')
<link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

<?php
$grandtotalsalary = 0;
$grandtotalwp = 0;
$grandtotalearned = 0;
$grandtotaldeduc = 0;
$grandtotalnet = 0;
$grandtotalitw = 0;
$grandtotalhmdf = 0;
$grandtotalpmpc = 0;
$grandtotalother = 0;

if(isset($staffid))
{
  $userid = $staffid;
}
else
{
  $staffid = Auth::user()->id;
}

$countstaff = count(getAllICOS());
$countstaffprocess = getProcessCOS(0,$data['mon'],$data['yr'],$data['period'],'total');
$totalprocess = checkPendingCOSProcess2($data['mon'],$data['yr'],$data['period']);


//DAYS IN MONTH
$daysinmonth = getDaysInMonth($data['yr'].'-'.$data['mon'].'-01');

//DAYS IN PERIOD
switch ($daysinmonth ) {
  case 28:
      $period2 = 13;
    break;
  case 29:
      $period2 = 14;
    break;
  case 30:
      $period2 = 15;
    break;
  case 31:
      $period2 = 16;
    break;

}

$daysperiod2 = 15;
if($data['period'] == 2)
  $daysperiod2 = $period2;

?>
<div class="row">
  <div class="col-lg-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>COS PAYROLL</b></h3>
                <div class="card-tools">
                  @if(Auth::user()->usertype == 'Administrator')
                    @if(!getProcessCOSPayroll($data['mon'],$data['yr'],$data['period']))
                        @if($totalprocess != 0)
                          <button class="btn btn-danger" onclick="submitFrm()">Lock Payroll</button>
                        @endif
                    @else
                      @if($countstaff != $countstaffprocess)
                        @if($totalprocess != 0)
                          <button class="btn btn-danger" onclick="submitFrm()">Lock Payroll</button>
                        @endif
                      @endif
                      <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Print Payroll
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          @foreach(checkDateCOSProcess($data['mon'],$data['yr'],$data['period']) AS $list)
                            <a class="dropdown-item" href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period'].'/'.date('Y-m-d',strtotime($list->process_date)).'/print/0') }}" target="_blank">{{ $list->process_date }}</a>
                          @endforeach
                          </div>
                        </div>


                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Download Textfile
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          @foreach(checkDateCOSProcess($data['mon'],$data['yr'],$data['period']) AS $list)
                            <a class="dropdown-item" href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period'].'/'.date('Y-m-d',strtotime($list->process_date)).'/textfile/101') }}" target="_blank">101</a>
                            <a class="dropdown-item" href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period'].'/'.date('Y-m-d',strtotime($list->process_date)).'/textfile/184') }}" target="_blank">184MOOE</a>
                            <a class="dropdown-item" href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period'].'/'.date('Y-m-d',strtotime($list->process_date)).'/textfile/184PS') }}" target="_blank">184PS</a>
                            <a class="dropdown-item" href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period'].'/'.date('Y-m-d',strtotime($list->process_date)).'/textfile/184C') }}" target="_blank">184C</a>
                          @endforeach
                          </div>
                        </div>
                        
                      <!-- <a href="{{ url('payroll/cos-print/'.$data['mon'].'/'.$data['yr'].'/'.$data['period']) }}" class="btn btn-success" target="_blank">Print Payroll</a > -->

                      @if(checkSummaryCOSDTR($data['mon'],$data['yr'],$data['period']))
                        <a class="btn btn-warning" href="{{ url('dtr/cos-summary/'.$data['mon'].'/'.$data['yr'].'/'.$data['period']) }}" target="_blank">Print DTR Summary</a>
                      @endif

                    @endif
                  @elseif(Auth::user()->usertype == 'Marshal' || Auth::user()->id == 233 || Auth::user()->id == 295)
                    @if(checkSummaryCOSDTR($data['mon'],$data['yr'],$data['period']))
                        <a class="btn btn-warning" href="{{ url('dtr/cos-summary/'.$data['mon'].'/'.$data['yr'].'/'.$data['period']) }}" target="_blank">Print DTR Summary</a>
                      @endif
                  @endif
                  <button class="btn btn-primary" data-toggle="modal" data-target="#modalFilter">Filter</button>
                </div>
              </div>
              <div class="card-body">
              <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form"> -->
              <form method="POST" id="frm" enctype="multipart/form-data" role="form" action="{{ url('payroll/cos/process') }}">
                {{ csrf_field() }}
                <input type="hidden" id="frm_url_action" value="{{ url('payroll/cos/process') }}">
                <input type="hidden" id="frm_url_reset" value="{{ url('icos/payroll/'.$data['mon'].'/'.$data['yr'].'/'.$data['period']) }}">
                <input type="hidden" class="form-control" name="process_mon" id="process_mon"  value="{{ $data['mon'] }}">
                <input type="hidden" class="form-control" name="process_yr" id="process_yr"  value="{{ $data['yr'] }}">
                <input type="hidden" class="form-control" name="process_period" id="process_period"  value="{{ $data['period'] }}">

              <center><h1 class="alert w-25"><center>{!! date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['yr'].'<h5>Period : '.getPeriodCOS($data['period'],$data['mon'],$data['yr']).'</h5>'  !!}</center></h1></center>
              <table class="table table-bordered" id="tbl" style="width: 100%;font-size:12px">
                <thead>  
                <tr>
                    <th style="width:15%"><b>PERSONNEL</b></center></th>
                    <th><center><b>ORS NO.</b></center></th>
                    <th><center><b>ATM #</b></center></th>
                    <th><center><b>Salary</b></center></th>
                    <th><center><b># Days in a Month</b></center></th>
                    <th><center><b># Days in the Period</b></center></tth>
                    <th><center><b>Days Without Pay</b></center></th>
                    <th><center><b>Days Without Pay (Amount)</b></center></th>
                    <th><center><b>Earned for the Period</b></center></th>
                    <th><center><b>Charging</b></center></th>
                    <th><center><b>Tax Rate</b></center></th>
                    <th><center><b>ITW</b></center></th>
                    <th><center><b>HMDF</b></center></th>
                    <th><center><b>PMPC</b></center></th>
                    <th><center><b>Other Deduction</b></center></th>
                    <th><center><b>Total Deduction</b></center></th>
                    <th><center><b>NET</b></center></th>
                    @if(Auth::user()->usertype == 'Administrator')
                    <th style="width:8%"><center><b></b></center></th>
                    @endif
                  </tr>
                </thead>
                  <tbody>
                    @foreach(getAllICOS() AS $divs)
                        <?php
                          $salary = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'salary');
                          if($salary == null)
                            $salary = getCOSSalary($divs->id,'salary','num');

                          $grandtotalsalary += $salary;
                          $nodays = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'nodays');
                          
                          $daysperiod = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'daysperiod');
                          
                          if($daysperiod == null)
                            $daysperiod = $daysperiod2;


                          $dayswithoutpay = round((($salary / $daysinmonth) * $nodays),2);
                          $grandtotalwp += $dayswithoutpay;


                          $hmdf = getDeductionCOS($divs->id,'HDMF',$data['mon'],$data['yr'],$data['period'],'num');
                          $grandtotalhmdf += $hmdf;
                          //$itw = getDeductionCOS($divs->id,'ITW',$data['mon'],$data['yr'],$data['period'],'num');
                          //$grandtotalitw += $itw;
                          $pmpc = getDeductionCOS($divs->id,'PMPC',$data['mon'],$data['yr'],$data['period'],'num');
                          $grandtotalpmpc += $pmpc;

                          $other = getDeductionCOS($divs->id,'OTHER',$data['mon'],$data['yr'],$data['period'],'num');
                          $grandtotalother += $other;

                          $earnedperiod = ($salary / $daysinmonth) * ($daysperiod - $nodays);
                          //$earnedperiod = ceil($earnedperiod * 100) / 100;
                          $earnedperiod = round($earnedperiod,2);
                          
                          //$getTax = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'itw');
                          
                          //GET TAX RATE
                          $tax = getCOSSalary($divs->id,'tax_rate','num');
                          if($tax != null)
                          {
                            $itw = $earnedperiod * $tax;
                            //$itw = ceil($itw * 100) / 100;
                            $itw = round($itw,2);
                          }
                          else
                            $itw = 0; 
                          
                          $grandtotalitw += $itw;

                          $totaldeduc = $hmdf + $itw + $pmpc + $other;
                          $grandtotaldeduc += $totaldeduc;

                          $net = $earnedperiod - $totaldeduc;

                          

                          $grandtotalearned += $earnedperiod;
                          //$net = $net - ($hmdf + $itw + $pmpc);

                          $grandtotalnet += $net;

                          $processdatemarshal = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'created_at');
                          $processid = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'id');
                          $processdate = getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'process_date');
                        ?>

                        @if($processdatemarshal == null)
                        <tr>
                            <td><input type="hidden" class="form-control" name="payroll_user[]" value="{{ $divs->id }}">{{ $divs->lname.', '.ucwords(strtolower($divs->fname.' '.substr($divs->mname,0,1).'.')) }}</td>
                            <td>{!! getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period']) !!}</td>
                            <td align="center">{!! getCOSSalary($divs->id,'atm') !!}</td>
                            <td align="center">{!! getCOSSalary($divs->id,'salary') !!}</td>
                            <td align="center">{{ $daysinmonth }}</td>
                            <td align="center">{{ $daysperiod }}</td>
                            <td align="center">{{ getProcessInfoCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'nodays') }}</td>
                            <td align="right">{{ formatCash($dayswithoutpay) }}</td>
                            <td align="right">{{ formatCash($earnedperiod) }}</td>
                            <td align="center">{!! getCOSSalary($divs->id,'charging') !!}</td>
                            <td align="center">{!! getCOSSalary($divs->id,'tax_rate') !!}</td>
                            <td align="center">{{ formatCash($itw) }}</td>
                            <td align="right">{!! getDeductionCOS($divs->id,'HDMF',$data['mon'],$data['yr'],$data['period']) !!}</td>
                            <td align="right">{!! getDeductionCOS($divs->id,'PMPC',$data['mon'],$data['yr'],$data['period']) !!}</td>
                            <td align="right">{!! getDeductionCOS($divs->id,'OTHER',$data['mon'],$data['yr'],$data['period']) !!}</td>
                            <td align="right">{{ formatCash($totaldeduc) }}</td>
                            <td align="right">{{ formatCash($net) }}</td>
                            @if(Auth::user()->usertype == 'Administrator')
                            <th><center></center></th>
                            @endif
                        </tr>
                        @else
                          @if($processdate == null)
                          <tr>
                            <td><input type="hidden" class="form-control" name="payroll_user[]" value="{{ $divs->id }}">{{ $divs->lname.', '.ucwords(strtolower($divs->fname.' '.substr($divs->mname,0,1).'.')) }}</td>
                              <td>{!! getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="center">{!! getCOSSalary($divs->id,'atm') !!}</td>
                              <td align="center">{!! getCOSSalary($divs->id,'salary') !!}</td>
                              <td align="center">{{ $daysinmonth }}</td>
                              <td align="center">{{ $daysperiod }}</td>
                              <td align="center">{{ getProcessInfoCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'nodays') }}</td>
                              <td align="right">{{ formatCash($dayswithoutpay) }}</td>
                              <td align="right">{{ formatCash($earnedperiod) }}</td>
                              <td align="center">{!! getCOSSalary($divs->id,'charging') !!}</td>
                              <td align="center">{!! getCOSSalary($divs->id,'tax_rate') !!}</td>
                              <td align="center">{{ formatCash($itw) }}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'HDMF',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'PMPC',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'OTHER',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{{ formatCash($totaldeduc) }}</td>
                              <td align="right">{{ formatCash($net) }}</td>
                              @if(Auth::user()->usertype == 'Administrator')
                              <th><center><b><a href-"#" class="btn btn-danger btn-sm text-white" style="cursor:pointer" onclick="reverseDTR({{ $processid }})"><small>Reverse DTR</small></a></b></center></th>
                              @endif
                          </tr>
                          @else
                          <tr>
                              <td><input type="hidden" class="form-control" name="payroll_user[]" value="{{ $divs->id }}">{{ $divs->lname.', '.ucwords(strtolower($divs->fname.' '.substr($divs->mname,0,1).'.')) }}</td>
                              <td>{!! getProcessCOS($divs->id,$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="center">{{ getCOSSalary($divs->id,'atm','num') }}</td>
                              <td align="center">{{ formatCash(getCOSSalary($divs->id,'salary','num')) }}</td>
                              <td align="center">{{ $daysinmonth }}</td>
                              <td align="center">{{ $daysperiod }}</td>
                              <td align="center">{{ getProcessInfoCOS($divs->id,$data['mon'],$data['yr'],$data['period'],'nodays') }}</td>
                              <td align="right">{{ formatCash($dayswithoutpay) }}</td>
                              <td align="right">{{ formatCash($earnedperiod) }}</td>
                              <td align="center">{{ getCOSSalary($divs->id,'charging','num') }}</td>
                              <td align="center">{{ getCOSSalary($divs->id,'tax_rate','num') }}</td>
                              <td align="center">{{ formatCash($itw) }}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'HDMF',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'PMPC',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{!! getDeductionCOS($divs->id,'OTHER',$data['mon'],$data['yr'],$data['period']) !!}</td>
                              <td align="right">{{ formatCash($totaldeduc) }}</td>
                              <td align="right">{{ formatCash($net) }}</td>
                              @if(Auth::user()->usertype == 'Administrator')
                              <th><center><b><a href-"#" class="btn btn-danger btn-sm text-white" style="cursor:pointer" onclick="reverseDTR({{ $processid }})"><small>Reverse DTR</small></a></b></center></th>
                              @endif
                          </tr>
                          @endif
                        @endif
                    @endforeach
                      <!-- <tr>
                          <td><b>GRAND TOTAL</b></td>
                          <td></td>
                          <td></td>
                          <td align="right"><b>{{ formatCash($grandtotalsalary) }}</b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align="right"><b>{{ formatCash($grandtotalwp) }}</b></td>
                          <td align="right"><b>{{ formatCash($grandtotalearned) }}</b></td>
                          <td></td>
                          <td></td>
                          <td align="right"><b>{{ formatCash($grandtotalitw) }}</b></td>
                          <td align="right"><b>{{ formatCash($grandtotalhmdf) }}</b></td>
                          <td align="right"><b>{{ formatCash($grandtotalpmpc) }}</b></td>
                          <td align="right"><b>{{ formatCash($grandtotaldeduc) }}</b></td>
                          <td align="right"><b>{{ formatCash($grandtotalnet) }}</b></td>
                      </tr> -->
                  </tbody>
                </table>
                @if(Auth::user()->usertype == 'Administrator')
                <hr>
                <center><h4>SUMMARY</h4></center>
              <table class="table table-bordered" id="tbl" style="width: 100%;font-size:12px">
                <thead>  
                <tr>
                    <th style="width:5%"><center><b>DIVISION</b></center></th>
                    @foreach(getDivisionList() AS $lists)
                      <th style="width:5%"><b><center>{{ $lists->division_acro }}</b></center></th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>

                    <tr>
                      <td>DTR NOT PROCESSED</td>
                      @foreach(getDivisionList() AS $lists)
                        <?php
                          $divcos = count(getICOSDivision(null,$lists->division_id));
                          $divdtrproc = getProcessDivCOS($lists->division_id,$data['mon'],$data['yr'],$data['period']);
                          $not_proc = $divcos - $divdtrproc;
                        ?>
                        <td align="center"><span class="badge badge-warning text-lg">{{ ifNull($not_proc,true) }}</span></td>
                      @endforeach
                    </tr>


                    <tr>
                      <td>DTR PROCESSED</td>
                      @foreach(getDivisionList() AS $lists)
                        <td align="center"><span class="badge badge-primary text-lg">{{ ifNull(getProcessDivCOS($lists->division_id,$data['mon'],$data['yr'],$data['period']),true) }}</span></td>
                      @endforeach
                    </tr>

                    <!-- <tr>
                      <td>FOR PAYROLL PROCESSING</td>
                      @foreach(getDivisionList() AS $lists)
                        <td align="center"><span class="badge badge-danger text-lg">{{  ifNull(getPendingProcessDivCOS($lists->division_id,$data['mon'],$data['yr'],$data['period']),true) }}</span></td>
                      @endforeach
                    </tr> -->
                </tbody>
              </table>
              @endif

              </div>
              <!-- /.card-body -->

              

              </form>
              
              <div class="card-footer">
              </div>
              
            </div>
            <!-- /.card -->
  </div>
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
      <form method="POST" id="frm_deducedit" enctype="multipart/form-data" role="form" action="">  
        {{ csrf_field() }}
        
              <input type="hidden" class="form-control" name="deduc_type" id="deduc_type" value="">
              <input type="text" class="form-control" name="deduc_val" id="deduc_val" value="">

              <input type="text" class="form-control" name="ors_num" id="ors_num" value="">
              <input type="hidden" class="form-control" name="process_id" id="process_id" value="">
              
              <input type="hidden" class="form-control" name="deduc_userid" id="deduc_userid"  value="">
              <input type="hidden" class="form-control" name="mon" id="mon"  value="{{ $data['mon'] }}">
              <input type="hidden" class="form-control" name="yr" id="yr"  value="{{ $data['yr'] }}">
              <input type="hidden" class="form-control" name="period" id="period"  value="{{ $data['period'] }}">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form> 
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalFilter">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_payroll" enctype="multipart/form-data" role="form" action="{{ url('dtr/edit') }}">  
          {{ csrf_field() }}
              <select class="form-control" name="payroll_mon" id="payroll_mon">
                              <option selected value='01'>January</option>
                              <option value='02'>February</option>
                              <option value='03'>March</option>
                              <option value='04'>April</option>
                              <option value='05'>May</option>
                              <option value='06'>June</option>
                              <option value='07'>July</option>
                              <option value='08'>August</option>
                              <option value='09'>September</option>
                              <option value='10'>October</option>
                              <option value='11'>November</option>
                              <option value='12'>December</option>
              </select>
              <br>
              <select class="form-control w-50" name="payroll_year" id="payroll_year">
                      <?php
                        for ($i = date('Y'); $i >= (date('Y') - 5) ; $i--) { 
                            echo "<option value='$i'>".$i."</option>";
                        }
                      ?>
                </select>
              <br>
              <select class="form-control w-50" name="payroll_period" id="payroll_period">
                              <option selected value='1'>1-15</option>
                              <option value='2'>16-31</option>
              </select>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="changeFilter()">Change</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>

<form method="POST" id="frm_dtr_reverse" enctype="multipart/form-data" role="form" action="{{ url('dtr/cos-reverse') }}">
{{ csrf_field() }}
  <input type="hidden" name="processid" id="processid">
  <input type="hidden" name="rev_mon" id="rev_mon" value="{{ $data['mon'] }}">
  <input type="hidden" name="rev_yr" id="rev_yr" value="{{ $data['yr'] }}">
  <input type="hidden" name="rev_period" id="rev_period" value="{{ $data['period'] }}">
</form>


@endsection

@section('JS')
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
    $(document).ready(function(){
      $("#payroll_mon").val("{{ $data['mon'] }}");
      $("#payroll_year").val("{{ $data['yr'] }}");
      $("#payroll_period").val("{{ $data['period'] }}");
    })

    $("#tbl").DataTable({
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

    function editAMT(type,userid,deduc,amt,proc = null)
    {

      switch (type) {
        case 1:
            $("#frm_deducedit").prop('action',"{{ url('icos/update-deduction') }}");
            $("#txt_title").text("EDIT VALUE");
            $("#deduc_val").val(amt);
            $("#ors_num").hide();
            $("#process_id").hide();
            $("#deduc_val").show();
          break;
      
        default:
            $("#frm_deducedit").prop('action',"{{ url('icos/update-ors') }}");
            $("#txt_title").text("EDIT ORS");
            $("#deduc_val").hide();
            $("#ors_num").val(amt);
            $("#process_id").val(proc);
            $("#ors_num").show();
            $("#process_id").show();
          break;
      }

        $("#deduc_type").val(deduc);
        
        $("#deduc_userid").val(userid);
        $("#modalEdit").modal('toggle');
    }


    function changeFilter()
    {
      m = $("#payroll_mon").val();
      y = $("#payroll_year").val();
      p = $("#payroll_period").val();

      window.location.replace("{{ url('icos/payroll') }}/"+m+"/"+y+"/"+p);
    }
    
    function submitFrm()
    {
      $("#frm").submit();
    }

    function reverseDTR(processid)
    {
      $("#processid").val(processid);
      $("#frm_dtr_reverse").submit();
    }
    
</script>
@endsection