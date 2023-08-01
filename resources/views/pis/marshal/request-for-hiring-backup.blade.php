@extends('template.master')

@section('CSS')
  <link rel="stylesheet" href="{{ asset('multidate/bootstrap-datepicker.css') }}">
@endsection

@section('content')
<!--  -->
<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Request</h3><div class="float-right"><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-request"><i class="fas fa-plus"></i>NEW REQUEST</a></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Description</th>
                  <th style="width: 20%" class="text-center">Type of Request</th>
                  <th style="width: 10%" class="text-center">Status</th>
                  <th style="width: 10%" class="text-center">Attachments</th>
                  <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['request_list'] AS $lists)
                      <tr>
                        <td></td>
                        <td>
                          @if($lists->request_seen == null)
                              <div class="badge-alert"><span class="badge badge-primary">Updated</span><br></div>
                          @endif
                          {{ $lists->request_desc }}
                        </td>
                        <td class="text-center">Hiring</td>
                        <td align="center">{{ formatStatus($lists->request_status) }}</td>
                        <td align="center"><a href="{{ asset('../storage/app/'.$lists->request_attachment) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                        <td>
                          @if(!isset($lists->request_disapproved))
                          <!-- <i class="fas fa-edit" style="cursor: pointer;color: #17a2b8" onclick="modalOption('edit-request',{{ $lists->id }})"></i>  --><i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('hiring','delete-request',{{ $lists->id }})"></i>
                        </td>
                          @else
                          <i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('hiring','delete-request',{{ $lists->id }})"></i></td>
                          @endif
                      </tr>
                  @endforeach


                  @foreach($data['training_list'] AS $trainings)
                      <tr>
                        <td></td>
                        <td>
                          {{ $trainings->training_title }}
                        </td>
                        <td class="text-center">Training</td>
                        <td align="center">{{ formatStatus($trainings->training_status) }}</td>
                        <td align="center"><a href="{{ asset('../storage/app/request_training/'.$trainings->training_attachment) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                        <td><i class="fas fa-trash" style="cursor: pointer;color: red" onclick="modalOption('training','delete-request',{{ $trainings->id }})"></i></td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card --> </div>
        <!-- /.col -->
      </div>
    </div>

      <!-- RESET PASSWORD MODAL-->
      <div class="modal fade" id="modal-request">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">  
            <!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request-for-hiring/create') }}">   -->
            {{ csrf_field() }}
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('letter-request') }}">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('request-for-hiring/create') }}">

            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <strong>Request for</strong><br>
                  <input type="radio" class='type-letter' name="typeletter" value="1" checked> Hiring <input type="radio" class='type-letter' name="typeletter" value="2"> Training 
                </div>
              </div>
              <br>

              <div class="div-content" id="div-content-hiring">
                <div class="row">
                  <div class="col-md-12">
                    <strong>Description</strong>
                    <textarea class="form-control" name="request_desc" id="request_desc"></textarea>
                  </div>
                  <div class="col-md-12">
                    <strong>Attachment</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="request_attachment" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              <div class="div-content" id="div-content-training" style="display: none">
                <div class="row">
                  <div class="col-md-12">
                    <strong>Staff Attending</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control" name="user_id">
                            @foreach(getStaffDivision() AS $value)
                              <option value="{{ $value->id }}">{{ $value->lname . ", " . $value->fname . " " . $value->mname }}</option>
                            @endforeach
                          </select>
                        </p>

                    <strong>Title of Seminar/Training</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control training" name="training_title" id="training_title" placeholder="" autocomplete="off">
                        </p>
                    <strong>Conducted By</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control training" name="training_conducted_by" id="training_conducted_by" placeholder="" autocomplete="off">
                        </p>
                    <strong>Inclusive Dates</strong>
                        <br>
                        <p class="text-muted">
                          <input type="text" class="form-control date training" name="training_inclusive_date" id="training_inclusive_date" autocomplete="off"/>
                        </p>
                    
                  </div>
                  <div class="col-md-6">
                    <strong>Type</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control training" name="training_type" id="training_type">
                            <option value="Free">Free</option>
                            <option value="Funded">PCAARRD Funded</option>
                            <option value="Personal">Personal</option>
                          </select>
                        </p>
                  </div>
                  <div class="col-md-6" id="div-amount" style="display: none">
                      <strong>Amount</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" name="training_amount" id="training_amount" class="form-control training">
                        </p>
                  </div>
                  <div class="col-md-6">
                    <strong>Training Hours</strong>
                        <br>
                        <p class="text-muted">
                          <input type="number" class="form-control training" name="training_hours" id="training_hours" autocomplete="off"/>
                        </p>
                  </div>
                  <div class="col-md-12">
                    <strong>LD</strong>
                        <br>
                        <p class="text-muted">
                          <select class="form-control select2 training" name="training_ld[]" id="training_ld" multiple>
                            <option value="Managerial">Managerial</option>
                            <option value="Supervisory">Supervisory</option>
                            <option value="Technical">Technical</option>
                          </select>
                        </p>
                  </div>
                  <div class="col-md-12">
                    <strong>Attachment</strong>
                    <div class="form-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input" name="training_attachment" id="customFile2">
                            <label class="custom-file-label" for="customFile2">Choose file</label>
                    </div>
                  </div>
                </div>
                </div>
              </div>
              
            </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="modalOnSubmit()">Save changes</button>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



@endsection

@section('JS')
<script src="{{ asset('multidate/bootstrap-datepicker.js') }}"></script>
<script>
  $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

  $('.date').datepicker({
        multidate: true,
        format: "yyyy-mm-dd",
        });

  $(".type-letter").change(function(){
    $(".div-content").hide();
    console.log(this.value);
    switch(this.value)
    {
      case "1":
        $("#div-content-hiring").show();
        $("#frm_url_action").val("{{ url('request-for-hiring/create') }}");
      break;

      case "2":
        $("#div-content-training").show();
        $("#frm_url_action").val("{{ url('request-for-training/create') }}");
      break;

    }
  })

  $("#training_type").change(function(){
    $("#div-amount").hide();
    if(this.value == "Funded"){
      $("#div-amount").show();
    }
  });

  function modalOption(type,act,id)
  {
    $("#tblid").val(id);

    if(type == 'hiring')
    {
      switch(act)
      {
        case 'edit-request':
          $("#modal-request").modal('toggle');
          $("#frm_url_action").val("{{ url('request-for-hiring/update') }}");
          $.getJSON( "{{ url('request-for-hiring/json') }}/"+id, function( datajson ) {
                  
                }).done(function(datajson) {
                  jQuery.each(datajson,function(i,obj){
                           $("#request_desc").empty().val(obj.request_desc);
                           
                      });
              }).fail(function() {
              });
        break;
        case 'delete-request':
          $("#frm_url_action").val("{{ url('request-for-hiring/delete') }}");
          $("#frm").submit();
        break;
      }
    }
    else
    {
      $("#frm_url_action").val("{{ url('request-for-training/delete') }}");
      $("#frm").submit();
    }
    
  }

  setInterval(function() {
      $(".badge-alert").fadeOut(1000);
    }, 5000);

  function modalOnSubmit()
  {
    $("#frm").submit();
  }

  $.getJSON( "{{ url('request-for-hiring-alert/clear') }}", function( datajson ) {
              }).done(function(datajson) {
            }).fail(function() {
     });
</script>
@endsection