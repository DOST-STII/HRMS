<!DOCTYPE html>
<html>
<head>
	<title>DTR MONITORING</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('DataTables-1.10.23/media/css/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('DataTables-1.10.23/media/css/jquery.dataTables.min.css') }}">
	<style type="text/css">
		body
		{
			font-family: Arial;
		}
	</style>
	<style type="text/css">
    /* Style the Image Used to Trigger the Modal */
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
</head>
<body>
	<center><h1>{{ $data['date'] }}</h1></center>
	<h2>Name : <b>{{ $data['user'] }}</b></h2>
	<table class="table" id="tbl">
		<thead>
			<th align="left">Day</th>
			<th>AM In</th>
			<th>AM Out</th>
			<th>PM In</th>
			<th>PM Out</th>
			<th>OT In</th>
			<th>OT Out</th>
			<th>Remarks</th>
		</thead>
		<tbody>
			@foreach($data['dtr'] AS $dtrs)
				<tr align="center">
					<td align="left">{{ date('d',strtotime($dtrs->fldEmpDTRdate)) . ' - ' . date('D',strtotime($dtrs->fldEmpDTRdate)) }}</td>
					<td><span title="{{ $dtrs->photo_am_in }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRamIn }}</span></td>
					<td><span title="{{ $dtrs->photo_am_out }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRamOut }}</span></td>
					<td><span title="{{ $dtrs->photo_pm_in }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRpmIn }}</span></td>
					<td><span title="{{ $dtrs->photo_pm_out }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRpmOut }}</span></td>
					<td><span title="{{ $dtrs->photo_ot_in }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRotIn }}</span></td>
					<td><span title="{{ $dtrs->photo_ot_out }}" class="fas fa-image myImg" style="cursor: pointer">{{ $dtrs->fldEmpDTRotOut }}</span></td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>

<!-- The Modal -->
<div id="imageModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

<script type="text/javascript" language="javascript" src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script type="text/javascript" language="javascript" src="{{ asset('DataTables-1.10.23/media/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('DataTables-1.10.23/media/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('#tbl').DataTable();
} );


	// Get the modal
var modal = document.getElementById("imageModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
// var img = document.getElementsByClassName("myImg");
var modalImg = document.getElementById("img01");

$(".myImg").click(function(){
    modal.style.display = "block";
    modalImg.src = this.title;
})

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}


</script>
</html>