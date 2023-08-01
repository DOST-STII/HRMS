<!doctype html>
<html lang="en">
  <head>
    <title>HRMS | Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Lato|Trirong" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('login-page/images/logo.png') }}">


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <link rel="stylesheet" href="{{ asset('login-page/css/style.css') }}">

  </head>
  <body class="img js-fullheight" style="background-image: url({{ asset('login-page/images/22.jpeg') }});">
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
          <img src="{{ asset('login-page/images/logo.png') }}" width='15%' style="padding-bottom: 20px;">
          <h1 class="heading-section" style="color: #E0E1E0;"><b>DOST-STII</h1>
            <h1 style='font-family: "Trirong"; line-height: 1.2;color: #70E196; text-shadow: 2px 2px 8px #000;'>Human Resource Management System<br/>(HRMS)</h1>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <div class="login-wrap p-0">

            <form action="{{ route('login') }}" method="post">
            @csrf
              <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="off">
              </div>
              @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

              <div class="form-group">
                <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
              <div class="form-group">
                <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
              </div>
              <div class="form-group d-md-flex">
                <div class="w-50">
                  <label class="checkbox-wrap checkbox-primary">Remember Me
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="w-50 text-md-right">
                  <a href="#" style="color: #fff">Forgot Password</a>
                </div>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ asset('login-page/js/jquery.min.js') }}"></script>
  <script src="{{ asset('login-page/js/popper.js') }}"></script>
  <script src="{{ asset('login-page/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('login-page/js/main.js') }}"></script>

  </body>
</html>

