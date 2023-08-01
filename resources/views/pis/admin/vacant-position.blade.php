@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
<!--   <div class="col-md-3 col-sm-6 col-12"  style="cursor: pointer;">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="far fa-copy"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Vacant <b>FUNDED</b></span>
                <span class="info-box-number">-</span>
              </div>
            </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12"  style="cursor: pointer;">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Vacant <b>UNFUNDED</b></span>
                <span class="info-box-number">-</span>
              </div>
            </div>
  </div> -->
</div>
<div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Vacant Plantilla Position</h3><div class="float-right"><button type="button" class="btn btn-primary" onclick="modalFunction('add-new-pantilla',0)"><i class="fas fa-plus"></i> ADD NEW</button></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Item Number</th>
                  <th>Position Title</th>
                  <th>Division</th>
                  <th>Salary</th>
                  <th>Special</th>
                  <th>Steps</th>
                  <!-- th>Posted</th>
                  <th class="text-center">Applicants</th>
                  <th class="text-center">Invites</th>
                  <th style="width: 8%"></th> -->
                </tr>
                </thead>
                <tbody>
                  @foreach($data['plantilla'] as $plantillas)
                  <?php
                    //GET REQUEST ID
                    $letter = App\Request_for_hiring::where('plantilla_id',$plantillas->id)->first();
                  ?>
                        <tr>
                          <td></td>
                          <td>{{ $plantillas->plantilla_item_number }}</td>
                          <td>{{ $plantillas->position_desc }}</td>
                          <td>{{ getDivision($plantillas->division_id) }}</td>
                          <td>{{ formatNumber('currency',$plantillas->plantilla_salary) }}</td>
                          <td class="text-center"><?php if($plantillas->plantilla_special == 0){echo 'NO';}else{echo 'YES';} ?></td>
                          <td>{{ $plantillas->plantilla_steps }}</td>
                          <!-- <td class="text-center">
                            @if($plantillas->plantilla_posted == 1)
                              YES
                            @else
                              NO
                            @endif
                          </td>
                          <td class="text-center">{{ getApplicant('count',$plantillas->id,$plantillas->request_for_hiring_id,$plantillas->request_status) }}</td>
                          <td class="text-center">{{ counInvites($plantillas->id) }}</td>
                          <td class="text-center">
                              <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" onclick="callInvite({{ $plantillas->id }})"><i class="fas fa-bullhorn"></i> Call for Application</a>

                                <a class="dropdown-item" href="#" onclick="modalUpdate({{ $plantillas->id }})"><i class="fas fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#" onclick="modalFunction('assign-plantilla',{{ $plantillas->id }},'{{ url("plantilla/assign") }}','{{ url("vacant-position") }}')"><i class="fas fa-exchange-alt"></i> Assign to an Employee</a>
                                <a class="dropdown-item" href="#" style="color:red" onclick="modalFunction('delete-plantilla','{{ $plantillas->request_for_hiring_id }}',{{ $plantillas->id }})"><i class="fas fa-trash"></i> Delete</a>
                              </div>
                          </td> -->
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


      <div class="modal fade" id="modal-option">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
              <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('plantilla/create') }}">   -->
              {{ csrf_field() }}
              <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
              <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">
              <input type="hidden" name="plantillaid" id="plantillaid" value="">
              <input type="hidden" name="letterid" id="letterid" value="">
              <div class="div-content" id="div-new-pantilla">
                  <strong>Item Number</strong>
                            <br>
                            <p class="text-muted">
                              <input type="text" class="form-control" name="item_number" id="item_number" placeholder="" autocomplete="off">
                            </p>

                    <strong>Position</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control select2" id="position" name="position">
                                  <option value=""></option>
                                  @foreach($data['position'] as $positions)
                                      <option value="{{ $positions->position_id }}">{{ $positions->position_desc }}</option>
                                  @endforeach
                                </select>
                            </p>

                    <strong>Division</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control select2" id="p_division" name="p_division">
                                  <option value=""></option>
                                  @foreach(getDivisionList() as $divisions)
                                      <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                                  @endforeach
                                </select>
                            </p>

                    <strong>Salary</strong>
                            <br>
                            <p class="text-muted">
                              <input type="number" class="form-control" name="salary" id="salary" placeholder="" autocomplete="off">
                            </p>

                    <strong>Special</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control" id="special" name="special">
                                  <option value="0">No</option>
                                  <option value="2">Yes</option>
                                </select>
                            </p>

                    <strong>Step</strong>
                            <br>
                              <p class="text-muted">
                                <select class="form-control" id="steps" name="steps">
                                  <option value="" selected></option>
                                  @for ($i = 1; $i <= 9;$i++)
                                      <option value="{{ $i }}">{{ $i }}</option>
                                  @endfor
                                </select>
                              </p>
              </div>

              <div class="div-content" id="div-assign-pantilla">
                  <strong>Select Employee</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control select2" id="user_id" name="user_id">
                                  <option value=""></option>
                                  @foreach($data['employee'] as $employees)
                                      <option value="{{ $employees->id }}">{{ $employees->lname.', '.$employees->fname.' '.$employees->mname.' '.$employees->exname }}</option>
                                  @endforeach
                                </select>
                            </p>

                    <strong>Division</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control select2" id="division" name="division">
                                  <option value=""></option>
                                  @foreach($data['division'] as $divisions)
                                      <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                                  @endforeach
                                </select>
                            </p>

                    <strong>Appointment Date</strong>
                            <br>
                            <p class="text-muted">
                              <input type="date" class="form-control" name="date_from">
                            </p>

                    <strong>Designation</strong>
                            <br>
                            <p class="text-muted">
                              <select class="form-control select2" id="designation" name="designation">
                                  <option value=""></option>
                                  @foreach($data['designation'] as $designations)
                                      <option value="{{ $designations->designation_id }}">{{ $designations->designation_desc }}</option>
                                  @endforeach
                                </select>
                            </p>
              </div>

              <div class="div-content" id="div-invitation">
                  <strong>Select position to invite</strong>
                  <select class="select2" name="positions[]" multiple>
                      <option value=""  disabled></option>
                    @foreach($data['position'] AS $positions)
                      <option value="{{ $positions->position_id }}">{{ $positions->position_desc }}</option>
                    @endforeach
                  </select>
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
@endsection

