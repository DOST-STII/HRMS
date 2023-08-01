<script type="text/javascript">
   $.getJSON( "{{ url('notifications') }}", function( datajson ) {
                
              }).done(function(datajson) {

                var total = 0;
                var txt = "";
                jQuery.each(datajson,function(i,obj){
                            if(obj.total > 0)
                            {
                              total = obj.total + total;
                              $("#notification_body").append("<div class='dropdown-divider'></div><a href='"+obj.url+"' class='dropdown-item'>("+obj.total+") "+obj.title+"</a>");
                            }
                            
                      });

                if(total > 0)
                {
                  $("#total_notification,#total_notification_sub").text(total);
                }
                else
                {
                  $("#total_notification").text("");
                  $("#total_notification_sub").text("No");
                }

            }).fail(function() {
     });
</script>