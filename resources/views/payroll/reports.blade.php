@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Reports</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <u>
                  <li><a href="reports/gpatm.php" target="_blank">General Payroll Under LBP ATM Credit System</a></li>
   <li><a href="#" onclick="popuponclick('reports/periodFilterTotalDeductions.php', 800, 450);" title="Total Deductions Report">Total Deductions Report</a></li>
   <li><a href="#" onclick="popuponclick('reports/empFilterSalaryLedger.php', 800, 450);" title="Salary Ledger">Salary Ledger</a></li>
   <!--<li><a href="reports/payslip.php" target="_blank">Payslip</a></li>   -->
   <li><a href="reports/remBIR.php" target="_blank">Remittance of Witholding Tax (BIR)</a></li>
   <li><a href="reports/remGPrem.php" target="_blank">Remittance of GSIS Premium</a></li>
   <li><a href="reports/remGPremSum.php" target="_blank">Remittance of GSIS Premium Summary</a></li>
   <li><a href="reports/remPhilHealth.php" target="_blank">Remittance of PhilHealth Premium</a></li>      
   <li><a href="reports/remPGFC.php" target="_blank">Remittance of PAG-IBIG Fund Contribution</a></li>
   <li><a href="reports/remPGMPL.php" target="_blank">Remittance of PAG-IBIG Multi-Purpose Loan</a></li>
   <li><a href="reports/remPGHL.php" target="_blank">Remittance of PAG-IBIG Housing Loan</a></li>
   <li><a href="reports/remNHMFC.php" target="_blank">Remittance of NHMFC Housing Loan</a></li>
   <!-- <li><a href="reports/remCDC.php" target="_blank">Remittance of Regular Loan Installments, Fixed & Savings Deposits (UPLB CDC)</a></li> -->
   <li><a href="reports/remCDC.php" target="_blank">Remittance of Regular Loan Installments, Fixed & Savings Deposits (LPMC)</a></li>
   <li><a href="reports/remPMPC.php" target="_blank">Remittance of Regular Loan Installments, Fixed & Savings Deposits (PMPC)</a></li>
   <li><a href="reports/remPCARRDMOF.php" target="_blank">Remittance of Maintenance of Facilities (MOF)</a></li>
   <li><a href="reports/remPCARRDSO.php" target="_blank">Repayment of PCAARRD Scholarship Obligation</a></li>
   <li><a href="reports/remDOSTSO.php" target="_blank">Repayment of DOST Scholarship Obligation</a></li>
   <li><a href="#" onclick="popuponclick('reports/empFilterLandbankMobileLoanSavers.php', 800, 450);" title="Remittance of Landbank Mobile Loan Savers">Remittance of Landbank Mobile Loan Savers</a></li>
                </u>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

@endsection

@section('JS')

<script>
</script>
@endsection