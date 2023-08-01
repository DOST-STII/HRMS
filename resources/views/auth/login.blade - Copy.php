
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HRMS | Log In</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/css/ionicons.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style type="text/css">
    .bg
    {
      background-image: url("{{ asset('img/login-background3.jpg') }}");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center center;
      background-attachment: fixed;
    }

  </style>
</head>
<body class="hold-transition login-page bg">
<div class="login-box">
  <div class="login-logo" style="line-height: 80% !important">
    <a href="{{ url('/') }}" style="color: #FFF;font-weight: bold;font-size: 35px;"><b>Human Resource Management System</b></a>

  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="border-radius: 5px">
      <h5 class="text-center" style="font-size: 18px;margin: 2%"><small>The Human Resources Management System <b>(HRMS)</b> allows for the storage of personnel data, and management of  attendance, payroll, recruitment, benefits, employee performance, and tracking of competencies and training records.</small></h5>
      <br>
      <span><center><b>SIGN-IN TO YOUR ACCOUNT</b></center></span>
      <br>
      <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE-3.0.2/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
