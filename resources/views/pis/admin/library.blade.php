@extends('template.master')

@section('content')
<?php
//TO SET AN ACTIVE TAB
$tab_division = "";
$tab_division_content = "";
$tab_position = "";
$tab_position_content = "";
$tab_designation = "";
$tab_designation_content = "";
$tab_employment = "";
$tab_employment_content = "";

$tab_plantilla = "";
$tab_plantilla_content = "";

$tab_salary_sched = "";
$tab_salary_sched_content = "";

$tab_salary_tbl = "";
$tab_salary_tbl_content = "";

$tab_parent = "";
$tab_parent_content = "";

$tab_organization = "";
$tab_organization_content = "";



switch ($data['active_tab']) {
	case 'division':
		# code...
			$tab_division = "active";
			$tab_division_content = "show active";
		break;
	case 'position':
		# code...
			$tab_position = "active";
			$tab_position_content = "show active";
		break;
	case 'designation':
		# code...
			$tab_designation = "active";
			$tab_designation_content = "show active";
		break;
  case 'employment':
    # code...
      $tab_employment = "active";
      $tab_employment_content = "show active";
    break;
  case 'plantilla':
    # code...
      $tab_plantilla = "active";
      $tab_plantilla_content = "show active";
    break;
  case 'salary_sched':
    # code...
      $tab_salary_sched = "active";
      $tab_salary_sched_content = "show active";
    break;
  case 'salary_tbl':
    # code...
      $tab_salary_tbl = "active";
      $tab_salary_tbl_content = "show active";
    break;
  case 'parent':
    # code...
      $tab_parent = "active";
      $tab_parent_content = "show active";
    break;
  case 'organization':
    # code...
      $tab_organization = "active";
      $tab_organization_content = "show active";
    break;
}

