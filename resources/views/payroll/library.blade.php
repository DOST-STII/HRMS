@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Library</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-library-org-tab" data-toggle="pill" href="#payroll-org-tab" role="tab" aria-controls="payroll-org-tab" aria-selected="true">Organizations</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-library-services-tab" data-toggle="pill" href="#payroll-services-tab" role="tab" aria-controls="payroll-services-tab" aria-selected="false">Services</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-library-deduction-tab" data-toggle="pill" href="#payroll-deduction-tab" role="tab" aria-controls="payroll-deduction-tab" aria-selected="false">Deductions</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link" id="custom-library-computation-tab" data-toggle="pill" href="#payroll-computation-tab" role="tab" aria-controls="payroll-computation-tab" aria-selected="false">Computations</a>
                  </li> -->
                  <li class="nav-item">
                    <a class="nav-link" id="custom-library-compensation-tab" data-toggle="pill" href="#payroll-compensation-tab" role="tab" aria-controls="payroll-deduc-tab" aria-selected="false">Compensations</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link" id="custom-library-salary-tab" data-toggle="pill" href="#payroll-salary-tab" role="tab" aria-controls="payroll-salary-tab" aria-selected="false">  Minimum Net Salary  </a>
                  </li> -->
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="payroll-org-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Organization</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                        @foreach(getPayrollLibrary('organization') AS $values)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $values->ORG_NAME }}</td>
                            <td></td>
                          </tr>
                        <?php $ctr++ ?>
                        @endforeach

                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-services-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Service</th>
                         <th>Acronym</th>
                         <th>Organization</th>
                         <th>Code</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                        @foreach(getPayrollLibrary('service') AS $values)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $values->SERV_DESC }}</td>
                            <td>{{ $values->SERV_ACRO }}</td>
                            <td>{{ $values->ORG_NAME }}</td>
                            <td>{{ $values->SERV_CODE }}</td>
                            <td></td>
                          </tr>
                        <?php $ctr++ ?>
                        @endforeach
                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-deduction-tab" role="tabpanel">
                     <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Deduction Code</th>
                         <th>Deduction Name</th>
                         <th style="width:5%"></th>
                       </thead>
                       <tbody>
                        <?php $ctr = 1; ?>
                        @foreach(getPayrollLibrary('deduction') AS $values)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $values->deductCode }}</td>
                            <td>{{ $values->deductName }}</td>
                            <td></td>
                          </tr>
                        <?php $ctr++ ?>
                        @endforeach
                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-computation-tab" role="tabpanel">
                    <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Module</th>
                         <th>Value</th>
                         <th>Apply</th>
                         <th style="width:5%"></th>
                       </thead>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-compensation-tab" role="tabpanel">
                      <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>Acronym/Code</th>
                         <th>Name</th>
                         <th>Input</th>
                         <th>Amount</th>
                         <th style="width:5%"></th>
                       </thead>
                       <?php $ctr = 1; ?>
                        @foreach(getPayrollLibrary('compensation') AS $values)
                          <tr>
                            <td>{{ $ctr }}</td>
                            <td>{{ $values->compCode }}</td>
                            <td>{{ $values->compName }}</td>
                            <td>{{ $values->compInput }}</td>
                            <td>{{ $values->compAmount }}</td>
                            <td></td>
                          </tr>
                        <?php $ctr++ ?>
                        @endforeach
                       </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade" id="payroll-salary-tab" role="tabpanel">
                      <p align="right"><button class="btn btn-primary btn-sm"> ADD RECORD</button></p>
                     <table class="table" id="tbl1">
                       <thead>
                         <th style="width:5%">#</th>
                         <th>From</th>
                         <th>To</th>
                         <th>Minimum Net Salary</th>
                         <th>Status</th>
                         <th style="width:5%"></th>
                       </thead>
                     </table>
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

@endsection

@section('JS')

<script>
</script>
@endsection