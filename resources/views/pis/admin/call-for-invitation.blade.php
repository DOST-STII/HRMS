@extends('template.master')

@section('CSS')

@endsection

@section('content')
<form method="POST" name="frm_invitation" id="frm_invitation" enctype="multipart/form-data"> 
  {{ csrf_field() }}
  <input type="hidden" name="plantilla_id" value="{{ $data['detail']['id'] }}">
<div class="row">
                <div class="col-md-6 callout callout-success">
                  <b>ITEM NUMBER : </b>{{ $data['detail']['plantilla_item_number'] }}
                  <br>
                  <br>
                  <b>FILTER POSITION : </b>
                    <select class="select2" id="position" style="width: 100%">
                      <option value="" selected disabled></option>
                      @foreach($data['position'] AS $positions)
                        <option value="{{ $positions->position_desc }}">{{ $positions->position_desc }}</option>
                      @endforeach
                    </select>
                    <br>
                    <b>TOTAL SELECTED : <a href="#" style="text-decoration: none"><span id="total"></span></a></b>
                    <br>
                    <br>
                    <button type="button" class="btn btn-success float-right" id="btnSend" onclick="frmSubmit('send')" style="margin-left: 1%">Send Invites</button>
                    <button type="button" class="btn btn-info float-right" id="btnPreview" onclick="frmSubmit('preview')">Preview</button>
                </div>
              </div>
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Call for Invitation</h2>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table" id="tbl">
                <thead>
                  <th style="width: 2%"><input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all"></th>
                  <th>Fullname</th>
                  <th>Division</th>
                  <th>Position</th>
                </thead>
                <tbody>
                    @foreach($data['employee'] AS $employees)
                      <tr>
                        <td><input type="checkbox" class="check checks" name="selected[]" id="chck_{{ $employees->id }}" value="{{ $employees->id }}"></td>
                        <td>{{ $employees->lname.",".$employees->fname." ".$employees->mname }}</td>
                        <td>{{ $employees->division_acro }}</td>
                        <td>{{ $employees->position_desc }}</td>
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
</form>
@endsection

@section('JS')
<script type="text/javascript">
  countTotalChecked();

  function frmSubmit(type)
  {
    if(type == 'preview')
    {
        $("#frm_invitation").attr({"target" : "_blank","action" : "{{ url('invitation/preview-list') }}"}).submit();
    }
    else
    {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
      }).then((result) => {
        if (result.value) {
         $("#frm_invitation").attr({"target" : "_parent","action" : "{{ url('invitation/create') }}"}).submit();
        }
      })
    }
  }


  function countTotalChecked()
  {
    $("#total").text("");
    var total = 0;
    $(".checks").each(function () {
        if($(this).iCheck('update')[0].checked)
        {
          total++;
        }
    });
    $("#total").text(total);
    if(total == "" || total == 0)
    {
      $("#btnPreview,#btnSend").prop("disabled",true);
    }
    else
    {
      $("#btnPreview,#btnSend").prop("disabled",false);
    }
  }

  //CHECK ALL
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
      });

  // Remove the checked state from "All" if any checkbox is unchecked
  $('.check').on('ifUnchecked', function (event) {
      countTotalChecked();
      $(this).iCheck('uncheck');

      if($("#icheck_all").iCheck('update')[0].checked)
        {
          $("#icheck_all").prop( "checked", false ).iCheck('update');
          // $("#icheck_all")
        }
  });

  $('#icheck_all').on('ifUnchecked', function (event) {
      countTotalChecked();
      $(".checks").iCheck('uncheck');
  });

  $('#icheck_all').on('ifChecked', function (event) {
      countTotalChecked();
      $(".checks").iCheck('check');
  });

  // Make "All" checked if all checkboxes are checked
  $('.check').on('ifChecked', function (event) {
      countTotalChecked();
      $(this).iCheck('check');
  });

</script>
<script type="text/javascript">
  (function($){
  
  var dataTable;
  
  var select2Init = function(){
    $('select').select2({
      dropdownAutoWidth : true,
      allowClear: true,
      placeholder: "Select a position",
    });
  };
  
  var dataTableInit = function(){
    dataTable = $('#tbl').dataTable({
      "columnDefs" : [{
        "targets": 3,
        "type": 'text',
      }],
        "bLengthChange": false,
        "pageLength": -1
    });


    dataTable.on('draw.dt', function (e) {
          countTotalChecked();
      });
  };

  
  
  var dtSearchInit = function(){
    
    $('#position').change(function(){
      dtSearchAction( $(this) , 3)
    });
    
  }; 
  
  dtSearchAction = function(selector,columnId){
      var fv = selector.val();
      if( (fv == '') || (fv == null) ){
        dataTable.api().column(columnId).search('', true, false).draw();
      } else {
        dataTable.api().column(columnId).search(fv, true, false).draw();
      }
  };
  
  
  $(document).ready(function(){
    select2Init();
    dataTableInit();
    dtSearchInit();
  });

})(jQuery);
</script>
@endsection