?>
<div class="row">
	
	<div class="col-md-12">
		    <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_division }}" id="library-division-tab" data-toggle="pill" href="#library-division" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Division</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_position }}" id="library-position-tab" data-toggle="pill" href="#library-position" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Position</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_designation }}" id="library-designation-tab" data-toggle="pill" href="#library-designation" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Designation</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_employment }}" id="library-employment-tab" data-toggle="pill" href="#library-employment" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Employment Status</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_plantilla }}" id="library-plantilla-tab" data-toggle="pill" href="#library-plantilla" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Plantilla Positions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_salary_sched }}" id="library-salary-sched-tab" data-toggle="pill" href="#library-salary-sched" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Salary Schedule</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ $tab_salary_tbl }}" id="library-salary-tbl-tab" data-toggle="pill" href="#library-salary-tbl" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Salary Grade Table</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link {{ $tab_parent }}" id="library-parent-tab" data-toggle="pill" href="#library-parent" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Solo Parent Schedule</a>
                  </li>

                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade {{ $tab_division_content }}" id="library-division" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                     <table class="table" id="tbl_divisions">
                     	<thead>
                     		<th style="width: 25px">#</th>
                     		<th>Acronym</th>
                     		<th>Description</th>
                        <th>Cluster</th>
                     		<th></th>
                     	</thead>
                     	<tbody>
                     		@foreach($data['division'] as $divisions)
                                  <tr>
                                    <td></td>
                                    <td>{{ $divisions->division_acro }}</td>
                                    <td>{{ $divisions->division_desc }}</td>
                                    <td></td>
                                    <td style="width: 5%"><i class="fas fa-edit" style="cursor: pointer;color: blue" onclick="optionLibrary('update','{{ url("division/update") }}','{{ url('pis-library/division') }}','divisions',{{ $divisions->id }},'{{ $divisions->division_acro }}','{{ $divisions->division_desc }}')"></i> &nbsp <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="optionLibrary('delete','{{ url("division/delete") }}','{{ url('pis-library/division') }}','divisions',{{ $divisions->id }})"></i></td></tr>
                        @endforeach
                     	</tbody>
                     </table>
                  </div>
                  <div class="tab-pane fade {{ $tab_position_content }}" id="library-position" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                     <table class="table" id="tbl_positions">
                     	<thead>
                     		<th style="width: 25px">#</th>
                     		<th>ID</th>
                     		<th>Abbreviation</th>
                     		<th>Description</th>
                     		<th></th>
                     	</thead>
                     	<tbody>
                     		@foreach($data['position'] as $positions)
                                  <tr><td></td><td>{{ $positions->position_id }}</td><td>{{ $positions->position_abbr }}</td><td>{{ $positions->position_desc }}</td><td style="width: 5%"><i class="fas fa-edit" style="cursor: pointer;color: blue" onclick="optionLibrary('update','{{ url("position/update") }}','{{ url('pis-library/position') }}','positions',{{ $positions->id }},'{{ $positions->position_abbr }}','{{ $positions->position_desc }}','{{ $positions->position_id }}')"></i> &nbsp <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="optionLibrary('delete','{{ url("position/delete") }}','{{ url('pis-library/position') }}','positions',{{ $positions->id }})"></i></td></tr>
                            @endforeach
                     	</tbody>
                     </table>
                  </div>
                  <div class="tab-pane fade {{ $tab_designation_content }}" id="library-designation" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                     <table class="table" id="tbl_designations">
                     	<thead>
                     		<th style="width: 25px">#</th>
                     		<th>Abbreviation</th>
                     		<th>Description</th>
                     		<th></th>
                     	</thead>
                     	<tbody>
                     		@foreach($data['designation'] as $designations)
                                  <tr><td></td><td>{{ $designations->designation_abbr }}</td><td>{{ $designations->designation_desc }}</td><td style="width: 5%"><i class="fas fa-edit" style="cursor: pointer;color: blue" onclick="optionLibrary('update','{{ url("designation/update") }}','{{ url('pis-library/designation') }}','designations',{{ $designations->designation_id }},'{{ $designations->designation_abbr }}','{{ $designations->designation_desc }}')"></i> &nbsp <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="optionLibrary('delete','{{ url("designation/delete") }}','{{ url('pis-library/designation') }}','designations',{{ $designations->designation_id }})"></i></td></tr>
                            @endforeach
                     	</tbody>
                     </table>
                  </div>
                  <div class="tab-pane fade {{ $tab_employment_content }}" id="library-employment" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                     <table class="table" id="tbl_employments">
                      <thead>
                        <th style="width: 25px">#</th>
                        <th>Description</th>
                        <th></th>
                      </thead>
                      <tbody>
                        @foreach($data['employment'] as $employments)
                                  <tr><td></td><td>{{ $employments->employment_desc }}</td><td style="width: 5%"><i class="fas fa-edit" style="cursor: pointer;color: blue" onclick="optionLibrary('update','{{ url("employment/update") }}','{{ url('pis-library/employment') }}','employments',{{ $employments->employment_id }},'','{{ $employments->employment_desc }}')"></i> &nbsp <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="optionLibrary('delete','{{ url("employment/delete") }}','{{ url('pis-library/employment') }}','employments',{{ $employments->employment_id }})"></i></td></tr>
                            @endforeach
                      </tbody>
                     </table> 
                  </div>

                  <div class="tab-pane fade {{ $tab_plantilla_content }}" id="library-plantilla" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                    <table class="table" id="tbl_plantilla">
                      <thead>
                        <th>#</th>
                        <th>Item Number</th>
                        <th>Salary</th>
                        <th>Employee</th>
                        <th></th>
                      </thead>
                      <tbody>
                        @foreach($data['plantilla'] as $plantillas)
                                  <tr>
                                    <td style="width: 2%"></td>
                                    <td>{{ $plantillas->plantilla_item_number }}</td>
                                    <td>{{ formatCash($plantillas->plantilla_salary) }}</td>
                                    <td>{{ $plantillas->lname . ', '.$plantillas->fname . ' '.$plantillas->mname }}</td>
                                    <td style="width: 5%"><i class="fas fa-edit" style="cursor: pointer;color: blue" onclick="optionLibrary('update','{{ url("division/update") }}','{{ url('pis-library/division') }}','divisions',{{ $divisions->id }},'{{ $plantillas->id }}','{{ $plantillas->id }}')"></i> &nbsp <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="optionLibrary('delete','{{ url("division/delete") }}','{{ url('pis-library/division') }}','divisions',{{ $divisions->id }})"></i></td></tr>
                        @endforeach
                      </tbody>
                     </table>
                  </div>

                  <div class="tab-pane fade {{ $tab_salary_sched_content }}" id="library-salary-sched" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                    
                  </div>

                  <div class="tab-pane fade {{ $tab_salary_tbl_content }}" id="library-salary-tbl" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">

                     <p align="right" style="cursor: pointer;color: #0074c2" onclick="modalUpload()"><i class="fas fa-upload"></i> <b>UPLOAD SALARY SCHEDULE</b></p>

                     <table class="table" id="tbl_salary">
                      <thead>
                        <th>Salary Grade</th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                        <th>Step 7</th>
                        <th>Step 8</th>
                      </thead>
                      <tbody>
                        @foreach($data['salary'] AS $i => $salaries)
                                  <tr>
                                    <td style="width: 10%">{{ $salaries->salary_grade }}</td>
                                    <td>{{ formatCash($salaries->salary_1) }}</td>
                                    <td>{{ formatCash($salaries->salary_2) }}</td>
                                    <td>{{ formatCash($salaries->salary_3) }}</td>
                                    <td>{{ formatCash($salaries->salary_4) }}</td>
                                    <td>{{ formatCash($salaries->salary_5) }}</td>
                                    <td>{{ formatCash($salaries->salary_6) }}</td>
                                    <td>{{ formatCash($salaries->salary_7) }}</td>
                                    <td>{{ formatCash($salaries->salary_8) }}</td>

                                  </tr>
                        @endforeach
                      </tbody>
                     </table>   
                  </div>

                  <div class="tab-pane fade {{ $tab_parent_content }}" id="library-parent" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                    
                  </div>

                  <div class="tab-pane fade {{ $tab_organization_content }}" id="library-organization" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                    
                  </div>



                </div>
              </div>
              <!-- /.card -->
        </div>
	</div>