@section('JS')

<script>
  $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });


  function modalFunction(title,letterid,plantillaid)
  {
    $(".div-content").hide();
    $("#frm_url_reset").val('{{ url("vacant-position") }}');

    //DATA THE WILL BE SEND
    $("#plantillaid").val(plantillaid);
    $("#letterid").val(letterid);

    switch(title)
    {
      case "add-new-pantilla":
        $("#frm_url_action").val('{{ url("plantilla/create") }}');
        $("#modal-option").modal('toggle');
        $("#div-new-pantilla").show();
        $("#modal-title").empty().text("Add New Plantilla");
        $("#icon-title").removeClass().addClass('fas fa-user-plus');
      break;

      // case "assign-plantilla":
      //   $("#modal-option").modal('toggle');
      //   $("#div-assign-pantilla").show();
      //   $("#modal-title").empty().text("Assign Plantilla");
      //   $("#icon-title").removeClass().addClass('fas fa-exchange-alt');
      // break;

      case "repost-plantilla":
        $("#frm_url_action").val('{{ url("plantilla/repost") }}');
        $("#frm").submit();
      break;

      case "delete-plantilla":
        $("#frm_url_action").val('{{ url("plantilla/delete") }}');
        $("#frm").submit();
      break;

    }

  }

  function modalUpdate(id)
  {
    $(".div-content").hide();

    //DATA THE WILL BE SEND
    $("#frm_url_action").val("{{ url("plantilla/update") }}");
    $("#frm_url_reset").val("{{ url("vacant-position") }}");
    $("#plantillaId").val(id);

    $("#modal-option").modal('toggle');
    $("#div-new-pantilla").show();
    $("#modal-title").empty().text("Update Plantilla");
    $("#icon-title").removeClass().addClass('fas fa-user-plus');

    $.getJSON( "{{ url('plantilla/json') }}/"+id, function( datajson ) {
                
              }).done(function(datajson) {
                jQuery.each(datajson,function(i,obj){
                          $("#item_number").val(obj.plantilla_item_number);
                          $("#salary").val(obj.plantilla_salary);
                          $("#position").val(obj.position_id).trigger('change');
                          $("#p_division").val(obj.division_id).trigger('change');
                          $("#special").val(obj.plantilla_special);
                          $("#steps").val(obj.plantilla_steps);            
                    });
            }).fail(function() {
            });
  }

  function callInvite(id)
  {
    // $(".div-content").hide();

    // $(".select2").val(-100).trigger('change');

    // //DATA THE WILL BE SEND
    // $("#frm_url_action").val("{{ url("invitation/list") }}");
    // $("#frm_url_reset").val("{{ url("vacant-position") }}");
    // $("#plantillaId").val(id);

    // $("#modal-option").modal('toggle');
    // $("#div-invitation").show();
    // $("#modal-title").empty().text("Call for Invitation");
    // $("#icon-title").removeClass().addClass('fas fa-bullhorn');
    window.location.href = "{{ url('invitation/select') }}/"+id;
   
  }
</script>
@endsection