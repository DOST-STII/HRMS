@extends('template.master')

@section('CSS')

@endsection

@section('content')
<?php
    $payrollstatus = checkLockPayroll($data['mon'],$data['year'],$data['period']);
?>
<form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('dtr/icos-final-process') }}">  
                      {{ csrf_field() }}
                      <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('dtr/icos-final-process') }}">
                      <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('/') }}">
                      <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
                      <input type="hidden" name="yr" id="yr" value="{{ $data['year'] }}">
                      <input type="hidden" name="period" id="period" value="{{ $data['period'] }}">
<div class="row">
        <div class="col-9">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><h4>{!! date('F',mktime(0, 0, 0, $data['mon'], 10))." ".$data['year'].' Period : '.getPeriodCOS($data['period'],$data['mon'],$data['year']).'</h5></h4>'  !!}</h3>
			         <div class="card-tools">
                    @if(!$payrollstatus) 
                      <input type="submit" class="btn btn-primary" onclick="finalizedDTR()" id="finalBTN" name="finalBTN" value="Finalize DTR" disabled>
                    @endif
                </div>
            </div>
			
            <!-- /.card-header -->
            <div class="card-body">


              @if($payrollstatus)
                  <div class="alert alert-danger"><center><h4><i class="fas fa-exclamation-triangle text-warning"></i> Payroll Locked! Please contact FAD-Personnel <i class="fas fa-exclamation-triangle text-warning""></i></h4></center></div>
              @endif
              <table id="tbl" class="table table-bordered table-striped">
                
                <thead>
                <tr>
                  <th style="width: 2%">
                    
                    @if(!$payrollstatus)  
                      <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                    @endif
                    
                  </th>
                  <th style="width: 30%">Name</th>
                  <th style="width: 15%"><center>Absent (days)</center></th>
                  <th style="width: 15%"><center>Late</center></th>
                  <th style="width: 15%"><center>Undertime</center></th>
                  <th style="width: 15%"><center>Deficit</center></th>
                  <th style="width: 20%"><center>Days Without Pay</center></th>
                </tr>
                </thead>

                
                <tbody>
                    @foreach(getICOSDivision(null,Auth::user()->division) AS $lists)
                    @if(!checkProcessCOS($lists->id,$data['mon'],$data['year'],$data['period']))
                    <?php
                    //$error = "";
                    //$proc = checkPendingProcess($lists->username,$data['mon'],$data['year']);

                    $resultdate = explode("|",checkDTRCOSWP2($lists->id,$data['mon'],$data['year'],$data['period']));
                    $dayswithoutpay = $resultdate[0];

                    if($dayswithoutpay != null)
                    {
                      $totalabsent = $resultdate[1];
                      $totallates = $resultdate[2];
                      $totalundertime = $resultdate[3];
                      $totaldeficit = $resultdate[4];
                      $dates = $resultdate[5];
                    }
                    else
                    {
                      $totalabsent = 0;
                      $totallates = 0;
                      $totalundertime = 0;
                      $totaldeficit = 0;
                      $dates = "";
                    }
                    
                    ?>
                    
                    <tr>
                        <td>
                          <?php
                              if(!$payrollstatus)  
                                echo '<input type="checkbox" name="check_request[]" class="check" value="'.$lists->id.'">';
                              
                              // if($proc['status'] == true)
                              // {
                              //   echo '<input type="checkbox" name="check_request[]" class="check" value="'.$lists->id.'">';
                              // }
                              // else
                              // {
                              //   //$error = "<span class='badge badge-danger'>Pending</span>";
                              // }
                          ?>
                          
                        </td>
                        <td>{{ ucwords(strtolower($lists->lname)).", ".$lists->fname." ".$lists->mname  }} <?php //echo $error ?> </td>
                        <td align="center">{{ ifNull($totalabsent) }}</td>
                        <td align="center">{{ readableTime($totallates) }}</td>
                        <td align="center">{{ readableTime($totalundertime) }}</td>
                        <td align="center">{{ readableTime($totaldeficit) }}</td>
                        <td align="center">
                          <input type="hidden" name="numdays_{{$lists->id}}" value="{{ $dayswithoutpay }}">
                          <input type="hidden" name="absent_{{$lists->id}}" value="{{ $totalabsent }}">
                          <input type="hidden" name="late_{{$lists->id}}" value="{{ $totallates }}">
                          <input type="hidden" name="undertime_{{$lists->id}}" value="{{ $totalundertime }}">
                          <input type="hidden" name="deficit_{{$lists->id}}" value="{{ $totaldeficit }}">
                          <input type="hidden" name="dates_{{$lists->id}}" value="{{ $dates }}">
                          {{ $dayswithoutpay }}
                        </td>
                        
                    </tr>
                      @endif
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      </form>


@endsection

@section('JS')


<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_minimal',
    radioClass: 'iradio_minimal',
    increaseArea: '20%' // optional
  });
  var t = $("#tbl").DataTable();


});


  // Remove the checked state from "All" if any checkbox is unchecked
$('.check').on('ifUnchecked', function (event) {
    if(this.value == 'all')
    {
      $(".check").iCheck('uncheck');
      $("#finalBTN").prop({"disabled":true});
    }
    else
    {
      $("#finalBTN").prop({"disabled":true});

      $("#finalBTN").prop('disabled',false);
      $(this).iCheck('uncheck');
      $("#icheck_all").iCheck('uncheck');

    }
});

// Make "All" checked if all checkboxes are checked
$('.check').on('ifChecked', function (event) {
     $(".request_btn").prop({"disabled":false});
    if(this.value == 'all')
    {
      $(".check").iCheck('check');
      $("#finalBTN").prop('disabled',false);
    }
    else
    {
      $(this).iCheck('check');
      $("#finalBTN").prop('disabled',false);
    }
    // if ($('.check').filter(':checked').length == $('.check').length) {
    //     $('#icheck_all').iCheck('check');
    // }
});


function finalizedDTR()
  {
    $("#overlay").show();
    $("#frm2").sumbit();
  }

function submitFrm(type,mon,yr,userid)
{
  console.log("mon : " + mon + " year : " +yr);
  $('#mon2').val(mon);
  $('#yr2').val(yr);
  $('#userid2').val(userid);

  switch(type)
  {
    case "monitor":
      var act = '{{ url("dtr/monitoring") }}';
      var frm = "#frm_edit";
    break;
    case "pdf":
      var act = '{{ url("dtr/pdf") }}';
      var frm = "#frm_edit";
    break;
    case "view":
      var act = '{{ url("dtr/edit-view") }}';
      var frm = "#frm_edit";
    break;
    case "edit":
      var act = '{{ url("dtr/edit") }}';
      var frm = "#frm_edit";
    break;
  }

  $(frm).attr({'action': act,'target' : '_blank'}).submit();
}

function showNoEntry(data)
{
  // console.log($data);
    var arr = data.split(",");
    var txt = "";

    arr.forEach(function(item) {
        txt += item + "<br/>";
    });

    Swal.fire(
    'Dates with no entry',
    txt,
    'question'
  );
}

</script>
@endsection