<script type="text/javascript">
    $(document).ready(function(){

    getLeaveCTO({{ Auth::user()->id }});

    $("#cto-choice").hide();
    

    $("#payslip-mon").val("{{ date('F') }}");
    $("#dtr_mon").val("{{ date('m') }}");
    $("#dtr_year,#payslip-year").val({{ date('Y') }});

    $("#userid,#userid2").val({{ Auth::user()->id }});

   var now = new Date();
      now.setDate(now.getDate()+2);
      $('#leave_duration').daterangepicker({
          endDate:now,
          minDate:now,
          isInvalidDate: function(date) {
            if (date.day() == 0 || date.day() == 6)
              return true;
            return false;
          }   
      });
    

    var now2 = new Date();
    $('#leave_duration2').daterangepicker({
      // maxDate: now2,
      isInvalidDate: function(date) {
          if (date.day() == 0 || date.day() == 6)
            return true;
          return false;
        }
    });

    $('#leave_duration3').daterangepicker(
      {
        singleDatePicker : true,
        // isInvalidDate: function(date) {
        //     if (date.day() == 0 || date.day() == 6)
        //       return true;
        //     return false;
        //   }
      }
    );

    $('#leave_duration4').daterangepicker();

    checkPendingRequest();

  });

  $('input:radio[name="vl_select"]').change(
    function(){
      $("#vl_select_specify").hide();
      if(this.value == 'Abroad')
            $("#vl_select_specify").show();
    });
  
    $('input:radio[name="request_cto"]').change(
    function(){
      $("#div_request_ot,#cto_bal,#div_request_ot").hide();
      $("#ctorequest").val(null);
      if(this.value == 'apply_cto')
      {
        $("#ctorequest").val(5);
        $("#cto_bal").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-leave-request') }}"});
      }
      else
      {
        $("#div_request_ot,#div_request_ot").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-ot-request') }}"});
      }
           
            
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




  function showRequest(title)
  {
    $("#modal-request-for-title").text(title);
    $("#modal-request-for").modal("toggle");

    $("#option-leave,#option-remarks,#option-to,#option-cto,#option-wfh,#cto_bal,#cto-choice,#cto_bal").hide();
    $("#wfhrequest").val(null);
    $("#ctorequest").val(null);
    $('#option-leave-duration,#option-leave-duration2,#option-leave-duration3,#option-leave-duration4,#option-vl-select,#option-sl-select').hide();

    switch(title)
    {
      case "Apply for Leave":
        $("#option-leave,#option-leave-duration,#option-vl-select").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-leave-request') }}"});
      break;

      case "Work From Home":
        $("#wfhrequest").val(16);
        $("#option-leave-duration4,#option-wfh").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-leave-request') }}"});
      break;

      case "Request for T.O.":
        $("#option-leave-duration4,#option-to").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-to-request') }}"});
        // $("#frm_request").attr({"action" : "{{ url('test-leave') }}"});
      break;

      case "Request for O.T. / CTO":
        $("#option-leave-duration4,#option-cto,#cto-choice").show();
        $("#frm_request").attr({"action" : "{{ url('dtr/send-ot-request') }}"});
      break;
    }
  }


  $("#leave_id").change(function(){

    getLeaveDef(this.value);
    
    $('#option-leave-duration,#option-leave-duration2,#option-leave-duration3,#option-leave-duration4,#option-vl-select,#option-sl-select').hide();


    if(this.value == 1 || this.value == 6)
    {
      $('#option-leave-duration,#option-vl-select').show();
    }
    else if(this.value == 2)
    {
      $('#option-leave-duration2,#option-sl-select').show();
    }
    else if(this.value == 16)
    {
      $('#option-wfh').show();
      $('#option-leave-duration4').show();
    }
    else if(this.value == 3)
    {
      $('#option-leave-duration2,#option-vl-select').show();
    }
    else if(this.value == 9 || this.value == 7)
    {
      $('#option-leave-duration3').show();
    }
    else
    {
      $('#option-leave-duration2').show();
    }

    switch(this.value)
    {
        case 1:
        break;
        case 2:
        break;
    }

  });

 
  function toPrint(type,id) {

    if(type == 'leave')
    {
      action = "{{ url('dtr/print-leave') }}";
    }
    else if(type == 'to')
    {
      action = "{{ url('dtr/print-to') }}";
    }
    else if(type == 'wfh')
    {
      action = "{{ url('dtr/print-wfh') }}";
    }
    else
    {
      action = "{{ url('dtr/print-ot') }}";
    }

    $("#req_id").val(id);
    $("#frm_request_print").prop('action',action).submit();
  }


  function getLeaveDef(id)
    {
      $.getJSON( "{{ url('leave/json') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                  $("#leave_def").empty().append(datajson.leave_def);

              });
    }
</script>