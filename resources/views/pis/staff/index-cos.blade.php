@extends('template.master-cos')

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

  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/bootstrap-datepicker3.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/bootstrap-theme.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/css/font-awesome.min.css') }}">
@endsection

@section('content')

<div class="row">
  <div class="col-lg-6 col-md-12 col-sm-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>DTR</b></h3>

                <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" target="_blank" action="{{ url('dtr/pdf') }}">  
                {{ csrf_field() }}
                  <h3 class="card-title float-right"><a class="btn btn-primary btn-sm" href="#" target="_blank" onclick="showDTR()"><i class="fas fa-print"></i></a></h3>
                  <h3 class="card-title float-right" style="padding-right: 10px">
                    <input type="hidden" id="yr" name="yr" value="{{ date('Y') }}">
                    <input type="hidden" id="mon" name="mon" value="{{ date('m') }}">
                    <input type="hidden" id="userid" name="userid" value="{{ Auth::user()->id }}">
                  </h3>
                  <div class="card-tools">
                    
                  </div>
                </form>
              </div>
              <div class="card-body">
                <h5><b><center>{{ date('F Y') }}</b></center></h5>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th><th>OT In</th><th>OT Out</th><th>Total Hours</th><th>Remarks</th>
                  </thead>
                  <tbody>
                    <?php
                      $total = Carbon\Carbon::now()->daysInMonth;
                      $prevweek = 1;

                      $week_num = 2;

                      $mon = date('m');
                      $yr = date('Y');

                      echo "<tr><td colspan='9' align='center'>  <b>WEEK 1 </b> </td></tr>";
                      for($i = 1;$i <= $total;$i++)
                      {
                        $weeknum = weekOfMonth(date('Y-m-'.$i)) + 1;
                        if($weeknum == $prevweek)
                        {
                          
                        }
                        else
                        {
                          $prevweek = $weeknum;
                          echo "<tr><td colspan='9' align='center'> <b>WEEK $week_num </b> </td></tr>";
                          $week_num++;
                        }

                       $dtr_date = $yr.'-'.$mon.'-'.$i;

                        $dayDesc = weekDesc(date($yr.'-'.$mon.'-'.$i));

                      
                        $dtr = getDTRemp($dtr_date,Auth::user()->id,Auth::user()->employment_id);

                       echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRotIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRotOut'])."</td><td align='center'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'])."</td><td></td></tr>";

                        // echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center'></td><td align='center'></td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
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

<script src="{{ asset('datepicker/js/bootstrap-datepicker.min.js') }}"></script>


<script>
  $(document).ready(function(){
    $("#dtr-mon,#payslip-mon").val("{{ date('F') }}");
    $("#dtr-year,#payslip-year").val({{ date('Y') }});

    //Date range picker
    // $('#leave_duration').daterangepicker();

    $('#datepicker').datepicker({
        startDate: "2000-01-01",
        multidate: true,
        format: "yyyy-mm-dd",
        daysOfWeekHighlighted: "0,6",
        // daysOfWeekDisabled: [0,6],
        datesDisabled: [
                        <?php
                          foreach (getDisableDates() as $dates) {
                            echo "'".$dates['date_desc']."',";
                          }
                        ?>
                       ],
        language: 'en'
    }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
        // $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
    });

  });


  function showPendingLeave(id)
  {
    $("#modal-show-leave").modal('toggle');

    $.getJSON( "{{ url('dtr/get-pending-leave') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                $("#tbl-body").empty();
                jQuery.each(datajson,function(i,obj){
                         $("#tbl-body").append("<tr><td>"+obj.leave_date+"</td><td>"+obj.leave_deduction+"</td><td><a href='#' onclick='cancelPendingLeave("+obj.id+")'><i class='fas fa-times-circle text-danger'></i></a></td></tr>");
                    });
            }).fail(function() {
            });
  }

  function cancelPendingLeave(id)
  {
    // {{ url('dtr/cancel-leave') }}/"+obj.id+"
    
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
        window.location.href = "{{ url("dtr/cancel-leave") }}/" + id;
      }
    })
  }



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

  //SUBMIT REQUEST

  //GET DATE
//   $('#leave_duration').on('apply.daterangepicker', function(ev, picker) {
//     var start = new Date(picker.startDate.format('YYYY-MM-DD')),
//     end   = new Date(picker.endDate.format('YYYY-MM-DD')),
//     diff  = new Date(end - start),
//     days  = diff/1000/60/60/24;
//     console.log(days);
//     if(days > 0)
//     {
//       console.log("pass");
//       $("#leave_times").hide();
//     }
//     else
//     {
//       $("#leave_times").show();
//     }
// });
$("#Dates").change(function(){
  var dates = this.value;
  var dates = dates.split(",");
    if(dates.length > 1)
    {
      $("#leave_times").hide();
    }
    else
    {
      $("#leave_times").show();
    }
});



  function showRequest(title)
  {
    $("#modal-request-for-title").text(title);
    $("#modal-request-for").modal("toggle");

    $(".div-request").hide();
    switch(title)
    {
      case "Apply for Leave":
        $("#div-request-leave").show();
        $("#frm_request").attr({"action" : "{{ url('request/leave') }}"});
      break;
    }
  }



  function showDTR()
  {
    $("#frm_dtr").submit();
    // var win = window.open('{{ url("pdf/my-dtr") }}/' + $("#dtr_mon").val() + '-' + $("#dtr_year").val(), '_blank');
  }

  function showPayslip()
  {
    var win = window.open('{{ url("pdf/my-payslip") }}/' + $("#dtr-mon").val() + '-' + $("#dtr-year").val(), '_blank');
  }

</script>
@endsection