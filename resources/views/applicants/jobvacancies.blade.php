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
        }
      body 
          {
                background: rgba(0, 0, 0, 0.5);
                height: 100%;
                background-position: 0px;
          }
    .bg
    {
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0px;
        background: rgba(0, 0, 0, 0.5);
    }
</style>
<body>
    <!-- <div class="bg"></div> -->
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
                <h5 class="card-title"><center>JOB VACANCIES</center></h5>
                <p class="card-text">
                  <center></center>
                  <div class="row">
                      <ul>
                      	@foreach($job AS $jobs)
                      		<li>
                      			<a href="{{ url('apply/'.$jobs->request_for_hiring_id.'/'.$jobs->plantilla_item_number) }}" style="text-decoration: none">{{ $jobs->position_desc }}</a>
                      			<br><span>Divsion: <b>{{ $jobs->division_acro }}</b></span>
                            <br><span>Salary Grade: <b>P{{ formatNumber('currency',$jobs->plantilla_salary) }}</b></span>
                      		</li>
                      	@endforeach
                      </ul>
                  </div>
              </p>
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
</html>