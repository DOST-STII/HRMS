<script type="text/javascript">
	 $.getJSON( "{{ url('invitation/alert') }}", function( datajson ) {
                
              }).done(function(datajson) {
                if(datajson['total'] > 0)
                {
                	Swal.fire({
                               title: 'Alert!',
                               text: "You have an invitation to apply",
                               type: 'info',
                               confirmButtonText: 'Ok'
                                 }).then((result) => {
                                   if (result.value) {
                                     window.location.href = "{{ url('invitation/list') }}";
                                   }
                                 })
                }  
            }).fail(function() {
     });
</script>