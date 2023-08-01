@extends('template.master')

@section('CSS')
<!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.css') }}">
@endsection

@section('content')

<div class="row">
  <div class="col-lg-6 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
            <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('pdf/my-payslip') }}" target="_blank">  
              {{ csrf_field() }}
              <div class="card-header">
                <h3 class="card-title"><b>PAYROLL</b></h3>
                <h3 class="card-title float-right" style="margin-left: 2%"><button class="btn btn-primary btn-sm" ><i class="fas fa-print"></i></button></h3>

                <div class="float-right">
                        <select class="form-control-sm" id="payslip-year2" name="payslip_year2" >
                        <?php
                          $total = date('Y') - 5;
                          for ($i = date('Y'); $i >= $total ; $i--) { 
                            # code...
                            echo "<option value='$i'>$i</option>";
                          }
                          
                        ?>
                      </select>
                    </div>

                    <div class="float-right" style="margin-right: 1%">
                        <select class="form-control-sm" id="payslip-mon" name="payslip_mon2">
                      <?php
                        $month = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                        foreach ($month as $key => $months) {
                          # code...
                          $style = "";
                          if($months == date('F'))
                          {
                            $style = "font-weight:bold";
                          }
                          echo "<option value='".++$key."' style='$style'>$months</option>";
                        }
                      ?>
                    </select>
                    </div>

                  <div class="float-right" style="margin-right: 1%">
                        <select class="form-control-sm" id="payslip-year" name="payslip_year" >
                        <?php
                          $total = date('Y') - 5;
                          for ($i = date('Y'); $i >= $total ; $i--) { 
                            # code...
                            echo "<option value='$i'>$i</option>";
                          }
                          
                        ?>
                      </select>
                    </div>
                    

                    <div class="float-right" style="margin-right: 1%">
                        <select class="form-control-sm" id="payslip-mon" name="payslip_mon">
                      <?php
                        $month = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                        foreach ($month as $key => $months) {
                          # code...
                          $style = "";
                          if($months == date('F'))
                          {
                            $style = "font-weight:bold";
                          }
                          echo "<option value='".++$key."' style='$style'>$months</option>";
                        }
                      ?>
                    </select>
                  </div>

                    
                  </form>
              </div>
              <div class="card-body">
                <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('pdf/my-payslip') }}" target="_blank">
                <input type="hidden" id="payslip-year" name="payslip_year" value="{{ date('Y') }}">
                <input type="hidden" id="payslip-mon" name="payslip_mon" value="{{ date('m') }}">
                </form> 
                  <table width="100%">
                  <tr>
                    <td style="width:33%"><b>GROSS PAY</b></td>
                    <td style="width:33%"><b>DEDUCTIONS</b></td>
                    <td style="width:33%"><b>OTHER DEDUCTIONS</b></td>
                    <td></td>
                  </tr>
                  <tbody>
                    <tr>
                      <?php

                        //CHECK IF EXISTING NA YUNG PAYROLL
                        $sal = App\Payroll\PrevInfotbl::where('fldEmpCode',Auth::user()->username)->where('fldMonth',date('m'))->where('fldYear',date('Y'))->first();

                        if($sal)
                        {
                          $basic_salary = $sal['M_BASIC'];

                          $deductions = getDeductionsPrev(Auth::user()->username,date('m'),date('Y'));
                          $compensation = getCompensationPrev(Auth::user()->username,date('m'),date('Y'));

                        }
                        else
                        {
                          $plantilla = getPlantillaInfo(Auth::user()->username);

                          $basic_salary = $plantilla['plantilla_salary'];

                          $deductions = getDeductions(Auth::user()->username);

                          $compensation = getCompensation(Auth::user()->username);
                        }
                        

                        $total_manda_deduc = 0;
                        $total_loan_deduc = 0;

                        //RATA
                        $rata = 0;
                        foreach(getCompensation_rata(Auth::user()->username,true) AS $values)
                        {
                          $rata += $values->compAmount;
                        }

                        //TOTAL COMPESATION
                        $total_comp = 0;
                        foreach ($compensation as $key => $value) {
                          if($value->compID == 1)
                            $total_comp += $value->compAmount;
                        }


                        $total_deduc = 0;
                        // foreach ($deductions as $key => $value) {
                        //   if($value->deductID != 2)
                        //     {
                        //       $total_deduc += $value->deductAmount;
                        //       $total_manda_deduc += $value->deductAmount;
                        //     }
                        // }

                        foreach ($deductions as $key => $value)
                            {
                                if($value->deductID != 2 && $value->deductID != 3)
                                {
                                    if($value->deductCode != "")
                                    {
                                        if($value->deductAmount > 0)
                                        {
                                          $total_deduc += $value->deductAmount;
                                          $total_manda_deduc += $value->deductAmount;
                                        }
                                    }
                                }

                            }

                        $loans = getPersonalLoans(Auth::user()->username);
                        foreach ($loans as $key => $value) {
                                $total_deduc += $value->DED_AMOUNT;
                                $total_loan_deduc += $value->DED_AMOUNT;
                            }

                        $deduc_td = "";
                        $sic = $basic_salary * 0.09;
                        $total_deduc += $sic;
                        $total_manda_deduc += $sic;
                        $deduc_td .= 'SIC - '.formatCash($sic).'<br/>';

                        //PHILHEALTH
                        $philhealth = computePhil($basic_salary);
                        if($philhealth >= 1600)
                          {
                            $philhealth = 1600;
                          }
                        $total_deduc += $philhealth;
                        $total_manda_deduc += $philhealth;
                        $deduc_td .= 'PhilHealth - '.formatCash($philhealth).'<br/>';

                      ?>
                      <td valign="top">
                        SALARY - {{ formatCash($basic_salary) }}<br>
                        @foreach($compensation AS $lists)
                          <?php echo $lists->compCode.' - '.formatCash($lists->compAmount).'<br/>' ?>
                        @endforeach
                      </td>
                      <td valign="top">
                          <?php echo $deduc_td; ?>
                        @foreach($deductions AS $lists)
                          <?php 
                            if($lists->deductAmount > 0)
                              if($lists->deductID != 2)
                              {
                                echo $lists->deductName.' - '.formatCash($lists->deductAmount).'<br/>';
                              }
                              
                            ?>
                        @endforeach
                      </td>
                      <td valign="top">
                        @foreach($loans AS $lists)
                          <?php 
                            if($lists->DED_AMOUNT > 0)
                              echo $lists->ORG_ACRO.' - '.formatCash($lists->DED_AMOUNT).'<br/>';
                          ?>
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <td><b>TOTAL</b> - {{ formatCash($basic_salary + $total_comp) }}</td>
                      <td>{{ formatCash($total_manda_deduc) }}</td>
                      <td>{{ formatCash($total_loan_deduc) }}</td>
                    </tr>
                    <tr>
                      <td><b>NET SALARY</b> - {{ formatCash(($basic_salary + $total_comp) - $total_deduc) }}</td>
                      <td><b>PER WEEK</b></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td colspan="2">
                        <div class="row">
                          <?php
                            $net = ($basic_salary + $total_comp) - $total_deduc;
                            for ($i=1; $i <= 4 ; $i++) {
                                $salary = getWeekSalary(Auth::user()->username,$net,$i);
                                echo "<div class='col-3'>".$salary."</div>";
                              }
                          ?>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>BENEFITS</b></h3>
                <div class="card-tools">
                    
                </div>
              </div>
              <div class="card-body">
                <table class="table tbl" id="tbl_benefits">
                      <thead>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Year</th>
                      </thead>
                      @foreach($data['bonus'] AS $bonuses)
                        <tr>
                          <td>{{ $bonuses->description }}</td>
                          <td>{{ formatCash($bonuses->bonus_amt) }}</td>
                          <td>{{ $bonuses->bonus_year }}</td>
                        </tr>
                      @endforeach
                    </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
  </div>

  <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>MAGNA CARTA</b></h3>
                <div class="card-tools">
                
                </div>
              </div>
              <div class="card-body">
              <table class="table tbl" id="tbl_mc">
                      <thead>
                        <th>Month/Year</th>
                        <th>Amount</th>
                        <th align="center">Print</th>
                      </thead>
                      @foreach($data['mc'] AS $mcs)
                        <tr>
                          <td><span style="display: none">{{ $mcs->id }}</span> {{ date("F", mktime(0, 0, 0, $mcs->sala_mon, 1)).' '.$mcs->sala_year }}</td>
                          <td>{{ GetMCreport(Auth::user()->id,$mcs->sala_mon,$mcs->sala_year) }}</td>
                          <td align="center"><span style="cursor:pointer" onclick="printMC('{{ $mcs->process_code }}',{{ $mcs->sala_mon }},{{ $mcs->sala_year }})"><i class="fas fa-print"></i></span></td>
                        </tr>
                      @endforeach
                    </table>
              </div>
            </div>
  </div>

  <div class="col-lg-6 col-md-12 col-sm-12">
    
  </div>
</div>


<form method="POST" id="frm_mc" action="{{ url('payroll/mc-report') }}" target="_blank">
  {{ csrf_field() }}
  <input type="hidden" name="processcode" id="processcode">
  <input type="hidden" name="mcrequest" id="mcrequest" value="individual">
  <input type="hidden" name="mon" id="mon">
  <input type="hidden" name="yr" id="yr">
</form>

@endsection

@section('JS')

<!-- Sparkline -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- ChartJS -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>


<script>
  $("#payslip-mon,#payslip-mon2").val({{ date('n') }});
  $("#tbl_benefits").DataTable();

  $("#tbl_mc").DataTable( {
        "order": [[ 0, "desc" ]]
    } );

  function printMC(processcode,mon,yr)
  {
    $("#processcode").val(processcode);
    $('#mon').val(mon);
    $('#yr').val(yr);
    $("#frm_mc").submit();
  }
</script>
@endsection