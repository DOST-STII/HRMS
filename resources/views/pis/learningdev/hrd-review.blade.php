<!DOCTYPE html>
<html>
<head>
    <title>DOST-PCAARD HMRIS | HRD REVIEW</title>
    <link href="{{ asset('application/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('AdminLTE-3.0.2/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
</head>

<body>

<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">HRD PLAN {{ $data['hrd']['hrd_year'] }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">

                  <?php $ctr = 1?>
                  @foreach($data['division'] AS $divisions)
                  @if($ctr == 1)
                  <li class="nav-item">
                    <a class="nav-link active" id="hrd_tab_{{ $divisions->division_acro }}_tab" data-toggle="pill" href="#hrd_tab_{{ $divisions->division_acro }}" role="tab" aria-controls="hrd_tab_{{ $divisions->division_acro }}_control" aria-selected="true">{{ $divisions->division_acro }}</a>
                  </li>
                  @else
                  <li class="nav-item">
                    <a class="nav-link" id="hrd_tab_{{ $divisions->division_acro }}_tab" data-toggle="pill" href="#hrd_tab_{{ $divisions->division_acro }}" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">{{ $divisions->division_acro }}</a>
                  </li>
                  @endif
                  <?php $ctr++?>
                  @endforeach
                  
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="hrd_tab_tabContent">
                @foreach($data['division'] AS $divisions)
                  @if($divisions->division_acro == 'ARMRD')
                  
                    <div class="tab-pane fade show active" id="hrd_tab_{{ $divisions->division_acro }}" role="tabpanel" aria-labelledby="hrd_tab_{{ $divisions->division_acro }}_tab">
                       <table class="table table-bordered" style="font-size: 10px">
                                    <tr>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>NAME</b></td>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>POSITION</b></td>
                                      <td align="center" style="vertical-align: middle; width: 5% !important" rowspan="2"><b>TRAINING PRIORITIZATION</b></td>
                                      <td align="center" style="vertical-align: middle;" colspan="8"><small><b>AREA OF DISCIPLINE</b></small></small></td>
                                      <td align="center" style="vertical-align: middle;" colspan="4"><small><b>TARGET DATE</b></small></td>
                                    </tr>
                                     <tr style="font-size: 10px">
                                        
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>MANAGEMENT/ SUPERVISORY/ LEADERSHIP</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>R&D RELATED TRAININGS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>SKILLS ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION & COMMUNICATION TECHNOLOGY (ICT)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION, EDUCATION & COMMUNICATION (IEC)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>VALUE ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>GENERAL ADMINISTRATION/ GOVERNANCE</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>OTHERS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q1</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q2</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q3</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q4</b></td>

                                     </tr>

                                     <tbody>

                                      <tr><td colspan="15">A. Local</td></tr>
                                     


                                       <tr><td colspan="15">B. Foreign</td></tr>
                                       
                                       
                                     </tbody>
                                  </table>
                    </div>
                  @else
                    <div class="tab-pane fade show" id="hrd_tab_{{ $divisions->division_acro }}" role="tabpanel" aria-labelledby="hrd_tab_{{ $divisions->division_acro }}_tab">
                       <table class="table table-bordered" style="font-size: 10px">
                                    <tr>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>NAME</b></td>
                                      <td align="center" style="vertical-align: middle; width: 10% !important" rowspan="2"><b>POSITION</b></td>
                                      <td align="center" style="vertical-align: middle; width: 5% !important" rowspan="2"><b>TRAINING PRIORITIZATION</b></td>
                                      <td align="center" style="vertical-align: middle;" colspan="8"><small><b>AREA OF DISCIPLINE</b></small></small></td>
                                      <td align="center" style="vertical-align: middle;" colspan="4"><small><b>TARGET DATE</b></small></td>
                                    </tr>
                                     <tr style="font-size: 10px">
                                        
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>MANAGEMENT/ SUPERVISORY/ LEADERSHIP</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>R&D RELATED TRAININGS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>SKILLS ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION & COMMUNICATION TECHNOLOGY (ICT)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>INFORMATION, EDUCATION & COMMUNICATION (IEC)</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>VALUE ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>GENERAL ADMINISTRATION/ GOVERNANCE</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><small><b>OTHERS</b></small></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q1</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q2</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q3</b></td>
                                        <td align="center" style="vertical-align: middle; width: 5px !important"><b>Q4</b></td>

                                     </tr>

                                     <tbody>

                                      <tr><td colspan="15">A. Local</td></tr>
                                     


                                       <tr><td colspan="15">B. Foreign</td></tr>
                                       
                                       
                                     </tbody>
                                  </table>
                    </div>
                  @endif

                  @endforeach

                </div>

                <div class="row">
            <div class="col-12">
                  <form method="POST" id="frm" enctype="multipart/form-data" role="form" action="{{ url('learning-development/submit-hrd-plan-review') }}">  
                    <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('learning-development/call-for-hrd-plan') }}">   -->
                      {{ csrf_field() }}
                      <input type="hidden" name="hrdid" value="{{ $data['hrd']['id'] }}">
                      <br>
                      <strong>REMARKS</strong>
                      <textarea class="form-control" name="hrd_remarks"></textarea>
                      <br>
                      <strong>Review By</strong>
                     <input type="text" class="form-control" name="hrd_remarks_by" style="width: 25%">
                      <br>
                     <span class="float-right"><button class="btn btn-info" onclick="actionHRD()">SUBMIT HRD PLAN REVIEW</button></span>
                  </form>
                </div>
          </div>
              </div>
              <!-- /.card -->
            <!-- /.card-body -->
          </div>
          
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
</div>

</body>

<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>
