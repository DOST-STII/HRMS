@extends('template.master')

@section('CSS')
<!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
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
  <div class="col-lg-7 col-md-6 col-md-12">
    <h5 class="alert alert-warning"><center>Click on the column to edit/add</center></h5>
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>DTR</b></h3>
                <div class="card-tools">
                  <!-- <button class="btn btn-primary btn-sm" onclick="addWFH()"><i class="fas fa-plus"></i> Add DTR</button> -->
                </div>

                <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" target="_blank" action="{{ url('dtr/pdf') }}">  
                {{ csrf_field() }}
                  <h3 class="card-title float-right"></h3>
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
                <h4>Employee : <b>{{ $data['fullname'] }}</b></h4>
                <h5><b><center>{{ date('F',mktime(0, 0, 0, $data['mon'], 10)).' '.$data['yr'] }}</b></center></h5>
                <table class="table table-bordered" style="font-size: 12px">
                  <thead style="text-align: center">
                    <th style="text-align: left">Day</th><th style="width: 10%">AM In</th><th style="width: 10%">AM Out</th><th style="width: 10%">PM In</th><th style="width: 10%">PM Out</th><th style="width: 15%">Total Hours</th><th>Remarks</th>
                  </thead>
                  <tbody>
                    <?php
                      

                      $mon = $data['mon'] - 1;
                      $mon2 = date('F',mktime(0, 0, 0, $data['mon'], 10));
                      $yr = $data['yr'];
                      $date = $mon2 ."-" . $data['yr'];
                      $month = ++$mon;

                      $total = Carbon\Carbon::parse($date)->daysInMonth;

                      $prevweek = 1;

                      $week_num = 2;

                      echo "<tr><td colspan='9' align='center'>  <b>WEEK 1 </b> </td></tr>";
                      for($i = 1;$i <= $total;$i++)
                      {
                        $weeknum = weekOfMonth(date($yr.'-'.$mon.'-'.$i)) + 1;
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

                      
                        $dtr = getDTRemp($dtr_date,$data['emp']['id'],$data['emp']['employment_id'],$data['emp']['username']);

                        if($dtr['id'])
                        {
                          $dtrid = $dtr['id'];
                        }
                        else
                        {
                          $dtrid = 0;
                        }

                       echo "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",1,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamIn'])."</div></td><td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",2,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",3,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='cursor:pointer' onclick='showEdit(".$dtrid.",".$data['emp']['id'].",4,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'])."</td><td>".$dtr['dtr_remarks']."</td></tr>";

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

<div class="modal" tabindex="-1" role="dialog" id="modalEdit">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit DTR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="frm_dtr" enctype="multipart/form-data" role="form" action="{{ url('dtr/update') }}">  
          {{ csrf_field() }}

          <input type="hidden" name="userid" id="userid" value="{{ $data['emp']['id'] }}">
          <input type="hidden" name="yr" id="yr" value="{{ $data['yr'] }}">
          <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
          <input type="hidden" name="day" id="day" value="">
          <input type="hidden" name="dtr_colid" id="dtr_colid">
          <input type="hidden" name="dtr_col" id="dtr_col">
          <input type="hidden" name="dtr_orig" id="dtr_orig">
          
          <span id="desc"><b>Time</b></span>
          <input type="time" class="form-control" name="dtr_val" id="dtr_val">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
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
<!-- daterangepicker -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- ChartJS -->

<script src="{{ asset('datepicker/js/bootstrap-datepicker.min.js') }}"></script>


<script>


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

  function addWFH()
  {
    $("#addWFH").modal('toggle');
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

  function showEdit(colid,userid,col,y,m,d)
  {
    $("#dtr_orig,#dtr_val").empty();
    $("#day").val(d);
    $("#dtr_col").val(col);
    $("#dtr_colid").val(colid);

    $.getJSON( "{{ url('json/dtr') }}/"+userid+"/"+col+"/"+y+"/"+m+"/"+d, function( datajson ) {
              }).done(function(datajson) {
                $("#dtr_orig,#dtr_val").val(datajson);
            }).fail(function() {
            });

    $("#modalEdit").modal("toggle");
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