</div>

<div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Upload Salary Schedule</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('salary/upload') }}"> 
                <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('employee/create') }}">   -->
                {{ csrf_field() }}
                <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
                <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('pis-library/salary_tbl') }}">
                <input type="hidden" name="tbl_name" id="tbl_name" value="">
                <input type="hidden" name="tbl_name_id" id="tbl_name_id" value="">

                    <label for="customRange1">Date</label>
                    <input type="date" class="form-control" id="salary_date" name="salary_date">
                  </div>
                  <div class="form-group">
                    <!-- <label for="customFile">Custom File</label> -->

                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="salary_file" name="salary_file">
                      <label class="custom-file-label" for="salary_file">Choose file</label>
                    </div>
                  </div>
                
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


	<!-- EDIT MODAL -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
           
          {{ csrf_field() }}
          <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
          <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('pis-library') }}">
          <input type="hidden" name="tbl_name" id="tbl_name" value="">
          <input type="hidden" name="tbl_name_id" id="tbl_name_id" value="">
          
          
            <div class="modal-body">
              <div class="form-group" id="div-position" style="display: none">
                    <label for="positionid">Position ID</label>
                    <input type="text" class="form-control" id="position_id" name="position_id" placeholder="Position ID">
                  </div>
              <div class="form-group" id="div-abbreviation" style="display: none">
                    <label for="positionid">Abbreviation</label>
                    <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="Abbreviation">
                  </div>
              <div class="form-group" id="div-acronym">
                    <label for="acronym">Acronym</label>
                    <input type="text" class="form-control" id="acronym" name="acronym" placeholder="Acronym">
                  </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                  </div>
            </div>

            </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" onclick="submitFrm()">Save changes</button>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
@endsection

@section('JS')
<script type="text/javascript">
	var t1 = $("#tbl_divisions").DataTable();
	var t2 = $("#tbl_positions").DataTable();
	var t3 = $("#tbl_designations").DataTable();
  var t4 = $("#tbl_employments").DataTable();
  var t5 = $("#tbl_plantilla").DataTable();


	 t1.on('order.dt search.dt', function () {
      t1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  	}).draw();

  	t2.on('order.dt search.dt', function () {
      t2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  	}).draw();

  	t3.on('order.dt search.dt', function () {
      t3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  	}).draw();

    t4.on('order.dt search.dt', function () {
      t4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
    }).draw();

    t5.on('order.dt search.dt', function () {
      t5.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
    }).draw();

  	function optionLibrary(opt = null,url = null,url2 = null,tbl = null,tblid = null,acro = null,desc = null,postid = null)
  	{
  		$("#frm_url_action").val(url);
  		$("#frm_url_reset").val(url2);
  		$("#tbl_name").val(tbl);
  		$('#tbl_name_id').val(tblid);

  		$('#acronym').val(acro);
  		$('#description').val(desc);

  		//SHOW HIDE POSITION ID
  		$("#div-position").hide();
  		$("#div-abbreviation").hide();
  		$("#div-acronym").show();
  		if(tbl == 'positions')
  		{
  			$("#div-position").show();
  			$("#div-abbreviation").show();
  			$("#div-acronym").hide();
  			$('#position_id').val(postid);
  			$('#abbreviation').val(acro);
  		}
      else if(tbl == 'designations')
      {
        $("#div-abbreviation").show();
        $("#div-acronym").hide();
        $('#abbreviation').val(acro);
      }
      else if(tbl == 'employments')
      {
        $("#div-abbreviation").hide();
        $("#div-acronym").hide();
      }


  		if(opt == 'delete')
  		{
  			$("#frm").submit();
  		}
  		else
  		{
  			$("#modal-edit").modal('toggle');
  		}
  		
  	}

  	function submitFrm()
  	{
  		$("#frm").submit();
  	}

    function modalUpload()
    {
      $("#modal-upload").modal('toggle');
    }
</script>
@endsection