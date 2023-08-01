@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">CHANGE PASSWORD</h3>
			<div class="card-tools">
                  
            </div>
            </div>
			
            <!-- /.card-header -->
            <div class="card-body">
                  <form method="POST" id="frm2" enctype="multipart/form-data" role="form" action="{{ url('change-password-send') }}">
				  <!-- <form method="POST" id="frm" enctype="multipart/form-data" role="form"> -->
                        {{ csrf_field() }}
						<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('change-password-send') }}">
						<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('change-password') }}">

                        <div class="col-12">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" class="form-control" name="password2" id="password2" placeholder="" required>
                            </div>
                        </div>
                    
                    </form>
                    <p align="right"><a href="javascript:void(0)" class="btn btn-primary" onclick="checkPassword()">Submit</a></p>
					</div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>


@endsection

@section('JS')
<script type="text/javascript">
    function checkPassword()
    {
        var error = false;
        var error_msg = "";


        if($("#password").val() != $("#password2").val())
        {
            error_msg += "Password does not match<br/>";
            error = true;
        }

        if($("#password").val().length < 8)
        {
            error_msg += "Password length must be 8 or higher<br/>";
            error = true;
        }

        if(error)
        {
            swal.fire("Error!", error_msg, "warning");
        }
        else
        {
            $("#frm2").submit();
        }
    }
</script>
@endsection