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
	<button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Filter</button>
	<br>
	<br>
	<table class="table" id="myTable">
		<thead>
			<th>Item Number</th>
			<th>Date</th>
		</thead>
	</table>
</div>

<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <strong>Month</strong>
        <select class="form-control" id="select_mon">
        	<option value="all">All months</option>
        	<option value="1">January</option>
        	<option value="2">February</option>
        	<option value="3">March</option>
        	<option value="4">April</option>
        	<option value="5">May</option>
        	<option value="6">June</option>
        	<option value="7">July</option>
        	<option value="8">August</option>
        	<option value="9">September</option>
        	<option value="10">October</option>
        	<option value="11">November</option>
        	<option value="12">December</option>
        </select>
        <br>
        <strong>Year</strong>
        <select class="form-control" id="select_year">
        <?php
        	$yr = date('Y');
        	$yr2 = $yr - 5;
        	for ($i=$yr; $i >= $yr2; $i--) { 
        		echo "<option value='$i'>$i</option>";
        	}
        ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updateTable()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

<script type="text/javascript">
  $(document).ready(function(){
  	addDataToTables({{ date('Y') }},'all');
  });

  function updateTable()
  {
  	yr = $("#select_year").val();
  	mon = $("#select_mon").val();

  	addDataToTables(yr,mon);
  }

  function addDataToTables(yr,mon)
  {
    var t = $('#myTable').DataTable();
    t.clear().draw();
    t.row.add( [
                  'Loading data..',
                  'Loading data..'
                ] ).draw( false );

    $.getJSON( "{{ url('sample-filter-list') }}/"+yr+"/"+mon, function( datajson ) {
                
              }).done(function(datajson) {

                  t.clear().draw();
                  jQuery.each(datajson,function(i,obj){
                          t.row.add( [
                                    obj.plantilla_item_number,
                                    obj.plantilla_date_from
                                ] ).draw( false );
                    });
                  

            }).fail(function() {
                addDataToTables(yr,mon)
            });
  }

</script>
</html>