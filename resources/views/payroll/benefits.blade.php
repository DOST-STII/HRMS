@extends('template.master')

@section('CSS')
  <!-- MAINE INCLUDE MO TO -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

<form id="frm2" method="POST" action="{{ url('payroll/benefit-create') }}">
    {{ csrf_field() }}
</form>
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Preview/Process Payroll</h3>
              <div class="card-tools">


                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-trree-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-trree-home-tab" data-toggle="pill" href="#custom-tabs-trree-home" role="tab" aria-controls="custom-tabs-trree-home" aria-selected="true">MID YEAR</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-trree-home-tab" data-toggle="pill" href="#custom-tabs-trree-home2" role="tab" aria-controls="custom-tabs-trree-home2" aria-selected="true">YEAR END</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-trree-profile-tab" data-toggle="pill" href="#custom-tabs-trree-profile" role="tab" aria-controls="custom-tabs-trree-profile" aria-selected="false">PBB</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-trree-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-trree-home" role="tabpanel" aria-labelledby="custom-tabs-trree-home-tab">

                  <p align="right">
                  
                  @if(!checkBenefitProcess(1,date('Y')))
                  <button type="button" class="btn bg-primary" onclick="procBenefit(1)">
                    <i class="fas fa-cog"></i> Proccess
                  </button>
                  @else
                  <button type="button" class="btn bg-success" onclick="">
                    <i class="fas fa-file"></i> Download Text File
                  </button>
                  <button type="button" class="btn bg-info" onclick="procPrint()">
                    <i class="fas fa-print"></i> Print
                  </button>
                  @endif
                  
                  </p>

                    <table class="table" id="tbl_mid" style="width: 100%;">
                      <thead>
                        <th>Division</th>
                        <th style="width: 25% !important;">Employee</th>
                        <th>Monthly Basic Salary</th>
                        <th>Months in Service in {{date('Y')}}</th>
                        <th>Amount of Mid Year Bonus</th>
                        <th>Deduction</th>
                        <th>Total</th>
                        <th></th>
                      </thead>
                      <tbody>
                        <?php
                          $user = App\User::whereIn('employment_id',[1,11,13,14,15])->get();
                          foreach ($user as $key => $users) {

                          if(!checkBenefitRemove('MID YEAR',date('Y'),$users->id))
                            {
                              $division = getDivision($users->division);

                              $plantilla = getPlantillaInfo($users->username);
                              
                              if($plantilla)
                              {
                                $basic = $plantilla['plantilla_salary'];
                              }
                              else
                              {
                                $salary = 0;
                              }

                              //DEDUCTION
                              $mid_deduc = App\Payroll\Benefit_deduc::where('userid',$users->id)->where('benefit_year',date('Y',))->where('benefit_type','MID YEAR')->first();
                              if(isset($mid_deduc))
                                $mid_deduc = $mid_deduc['deduc_amt'];
                              else
                                $mid_deduc = 0;
                              
                              $amt = $basic - $mid_deduc;

                              if(!checkBenefitProcess(1,date('Y')))
                              {
                                $benefit_amt = "<td style='cursor:pointer;' onclick='editValue(1,".$users->id.",".$mid_deduc.")' align='center' class='text-primary'><b>".formatCash($mid_deduc)."</b></td>";
                                $benefit_btn = "<td align='center'><button class='btn btn-danger btn-sm' onclick='deleteFrm2(1,".$users->id.")'><i class='fas fa-trash'></i></button></td>";
                              }
                              else
                              {
                                $benefit_amt = "<td align='right'><b>".formatCash($mid_deduc)."</b></td>";
                                $benefit_btn = "<td></td>";
                              }


                              echo "
                              <tr>
                              <td align='center'>".$division."</td>
                              <td>".getStaffInfo($users->id,'fullname')."</td>
                              <td align='right'>".formatCash($basic)."</td>
                              <td align='center'>6</td>
                              <td align='right'>".formatCash($amt)."</td>
                              ".$benefit_amt."
                              <td align='right'>".formatCash($amt)."</td>
                              ".$benefit_btn."
                              </tr>";
                            
                            }

                            
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade show" id="custom-tabs-trree-home2" role="tabpanel" aria-labelledby="custom-tabs-trree-home-tab2">
                    
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-trree-profile" role="tabpanel" aria-labelledby="custom-tabs-trree-profile-tab">

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
        <h5 class="modal-title" id="txt_title">EDIAT DEDUCTION</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" id="frm_deducedit" enctype="multipart/form-data" role="form" action="{{ url('payroll/benefit-deduc') }}">  
        {{ csrf_field() }}
              <input type="hidden" class="form-control" name="deduc_type" id="deduc_type" value="">
              <input type="text" class="form-control" name="deduc_val" id="deduc_val" value="">
              <input type="hidden" class="form-control" name="deduc_userid" id="deduc_userid" value="">
      </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitFrm2()">Save changes</button>
      </div>
      
    </div>
  </div>
</div>

<form method="POST" id="frm_remove" enctype="multipart/form-data" role="form" action="{{ url('payroll/benefit-remove') }}">  
  {{ csrf_field() }}
  <input type="hidden" class="form-control" name="remove_type" id="remove_type" value="">
  <input type="hidden" class="form-control" name="remove_userid" id="remove_userid" value="">
</form>

<form method="POST" id="frm_process" enctype="multipart/form-data" role="form" action="{{ url('payroll/benefit-process') }}">  
  {{ csrf_field() }}
  <input type="hidden" class="form-control" name="proc_benefit_type" id="proc_benefit_type" value="">
</form>

<form method="POST" id="frm_print" enctype="multipart/form-data" role="form" action="{{ url('payroll/benefit-print') }}" target="_blank">  
  {{ csrf_field() }}
  <input type="hidden" class="form-control" name="print_benefit_type" id="print_benefit_type" value="">
</form>
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
    //$('#tbl_mid').DataTable();

    $('#tbl_mid').DataTable({
        "order": [[ 0, "asc" ],[ 1, "asc" ]]
    } );

    function editValue(ty,userid,amt)
    {
      $("#deduc_type").val(ty);
      $("#deduc_userid").val(userid);
      $("#deduc_val").val(amt);
      
      $("#modalEdit").modal("toggle");
    }

    function submitFrm2()
    {
      $("#overlay").show();
      $("#frm_deducedit").submit();
    }

    function deleteFrm2(ty,userid)
    {
      $("#remove_type").val(ty);
      $("#remove_userid").val(userid);

      $("#overlay").show();
      $("#frm_remove").submit();
    }

    function procBenefit(ty)
    {
      $("#proc_benefit_type").val(ty);

      $("#overlay").show();
      $("#frm_process").submit();
    }

    function procPrint()
    {
      $("#print_benefit_type").val(1);
      $("#frm_print").submit();
    }
</script>
@endsection