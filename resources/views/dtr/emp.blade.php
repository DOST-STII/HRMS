@extends('template.master')

@section('CSS')

@endsection

@section('content')
<form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('dtr/final-process') }}">  
                      {{ csrf_field() }}
                      <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('dtr/final-process') }}">
                      <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('/') }}">
                      <input type="hidden" name="mon" id="mon" value="{{ $data['mon'] }}">
                      <input type="hidden" name="yr" id="yr" value="{{ $data['year'] }}">
                      <input type="hidden" name="test" id="test" value="{{ $data['year'] }}">
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ date('F',mktime(0, 0, 0, $data['mon'], 10)).' '.$data['year'] }}</h3>
			         <div class="card-tools">

                              
                      <input type="submit" class="btn btn-primary" onclick="finalizedDTR()" id="finalBTN" name="finalBTN" value="Finalize DTR" disabled>  
                </div>
            </div>
			
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                
                <thead>
                <tr>
                  <th style="width: 2%">
                    <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                  </th>
                  <th>Name</th>
                  <th style="width: 15%">No entry</th>
                  <th style="width: 20%">Pending Request(Leave,T.O and O.T)</th>
                  <th style="width: 15%"><center>Action</center></th>
                </tr>
                </thead>

                
                <tbody>
                    @foreach($data['list'] AS $lists)
                    @if(!checkIfProcessed($lists->id,$data['mon'],$data['year']))
                    <?php
                    $error = "";
                    $proc = checkPendingProcess($lists->username,$data['mon'],$data['year']);
                    ?>
                    
                    <tr>
                        <td>
                          <?php

                              if($proc['status'] == true)
                              {
                                echo '<input type="checkbox" name="check_request[]" class="check" value="'.$lists->id.'">';
                              }
                              else
                              {
                                $error = "<span class='badge badge-danger'>pending ".$proc['prev_mon']." ".$proc['prev_year']."</span>";
                              }
                          ?>
                          
                        </td>
                        <td>{{ $lists->lname.", ".$lists->fname." ".$lists->mname  }} <?php echo $error ?> </td>
                        <td align="center"><?php echo countNoEntry($lists->id,$data['mon'],$data['year'],$lists->dtr_exe) ?></td>
                        <td>
                          <?php 


                            $req = getPendingRequest("leave",$lists->id,$data['mon'],$data['year']);

                            foreach ($req as $key => $value) {
                              echo $value."<br/>";
                            }

                            $req = getPendingRequest("t.o",$lists->id,$data['mon'],$data['year']);

                            foreach ($req as $key => $value) {
                              echo $value."<br/>";
                            }

                            $req = getPendingRequest("o.t",$lists->id,$data['mon'],$data['year']);

                            foreach ($req as $key => $value) {
                              echo $value."<br/>";
                            }

                          ?>
                        </td>
                        <td align="center">
                          <a href="#" onclick="submitFrm('view',{{ $data['mon'] }},{{ $data['year'] }},{{ $lists->id }})"><small><i class="fas fa-edit text-primary" style="cursor: pointer"></i>Edit &nbsp&nbsp</small></a>
                          <a href="#" onclick="submitFrm('pdf',{{ $data['mon'] }},{{ $data['year'] }},{{ $lists->id }})"><small><i class="fas fa-print text-success" style="cursor: pointer"></i>Print &nbsp&nbsp</small></a>
                          <!-- <a href="{{ url('dtr/monitoring/'.date('m',strtotime($lists->fldEmpDTRdate)).'/'.date('Y',strtotime($lists->fldEmpDTRdate)).'/'.$lists->user_id ) }}" target="_blank"><i class="fas fa-eye text-warning" style="cursor: pointer"></i>&nbsp&nbsp</a> -->
                          <!-- <a href="#" onclick="submitFrm('monitor',{{ date('m',strtotime($lists->fldEmpDTRdate)) }},{{ date('Y',strtotime($lists->fldEmpDTRdate)) }},{{ $lists->user_id }})"><i class="fas fa-eye text-warning" style="cursor: pointer"></i>&nbsp&nbsp</a> -->
                           <a href="#" onclick="submitFrm('monitor',{{ $data['mon'] }},{{ $data['year'] }},{{ $lists->id }})"><small><i class="fas fa-eye text-warning" style="cursor: pointer"></i>View &nbsp&nbsp</small></a>
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

<form method="POST" id="frm_edit" enctype="multipart/form-data" role="form" action="{{ url('dtr/edit') }}">
   {{ csrf_field() }}
   <input type="hidden" name="userid2" id="userid2" value="">
   <input type="hidden" name="action" id="action" value="view">
   <input type="hidden" name="mon2" id="mon2" value="">
   <input type="hidden" name="yr2" id="yr2" value="">
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