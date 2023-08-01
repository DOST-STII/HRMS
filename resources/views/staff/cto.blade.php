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
              <div class="card-header">
                <h3 class="card-title"><b>REQUEST FOR O.T/CTO</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
              <form method="POST" id="frm_request" enctype="multipart/form-data" action="{{ url('dtr/send-ot-request') }}">
              {{ csrf_field() }}
              <input type="hidden" name="ctorequest" id="ctorequest" value="5">
              
              @if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'Administrator')
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

              <div id="cto-choice">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="requestot" name="request_cto" value="request_ot" checked>
                        <label for="requestot">
                          Request O.T
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="requestcto" name="request_cto" value="apply_cto">
                        <label for="requestcto">
                          Leave Application (CTO)
                        </label>
                      </div>
                    </div>
                  </div>
            </div>

            <div id="cto_bal" style="display: none;">
                          <div class="alert alert-warning" id="ctobalance">
                              
                          </div>
                  </div>

              <div id="option-cto">
                      <!-- <strong>CTO</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="cto_yes" name="cto" value="YES">
                          <label for="cto_yes">
                            YES
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="cto_no" name="cto" value="NO" checked>
                          <label for="cto_no">
                            NO
                          </label>
                        </div>
                      </p> -->

                      <div class="input-group" id="option-leave-duration3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration4" name="leave_duration4">
                      </div>       
                      
                      
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
                    </p>
              </div>
              <div id="ot-reason">
                    <strong>Reason</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="ot_purpose" id="ot_purpose">
                      </p>

                      <strong>Expected Output</strong>
                      <br>
                      <p class="text-muted">
                        <input type="text" class="form-control" name="ot_output" id="ot_output">
                      </p>

                      <br>
                          <p>
                            <a href="{{ asset('files/OT_Guidelines.pdf') }}" target="_blank"><i class="fas fa-file-pdf"></i> Guidelines on Overtime Services</a>
                          </p>
                          <p>
                            <a href="{{ asset('files/OT_Guidelines2.pdf') }}" target="_blank"><i class="fas fa-file-pdf"></i> Joint Circular CSC-DBM No. 02 2015</a>
                          </p>
              <!-- /.card-body -->
            </div>
            </div>
              <div class="card-footer">
                <button type="button" class="btn btn-primary float-right" onclick="modalOnSubmit()">Submit</button>
              </div>
              </form>
            </div>
            <!-- /.card -->
  </div>

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
  getLeaveCTO({{ Auth::user()->id }});

$("#userid2").change(function(){
    // alert(this.value);
    getLeaveCTO(this.value);
  });

  function getLeaveCTO(id)
    {
      // alert(id);
      $.getJSON( "{{ url('staff/json/cto') }}/"+id, function( datajson ) {
              }).done(function(datajson) {
                  console.log(datajson);
                  $("#ctobalance").empty().append("Balance : " + datajson.balance + "<br/>Pending : "+datajson.pending+"<br/>Projected : "+datajson.projected);
              });
    }

$('input:radio[name="request_cto"]').change(
    function(){
      $("#ctorequest").val(null);
      $("#div_request_ot,#cto_bal,#ot-reason").hide();
      if(this.value == 'apply_cto')
      {
        $("#cto_bal").show();
            $("#ctorequest").val(5);
            $("#frm_request").prop('action',"{{ url('dtr/send-leave-request') }}");
      }
      else
      {
        alert();
        $("#div_request_ot,#ot-reason").show();
        $("#frm_request").prop('action',"{{ url('dtr/send-ot-request') }}");
      }
            
            
    });


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
@endsection