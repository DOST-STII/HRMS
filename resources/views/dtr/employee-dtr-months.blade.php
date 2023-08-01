@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">EMPLOYEE DTR</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">
                    #
                  </th>
                  <th>Month/Year</th>
                  <th style="width: 15%"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($data['list'] AS $lists)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td><a href='{{ url("dtr/icos/".date("m",strtotime($lists->fldEmpDTRdate))."/".date("Y",strtotime($lists->fldEmpDTRdate))) }}'>{{ date("F Y",strtotime($lists->fldEmpDTRdate)) }}</a></td>
                        <td align="center"><a href="{{ url('dtr/process/'.date('m',strtotime($lists->fldEmpDTRdate)).'/'.date('Y',strtotime($lists->fldEmpDTRdate))) }}" class="btn btn-primary btn-sm"><i class="fas fa-clock"></i> Process DTR </a></td>
                    </tr>
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


  <form method="POST" id="frm_dtr" enctype="multipart/form-data"> 
        {{ csrf_field() }}
        <input type="hidden" name="mon" id="mon">
        <input type="hidden" name="yr" id="yr">
        <input type="hidden" name="userid" id="userid">
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
});
</script>

<script>
  $(function () {
    var t = $("#tbl").DataTable();

  //   t.on('order.dt search.dt', function () {
  //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  //         cell.innerHTML = i+1;
  //     });
  // }).draw();

  });

  // Remove the checked state from "All" if any checkbox is unchecked
$('.check').on('ifUnchecked', function (event) {
    if(this.value == 'all')
    {
      $(".check").iCheck('uncheck');
      $(".request_btn").prop({"disabled":true});
    }
    else
    {
      $(".request_btn").prop({"disabled":true});
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
    }
    else
    {
      $(this).iCheck('check');
    }
    // if ($('.check').filter(':checked').length == $('.check').length) {
    //     $('#icheck_all').iCheck('check');
    // }
});


function submitFrm(type,mon,yr,userid)
{
  switch(type)
  {
    case "monitor":
      var act = '{{ url("dtr/monitoring") }}';
    break;
    case "pdf":
      var act = '{{ url("dtr/pdf") }}';
    break;
    case "edit":
      var act = '{{ url("dtr/edit") }}';
    break;
  }

  $('#mon').val(mon);
  $('#yr').val(yr);
  $('#userid').val(userid);
  $("#frm_dtr").attr({'action': act,'target' : 'blank'}).submit();
}
</script>
@endsection