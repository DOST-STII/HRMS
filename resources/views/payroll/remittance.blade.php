@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-lg-4 col-md-5 col-sm-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Remittance</h3>
            </div>
            <!-- /.card-header -->
            <form method="POST" id="frm_remittance" enctype="multipart/form-data" role="form" action="{{ url('payroll/remittance') }}" target="_blank">  
            {{ csrf_field() }}
            <input type="hidden" name="report_type" id="report_type" value="1">
            <div class="card-body">
              <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Month</label>
                        <select class="form-control" name="payroll_mon" id="payroll_mon">
                          <option selected value='1'>January</option>
                          <option value='2'>February</option>
                          <option value='3'>March</option>
                          <option value='4'>April</option>
                          <option value='5'>May</option>
                          <option value='6'>June</option>
                          <option value='7'>July</option>
                          <option value='8'>August</option>
                          <option value='9'>September</option>
                          <option value='10'>October</option>
                          <option value='11'>November</option>
                          <option value='12'>December</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Year</label>
                        <select class="form-control" name="payroll_year" id="payroll_year">
                          <?php
                            for ($i = date('Y'); $i >= (date('Y') - 5) ; $i--) 
                            { 
                                echo "<option value='$i'>".$i."</option>";
                            }
                          ?>
                          </select>
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Report</label>
                        <select class="form-control" name="remit_report" id="remit_report">
                          <option selected value='1'>GSIS</option>
                          <option value='2'>PAG-IBIG</option>
                          <option value='3'>PMPC</option>
                          <option value='4'>LMPC</option>
                          <option value='5'>LANDBANK</option>
                          <option value='7'>TAX/BIR</option>
                          <option value='6'>OTHERS</option>
                        </select>
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Deducted to</label>
                        <select class="form-control" name="remit_deduc" id="remit_deduc">
                          <option selected value='1'>Salary</option>
                          <option value='2'>Magna Carta</option>
                        </select>
                      </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary float-right"><i class="fas fa-print"></i> Print</button>
            </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

@endsection

@section('JS')
<script>
    $("#payroll_mon").val({{ date('n')}});

    $("#remit_deduc").change(function(){

      $("#remit_report").empty();

        if(this.value == 1)
        {
          $("#remit_report").append("<option selected value='1'>GSIS</option><option value='2'>PAG-IBIG</option><option value='3'>PMPC</option><option value='4'>LMPC</option><option value='5'>LANDBANK</option><option value='7'>TAX/BIR</option><option value='6'>OTHERS</option>");
          $("#report_type").val(1);
        }
        else
        {
          $("#remit_report").append("<option selected value='1'>GSIS</option><option value='3'>PMPC</option><option value='4'>LMPC</option><option value='5'>LANDBANK</option><option value='7'>OTHERS</option>");
          $("#report_type").val(2);
        }
    }); 
</script>
@endsection