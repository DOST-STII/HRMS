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
  <div class="col-lg-12 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>TRAVEL ORDER REQUEST FORM</b></h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
              <form method="POST" id="frm_request" enctype="multipart/form-data" action="{{ url('dtr/send-to-request') }}">
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

              <div id="option-to">
              
                      <strong>Purpose</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="purpose" id="purpose">
                      </p>

                      <strong>Title of the Activity</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="acttitle" id="acttitle">
                      </p>  

                      <strong>Date/s of Travel</strong>
                      <br>
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                          <div class="input-group" id="option-leave-duration3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            <input type="text" class="form-control float-right" id="leave_duration4" name="leave_duration4">
                          </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
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
                      </div>
                      </div>
                      </p>

                    <strong>Venue / Location</strong>
                    <br>
                    <p class="text-muted">
                      <input type="text" class="form-control" name="place" id="place">
                    </p>

                    <strong>Fund Source</strong>
                    <br>
                    <p class="text-muted">
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="checkbox" id="fund_gaa" name="fund_src" value="GAA">
                        <label for="fund_gaa">
                          GAA
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="checkbox" id="fund_pf" name="fund_src" value="Project Fund">
                        <label for="fund_pf">
                          Project Fund
                          <input type="text" class="form-control" name="pf_txt" id="pf_txt" style="display: none;">
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="checkbox" id="fund_others" name="fund_src" value="Others">
                        <label for="fund_others">
                          Others    
                          <input type="text" class="form-control" name="others_txt" id="others_txt" style="display: none;">
                        </label>
                      
                      </div>                        
                    </p>

                    <strong>Transporation Requested</strong>
                    <br>
                    <p class="text-muted">
                      <div class="icheck-primary d-inline" style="margin-left: 30px">
                        <input type="radio" id="vehicle_official1" name="vehicle" value="Official" checked>
                        <label for="vehicle_official1">
                          Official Vehicle
                        </label>
                      </div>
                      <br>
                      <div class="icheck-primary d-inline" style="margin-left: 30px">
                        <input type="radio" id="vehicle_personal2" name="vehicle" value="Public Conveyance">
                        <label for="vehicle_personal2">
                          Public Conveyance
                        </label>
                      </div>
                      <br>
                      <div class="icheck-primary d-inline" style="margin-left: 30px">
                        <input type="radio" id="vehicle_personal3" name="vehicle" value="Taxi / TNVS">
                        <label for="vehicle_personal3">
                          Taxi / TNVS
                        </label>
                      </div>
                      <br>
                      <div class="icheck-primary d-inline" style="margin-left: 30px">
                        <input type="radio" id="vehicle_personal4" name="vehicle" value="Airplane">
                        <label for="vehicle_personal4">
                          Airplane
                        </label>
                      </div>
                      <br>
                      <div class="icheck-primary d-inline" style="margin-left: 30px">
                        <input type="radio" id="vehicle_personal5" name="vehicle" value="Bus">
                        <label for="vehicle_personal5">
                          Bus
                        </label>
                      </div>

                                              
                    </p>

                    <strong>Travel Expenses to be incurred:</strong>
                    
                    <p class="text-muted">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- Your per diem options -->
                          <div class="icheck-primary d-inline" style="margin-right: 10px;">
                            <input type="radio" id="travel_diem" name="perdiem" value="Per Diem">
                            <label for="travel_diem">
                              Per Diem
                            </label>
                          </div>
                          <br><br>

                          <!-- Per diem type options -->
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_pd_hotel" name="perdiem_type" value="Hotel / Lodging" >
                            <label for="travel_pd_hotel" style="display: none;">
                              Hotel / Lodging
                            </label>
                          </div>
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_pd_meals" name="perdiem_type" value="Meals">
                            <label for="travel_pd_meals" style="display: none;">
                              Meals
                            </label>
                          </div>
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_pd_ie" name="perdiem_type" value="Incidental Expenses">
                            <label for="travel_pd_ie" style="display: none;">
                              Incidental Expenses
                            </label>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <!-- Your per diem options -->
                          <div class="icheck-primary d-inline" style="margin-right: 10px;">
                            <input type="radio" id="travel_actual" name="perdiem" value="Actual">
                            <label for="travel_actual">
                              Actual
                            </label>
                          </div>
                          <br><br>

                          <!-- Per diem type options -->
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_actual_hotel" name="perdiem_type" value="Hotel / Lodging (50% of DTE)" >
                            <label for="travel_actual_hotel" style="display: none;">
                              Hotel / Lodging (50% of DTE)
                            </label>
                          </div>
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_actual_meals" name="perdiem_type" value="Meals (30%)">
                            <label for="travel_actual_meals" style="display: none;">
                              Meals (30%)
                            </label>
                          </div>
                          <div class="icheck-primary d-inline" style="margin-left: 30px;">
                            <input type="radio" id="travel_actual_ie" name="perdiem_type" value="Incidental Expenses (20%)">
                            <label for="travel_actual_ie" style="display: none;">
                              Incidental Expenses (20%)
                            </label>
                          </div>
                        </div>

                       

                        
                    </div>
                    </p>

                      
                    </div>

              </div>

            



              <!-- /.card-body -->
              <div class="card-footer">
                <button type="button" class="btn btn-primary float-right" onclick="modalOnSubmit()">Submit</button>
              </div>
              
            </div>
            <!-- /.card -->
  </div>

</div>

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

  $('#leave_duration4').daterangepicker();

function modalOnSubmit()
  {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes!'
    }).then((result) => {
      if (result.value) {
        $("#overlay").show();
        $("#frm_request").submit();
      }
    })
  }

</script>

<script>
  $(document).ready(function() {
    $('#fund_others').on('change', function() {
      var othersCheckbox = $(this);
      var othersInput = $('#others_txt');
      
      if (othersCheckbox.is(':checked')) {
        othersInput.show();
      } else {
        othersInput.hide();
      }
    });

    $('#fund_pf').on('change', function() {
      var othersCheckbox = $(this);
      var othersInput = $('#pf_txt');
      
      if (othersCheckbox.is(':checked')) {
        othersInput.show();
      } else {
        othersInput.hide();
      }
    });

    $('#travel_diem').on('change', function() {
      var perDiemRadio = $(this);
      var perDiemTypeLabels = $('label[for^="travel_pd_"]');
      
      if (perDiemRadio.is(':checked')) {
        perDiemTypeLabels.show();
      } else {
        perDiemTypeLabels.hide();
      }
    });

    $('#travel_actual').on('change', function() {
      var perDiemRadio = $(this);
      var perDiemTypeLabels = $('label[for^="travel_actual_"]');
      
      if (perDiemRadio.is(':checked')) {
        perDiemTypeLabels.show();
      } else {
        perDiemTypeLabels.hide();
      }
    });
   
  });
</script>

@endsection