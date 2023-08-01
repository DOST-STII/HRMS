<script type="text/javascript">
   $.getJSON( "{{ url('request-for-hiring-alert/json') }}", function( datajson ) {
                
              }).done(function(datajson) {
                if(datajson['total'] > 0)
                {
                  Swal.fire({
                               title: 'Alert!',
                               text: "You have an update in your Hiring Request",
                               type: 'info',
                               confirmButtonText: 'Ok'
                                 }).then((result) => {
                                   if (result.value) {
                                     window.location.href = "{{ url('request-for-hiring') }}";
                                   }
                                 })
                }  
            }).fail(function() {
     });
</script>