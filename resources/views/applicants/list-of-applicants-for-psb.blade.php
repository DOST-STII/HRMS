<!DOCTYPE html>
<html>
<head>
    <title>DOST-PCAARD HMRIS</title>
    <link href="{{ asset('application/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all">
    <link href="{{ asset('AdminLTE-3.0.2/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-5.12.1-web/css/all.min.css') }}">
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
                background: rgba(0, 0, 0, 0.0);
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
    <div class="bg"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12" style="margin-top: 1%;">
            <div class="card" style="padding: 1%">
              <center><img
                src="{{ asset('application/img/logo.png') }}"
                class="card-img-top"
                alt="..."
                style="width: 10%;"
              /></center>
              <div class="card-body">
                <h5 class="card-title"><center>List of Applicants</center></h5>
                <span>POSITION : <b>{{ $data['info']['position_desc'] }}</b></span><br/>
                <span>DIVISION : <b>{{ $data['info']['division_acro'] }}</b></span><br/>
                <span>ITEM NUMBER : <b>{{ $data['info']['plantilla_item_number'] }}</b></span><br/>

                <table class="table">
                  <thead>
                    <th>#</th>
                    <th width="20%">Name</th>
                    <th>Application Letter</th>
                    <th>CV</th>
                    <th>Trainings</th>
                    <th>Service Records</th>
                    <th>Photo</th>
                    <th>Evaluation</th>
                    <th>Psycho Social Result</th>
                  </thead>
                  <tbody>
                    <?php $ctr = 1; ?>
                      @foreach($data['list'] AS $lists)
                        <tr>
                          <td>{{ $ctr }}</td>
                          <td>{{ $lists->lname.', '.$lists->fname. ' '.$lists->mname }}</td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_appletter ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_cv ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_trainingcert ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_servicerecords ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_photo ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_evaluationcert ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                          <td class="text-center"><a href="{{ asset('../storage/app/'.$lists->file_psycho ) }}" target="_blank"><i class="fas fa-paperclip"></i></a></td>
                        </tr>
                      <?php $ctr++; ?>
                      @endforeach
                  </tbody>
                </table>
              </div>


              <div class="card-footer bg-white text-muted">
                <small><strong>Copyright &copy; 2020 <a href="http://www.pcaarrd.dost.gov.ph/home/portal/" style="text-decoration: none" target="_blank">DOST-PCAARRD</a></strong> All rights reserved.</small>
              </div>

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