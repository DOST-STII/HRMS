@extends('template.master')

@section('CSS')

@endsection

@section('content')
        <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form"> -->
        <form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('request-for-hiring/update-applicants') }}">
        {{ csrf_field() }}
        <input type="hidden" name="plantilla_id" id="plantilla_id" value="{{ $data['detail']['id'] }}">
        <input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('request-for-hiring/update-applicants') }}">
        <input type="hidden" name="frm_url_reset" id="frm_url_reset" value="">
<div class="row">


        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Initial Screening List of Applicants</h3>
              @if(Auth::user()->usertype == 'Marshal')
              <div class="float-right"><button type="button" class="btn btn-primary" onclick="modalUpdateList({{ $data['detail']['id'] }})">UPDATE LIST</button></div>
              @endif

            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 callout callout-success">
                  <b>ITEM NUMBER : </b>{{ $data['detail']['plantilla_item_number'] }}<br/>
                  <b>POSITION : </b>{{ $data['detail']['position_desc'] }}<br/>
                  <b>DIVISION : </b>{{ getDivision($data['detail']['division_id']) }}<br/>
                </div>
              </div>
              <table id="tbl" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 2%">#</th>
                  <th>Applicant</th>
                  <th>Requirements</th>
                  <!-- <th class="text-center">Summary of Qualifications</th> -->
                  <th class="text-center">Qualified or Not</th>
                  <th style="width: 20%">Remarks</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php $i = 0; ?>
                  @foreach($data['list'] AS $lists)
                    
                    <tr>
                      <td></td>
                      <td><input type="hidden" name="applicants[]" value="{{ $lists->id }}">{{ $lists->lname.", ".$lists->fname." ".$lists->mname }}</td>
                      <td>
                        <ul>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_appletter) }}" style="text-decoration: none;" target="_blank">Application Letter</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_cv) }}" style="text-decoration: none;" target="_blank">CV</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_photo) }}" style="text-decoration: none;" target="_blank">2x2 Photo</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_cs) }}" style="text-decoration: none;" target="_blank">Civil Service Eligibility</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_trainingcert) }}" style="text-decoration: none;" target="_blank">Training Certificates</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_trainingcert) }}" style="text-decoration: none;" target="_blank">Service Record/Employment Certificate</a></li>
                          <li><a href="{{ asset('../storage/app/'.$lists->file_evaluationcert) }}" style="text-decoration: none;" target="_blank">Performance Evaluation</a></li>
                        </ul>
                      </td>
                      <!-- <td></td> -->
                      <td class="text-center">
                        @if(Auth::user()->usertype == 'Administrator')
                          @if($lists->qualified == 'YES')
                            <!-- <input type="checkbox" name="qualified[]" value="YES-{{ $lists->id }}" checked> -->
                            <input type="radio" name="qualified[{{$i}}]" value="YES" checked> YES <input type="radio" name="qualified[{{$i}}]" value="NO"> NO
                          @elseif($lists->qualified == 'NO')
                            <input type="radio" name="qualified[{{$i}}]" value="YES"> YES <input type="radio" name="qualified[{{$i}}]" value="NO" checked> NO
                          @else
                            <input type="radio" name="qualified[{{$i}}]" value="YES"> YES <input type="radio" name="qualified[{{$i}}]" value="NO"> NO
                          @endif
                        @else
                          {{ $lists->qualified }}
                        @endif
                      </td>
                      <td>
                        @if(Auth::user()->usertype == 'Administrator')
                          <textarea class="form-control" name="remarks[]">{{ $lists->remarks }}</textarea>
                        @else
                          {{ $lists->remarks }}
                        @endif
                        </form>
                      </td>
                      <td class="text-center">
                        @if(Auth::user()->usertype == 'Administrator')
                              <!-- <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-list"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> </a>
                              </div> -->
                            <button type="button" class="btn btn-success btn-sm" onclick="hireOpt({{ $lists->id }})"><i class="fas fa-check"></i> Hire</button>
                        @endif
                      </td>
                    </tr>
                    <?php $i++; ?>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      
      
      <div class="modal fade" id="modal-hire">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="frm_applicant" enctype="multipart/form-data" action="{{ url('hire-new-employee') }}" role="form">
              {{ csrf_field() }}
              <input type="hidden" name="job_application_id" id="job_application_id" value="">

              <h5>NEW EMPLOYEE? &nbsp&nbsp&nbsp&nbsp&nbsp <input type="radio" class="empOpt" name="empOpt" value="1" checked> YES <input type="radio" class="empOpt" name="empOpt" value="0"> NO</h5>
             
              <div id="employees" style="display: none">
                <br>
                 <h5><b>Select Employee</b></h5>
                 <select class="select2" name="user_id">
                      <option value="" disabled selected></option>
                   @foreach($data['employee'] AS $employees)
                      <option value="{{ $employees->id }}">{{ $employees->lname.", ".$employees->fname." ".$employees->mname }}</option>
                   @endforeach
                 </select>
                 <br>
                 <h5><b>Employment Status</b></h5>
                 <select class="select2" name="employment">
                      <option value="" disabled selected></option>
                   @foreach($data['employment'] AS $employments)
                      <option value="{{ $employments->employment_id }}">{{ $employments->employment_desc }}</option>
                   @endforeach
                 </select>

                 <br>
                 <h5><b>First Day of Service</b></h5>
                 <input type="date" class="form-control" name="fldservice">

                 <br>
                 <h5><b>User Type</b></h5>
                 <select class="form-control" id="usertype" name="usertype" required>
                              <option value="" disabled selected></option>
                              <option value="Director">Director</option>
                              <option value="Marshal">Marshal</option>
                              <option value="Staff">Staff</option>
                            </select>

              </div>

            </div>
            </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submitFrm()">Save changes</button>
            
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

  $(".empOpt").change(function(){
    $("#employees").hide();
    $("#frm_applicant").prop('action',"{{ url('hire-new-employee') }}");
    if(this.value == 0)
    {
      $("#employees").show();
      $("#frm_applicant").prop('action',"{{ url('hire-transfer-employee') }}");
    }
  });

  function hireOpt(id)
  {
    $("#modal-hire").modal('toggle');
    $("#job_application_id").val(id)
  }

  function submitFrm()
    {
        Swal.fire({
          title: 'Are you sure?',
          text: "",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
        }).then((result) => {
          if (result.value) {
            $("#frm_applicant").submit();
          }
        })
    }

  function modalUpdateList(id)
  {
    $("#frm_url_reset").val("{{ url('recruitment/list-of-applicants') }}/"+id);
    $("#frm2").submit();
  }
</script>
@endsection