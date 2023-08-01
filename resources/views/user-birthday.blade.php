<!DOCTYPE html>
<html>
<head>
	<title></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
	<br>
	<br>
<div class="container">
	<br>
	<br>
	<table class="table" id="myTable">
		<thead>
			<th>Image</th>
			<th>Staff</th>
		</thead>
    <tbody>
  </tbody>
	</table>
</div>




</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

<script type="text/javascript">
$.getJSON( "{{ url('user-birthday-json') }}", function( datajson ) {
                
              }).done(function(datajson) {

                if(datajson.length > 0)
                {
                    jQuery.each(datajson,function(i,obj){
                          exname = "";
                          if(obj.exname != null)
                              exname = obj.exname;


                          birthdate = "";
                          if(obj.birthdate != null)
                              birthdate = obj.birthdate;


                          $("#myTable").append("<tr><td><img class='img-fluid' src='{{ asset('storage/photos') }}/"+obj.image_path+"' style='width: 100px !important'></img></td><td>"+obj.lname+", "+obj.fname+" "+obj.mname+" "+exname+"</td></tr>");
                    });
                }
                else
                {
                    $("#myTable").append("<tr><td colspan='2' align='center'>No Birthday today</td></tr>");
                }
                  
                  

            }).fail(function() {
                addDataToTables(yr,mon)
            });
</script>
</html>