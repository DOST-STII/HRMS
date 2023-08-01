@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<div class="row">
        <div class="col-12">


          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Letter of Request</h3>
            <!-- /.card-header -->
          </div>
            <div class="card-body">
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Description</th>
                  <th style="width: 15%">Type of Request</th>
                  <th style="width: 10%">Attachments</th>
                  <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                  @foreach($data['request_list'] AS $lists)
                      @if($lists->request_status == 'Upload Vacancy Advise' || $lists->request_status == 'Pending')
                      <tr>
                        <td></td>
                        <td>{{ $lists->request_desc }}</td>
                        <td class="text-center">Hiring</td>
                        <td align="center"><a href="{{ asset('../storage/app/'.$lists->request_attachment) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                        <td>
                          @if($lists->request_status == 'Received')
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fas fa-list"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="#" onclick="actionRequest('hiring','approve',{{ $lists->id }})" style="color:green"><i class="fas fa-thumbs-up"></i> Approve</a>
                                  <a class="dropdown-item" href="#" onclick="actionRequest('hiring','disapprove',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i> Disapprove</a>
                                </div>
                          @else
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <i class="fas fa-list"></i>
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" onclick="actionRequest('hiring','receive',{{ $lists->id }})"><i class="fas fa-check"></i> Receive</a>
                                    <a class="dropdown-item" href="#" style="cursor: not-allowed;color: #999;" onclick="return false;"><i class="fas fa-thumbs-up"></i> Approve</a>
                                    <a class="dropdown-item" href="#" onclick="actionRequest('hiring','disapprove',{{ $lists->id }})" style="color:red"><i class="fas fa-trash"></i> Disapprove</a>
                                  </div>
                          @endif
                        </td>
                      </tr>
                      @endif
                  @endforeach


                  @foreach($data['training_list'] AS $trainings)
                      @if($trainings->training_status == 'Received' || $trainings->training_status == 'Pending')
                      <tr>
                        <td></td>
                        <td>{{ $trainings->training_title }}</td>
                        <td class="text-center">Training</td>
                        <td align="center"><a href="{{ asset('../storage/app/'.$trainings->training_attachment) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                        <td>
                          @if($trainings->training_status == 'Received')
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fas fa-list"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="#" onclick="actionRequest('training','approve',{{ $trainings->id }})" style="color:green"><i class="fas fa-thumbs-up"></i> Approve</a>
                                  <a class="dropdown-item" href="#" onclick="actionRequest('training','disapprove',{{ $trainings->id }})" style="color:red"><i class="fas fa-trash"></i> Disapprove</a>
                                </div>
                          @else
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <i class="fas fa-list"></i>
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" onclick="actionRequest('training','receive',{{ $trainings->id }})"><i class="fas fa-check"></i> Receive</a>
                                    <a class="dropdown-item" href="#" style="cursor: not-allowed;color: #999;" onclick="return false;"><i class="fas fa-thumbs-up"></i> Approve</a>
                                    <a class="dropdown-item" href="#" onclick="actionRequest('training','disapprove',{{ $trainings->id }})" style="color:red"><i class="fas fa-trash"></i> Disapprove</a>
                                  </div>
                          @endif
                        </td>
                      </tr>
                      @endif
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
</div>

      <!-- RESET PASSWORD MODAL-->
      <div class="modal fade" id="modal-request-approve">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="frm" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="status" id="status">
            <input type="hidden" name="tblid" id="tblid" value="">
            <input type="hidden" name="frm_url_action" id="frm_url_action" value="">
            <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('letter-of-request-list') }}">

            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <strong>Plantilla Item Number</strong>
                  <input type="text" class="form-control" name="plantilla_item_number" id="plantilla_item_number">
                </div>
                <div class="col-md-12">
                  <strong>Position Title</strong>
                  <select class="form-control select2" id="position" name="position">
                    <option value=""></option>
                    @foreach($data['position'] as $positions)
                        <option value="{{ $positions->position_id }}">{{ $positions->position_desc }}</option>
                                  @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <strong>Division</strong>
                  <select class="form-control select2" id="division" name="division">
                    <option value=""></option>
                    @foreach($data['division'] as $divisions)
                        <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <strong>Salary</strong>
                  <input type="text" class="form-control" name="plantilla_salary" id="plantilla_salary">
                </div>
                <div class="col-md-12">
                  <strong>Steps</strong>
                  <select class="form-control" id="plantilla_steps" name="plantilla_steps">
                                  <option value="" selected></option>
                                  @for ($i = 1; $i <= 9;$i++)
                                      <option value="{{ $i }}">{{ $i }}</option>
                                  @endfor
                                </select> 
                </div>
                <div class="col-md-12">
                  <br>
                  <strong>Special</strong>
                  <input type="radio" name="plantilla_special" id="plantilla_special_yes" value="1"> YES <input type="radio" name="plantilla_special" id="plantilla_special_no" value=""> NO 
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

<script>
  $(function () {
    var t = $("#tbl").DataTable();

    t.on('order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  });

  function actionRequest(type,act,id)
  {
    $("#tblid").val(id);

    if(type == 'hiring')
    {
      if(act == 'approve')
      {
        $("#frm_url_action").val("{{ url('request-for-hiring/approve') }}");
        $("#modal-request-approve").modal('toggle');
      }
      else
      {
        $("#frm_url_action").val("{{ url('request-for-hiring/action') }}");
        $("#status").val(act);
        $("#frm").submit();
      }
    }
    else
    {
      $("#frm_url_action").val("{{ url('request-for-training/action') }}");
      $("#status").val(act);
      $("#frm").submit();
    }
      
     
  }

  function modalOnSubmit()
  {
    $("#frm").submit();
  }
</script>

<script type="text/javascript">
   $.getJSON( "{{ url('request-for-hiring-alert/clear') }}", function( datajson ) {
                
              }).done(function(datajson) {
            }).fail(function() {
     });
</script>
@endsection