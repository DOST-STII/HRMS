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

@if(Auth::user()->usertype == 'Administrator')
    @include('dtr.admin.report-admin')
@elseif(Auth::user()->usertype == 'Marshal')
    @include('dtr.admin.report-marshal')
@endif

<div class="modal fade" id="modalOption">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modal-title">Report</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" id="frm_report" enctype="multipart/form-data" role="form" target="_blank">  
                {{ csrf_field() }}
              <div class="row">

                  <div class="col-12 option-div" id="option-calendar">
                    <div class="form-group">
                        <label>Date:</label>
                        <input type="date" class="form-control" name="calendarsingle" id="calendarsingle" value="{{ date('Y-m-d') }}">
                    </div>
                  </div>

                  <div class="col-12 option-div" id="option-division">
                    <div class="form-group">
                        <label>Division:</label>
                        @if(Auth::user()->usertype == 'Administrator')
                        <select class="form-control" name="division" id="division"> 
                          @foreach(getDivisionList() AS $divs)
                            <option value="{{ $divs->division_id }}">{{ $divs->division_acro }}</option>
                          @endforeach
                        </select>
                        @elseif(Auth::user()->usertype == 'Marshal')
                          <input type="hidden" name="division" id="division" value="{{ Auth::user()->division }}">
                          <b>{{ getDivision(Auth::user()->division) }}</b>
                        @endif
                    </div>
                  </div>

                  <div class="col-12 option-div" id="option-employee">
                    <div class="form-group">
                        <label>Employee:</label>
                           @if(Auth::user()->usertype == 'Administrator')
                          <select class="form-control" name="employee" id="employee"> 
                            @foreach(getAllUser() AS $users)
                              <option value="{{ $users->id }}">{{ $users->lname.", ".$users->fname." ".$users->mname }}</option>
                            @endforeach
                          </select>
                          @elseif(Auth::user()->usertype == 'Marshal')
                            <select class="form-control" name="employee" id="employee"> 
                            @foreach(getStaffDivision() AS $users)
                              <option value="{{ $users->id }}">{{ $users->lname.", ".$users->fname." ".$users->mname }}</option>
                            @endforeach
                          </select>
                          @endif
                    </div>
                  </div>

                  <div class="col-12" id="option-date">
                    <label>Date:</label>
                    <div class="row">
                      <div class="col-4 datess" id="option-mon1">
                        <select class="form-control" name="mon1" id="mon1">
                          <?php
                            foreach(getMonth() AS $key => $mons)
                            {
                                $slct = "";

                                if($mons == date('F'))
                                    $slct = "selected";

                                echo "<option value='".$key."' $slct>".$mons."</option>";
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-4 datess" id="option-mon2">
                        <select class="form-control" name="mon2" id="mon2">
                        <?php
                            foreach(getMonth() AS $key => $mons)
                            {
                                $slct = "";

                                if($mons == date('F'))
                                    $slct = "selected";

                                echo "<option value='".$key."' $slct>".$mons."</option>";
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-4 datess" id="option-year">
                        <select class="form-control" name="year" id="year">
                        <?php
                            for($i = (date('Y') - 5);$i <= date('Y');$i++)
                            {
                                $slct = "";

                                if($i == date('Y'))
                                    $slct = "selected";

                                echo "<option value='".$i."' $slct>".$i."</option>";
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>


              </div>
            </div>
          </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="frmSubmit()">Generate Report</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

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

<script type="text/javascript">
  function showOption(title,url = null)
  {
    $(".option-div,#option-date,.datess").hide();

    switch(title)
    {
      case 'Daily Monitoring':
        $("#option-division,#option-calendar").show();
      break;

      case 'Processed DTRs':
      case 'DTR Summary':
      case 'Excessive Tardiness':
      case 'Sala Attendance':
      case 'HP Attendance':
      case 'Leave without Pay':
        $("#option-division,#option-date,#option-mon1,#option-year").show();
      break;

      case 'Travel Order':
        $("#option-division,#option-date,#option-mon1,#option-year").show();
      break;

      case 'Leave Records':
        $("#option-employee,#option-date,#option-mon1,#option-mon2,#option-year").show();
      break;

      case 'Certificate of COC':
        $("#option-employee").show();
      break;

      case 'Rendering Overtime':
        $("#option-date,#option-year").show();
      break;

      case 'View Payslip':
        $("#option-employee,#option-date,#option-mon1,#option-mon2,#option-year").show();
      break;

    }

    $("#modal-title").text(title);
    $("#modalOption").modal("toggle");
    $("#frm_report").prop('action',url);
  }


  function frmSubmit()
  {
    $("#frm_report").submit();
  }
</script>
@endsection