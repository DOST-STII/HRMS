<!DOCTYPE html>
<html>
<head>
    <title>DOST-PCAARD HMRIS | JOB APPLICATION</title>
    <link href="{{ asset('application/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('AdminLTE-3.0.2/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
</head>
<style type="text/css">
    html {
              /* The image used */
              background-image: url("{{ asset('application/img/21.jpg') }}");
              height: 100%;
              background-repeat: no-repeat;
              background-size: cover;
              background-position: center center;
              background-attachment: fixed;
        }
      body 
          {
                background: rgba(0, 0, 0, 0.5);
                /*height: 100%;*/
                background-position: 0px;
          }
    .bg2
    {
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0px;
        background: rgba(0, 0, 0, 0.5);
    }
</style>
<body>
      
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card" style="padding: 2%">
              <center><img
                src="{{ asset('application/img/logo.png') }}"
                class="card-img-top"
                alt="..."
                style="width: 10%;"
              /></center>
              <div class="card-body">
                <h5 class="card-title"><center>JOB APPLICATION</center></h5>
                <form method="POST" id="frm_applicant" enctype="multipart/form-data" action="{{ url('send-application') }}" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="itemcode" value="{{ $data['code'] }}">
                <input type="hidden" name="request_id" value="{{ $data['request_id'] }}">
                <p class="card-text">
                  <center></center>
                  <div class="row">
                      <div class="col-md-12 alert alert-primary">
                        <b>POSITION</b> : {{ $data['info']['position_desc'] }}<br>
                        <b>SALARY</b> : P{{ formatNumber('currency',$data['info']['plantilla_salary']) }}<br>
                        <b>VACANCY ADVISE</b> : <a href="{{ getFileForApplicant($data['info']['id']) }}" target="_blank"><i>click here to view</i></a>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-4">
                        <span><b>Last Name <span class="text-danger">*</span></b></span>
                        <input type="text" name="lname" class="form-control" required>
                      </div>
                      <div class="col-md-4">
                        <span><b>First Name <span class="text-danger">*</span></b></span>
                        <input type="text" name="fname" class="form-control" required>
                      </div>
                      <div class="col-md-4">
                        <span><b>Middle Name <span class="text-danger">*</span></b></span>
                        <input type="text" name="mname" class="form-control" required>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-12">
                        <span><b>Contact No. <span class="text-danger">*</span></b></span>
                        <input type="email" name="contactnum" class="form-control" required>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-12">
                        <span><b>Email <span class="text-danger">*</span></b></span>
                        <input type="email" name="email" class="form-control" required>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-6">
                        <span><b>Application Letter<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="appletter" required>
                      </div>
                      <div class="col-md-6">
                        <span><b>Training Certificates<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="trainingcert" required>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-6">
                        <span><b>Upload CV <span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="cv" required>
                      </div>
                      <div class="col-md-6">
                        <span><b>Service Record/Employment Certificate<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="servicerecord" required>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-6">
                        <span><b>2x2 Picture<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="photo" required>
                      </div>
                      <div class="col-md-6">
                        <span><b>Performance Evaluation Report<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="evaluationcert" required>
                      </div>
                      
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-6">
                        <span><b>Certificate of Eligibility<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="cs" required>
                      </div>
                      <div class="col-md-6">
                        <span><b>TOR<span class="text-danger">*</span></b></span>
                        <input type="file" class="form-control" name="tor" required>
                      </div>
                  </div>
                  
                </p>

                <p align="left">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="emailMe" id="emailMe" value="Yes">
                      <label class="form-check-label" for="emailMe">
                        Do you want to receive email for future job vacancies?
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="Yes" name="privacycheck" id="privacycheck">
                      <label class="form-check-label" for="privacycheck">
                        I have read and agree to the terms and <a href="{{ asset('application/files/PrivacyNotice.pdf') }}" style="text-decoration: none" target="_blank">privacy policy</a> of DOST-PCAARRD 
                      </label>
                    </div>
                </p>
                <br>
                </form>
                <button type="button" class="btn btn-primary" id="btnSubmit" onclick="submitFrm()" disabled>Submit Application</button>
              </div>

              <div class="card-footer bg-white text-muted">
                <small><strong>Copyright &copy; 2020 <a href="http://www.pcaarrd.dost.gov.ph/home/portal/" style="text-decoration: none" target="_blank">DOST-PCAARRD</a></strong> All rights reserved.</small>
              </div>
            </div>
        </div>
<!-- <a href='https://www.freepik.com/free-photos-vectors/background'>Background photo created by osaba - www.freepik.com</a> -->
</body>

<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('application/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="text/javascript">
    $('#privacycheck').change(function(){
        $("#btnSubmit").prop("disabled",true);
        if($(this).is(':checked'))
        {
            $("#btnSubmit").prop("disabled",false);
        }
    })

    function submitFrm()
    {
        Swal.fire({
          title: 'Notice!',
          text: "By providing the required information, I am giving my consent to the collection, use, and disclosure of my personal data in accordance to DOST-PCAARRD's Data Privacy Policy.",
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
</script>
</html>