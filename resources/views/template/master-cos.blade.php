<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Human Resource Management System</title>
 
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('fontawesome-free-5.12.1-web/css/all.min.css') }}">

  <!-- DataTables -->
   <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!-- IonIcons -->
  <link rel="stylesheet" href="{{ asset('ionicons-master/docs/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">

  <!--=====================SWEET ALERT 2===========================================================-->    
    <link rel="stylesheet" href="{{ asset('sweetalert2-8.5.0/src/sweetalert2.scss') }}">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->

  <style type="text/css">
    #overlay {
      position: fixed;
      display: none;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0,0,0,0.5);
      z-index: 9998;
      cursor: pointer;
    }

    .spinner {
   width: 300px;
   height: 300px;
   position: absolute;
   left: 50%;
   top: 50%; 
   margin-left: -50px;
   margin-top: -100px;
   z-index: 9999;
}
  </style>

  @yield('CSS')
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div id="overlay"><i class="fas fa-spinner fa-spin spinner fa-3x" style="color: #FFF"></i></div>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-blue navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm" style="color:#FFF">
        <!-- <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div> -->
        <strong><span>{{ Auth::user()->fname.' '.Auth::user()->lname }}</span></strong>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell fa-lg"></i>
          <span class="badge badge-danger navbar-badge" id='total_notification'></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><span id="total_notification_sub"></span> Notifications</span>

          <div id="notification_body">
            
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="btn btn-danger btn-sm" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fas fa-power-off"></i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('AdminLTE-3.0.2/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">HRMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      @if(isset(Auth::user()->name))
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('AdminLTE-3.0.2/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            {{ Auth::user()->name }}
          </a>
        </div>
      </div>
      @endif

      @include('template.sidenav-staff-icos')

    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v3</li>
            </ol> -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        @yield('content')
        
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 DOST-PCAARRD <a href="#">HRMS</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script src="{{ asset('fontawesome-free-5.12.1-web/js/all.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/select2/js/select2.full.min.js') }}"></script>

<script src="{{ asset('ionicons-master/docs/js/ionicons.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  

<!-- jQuery UI -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('AdminLTE-3.0.2/dist/js/adminlte.js') }}"></script>

<script src="{{ asset('AdminLTE-3.0.2/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script src="{{ asset('sweetalert2-8.5.0/src/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('js/post-ajax.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
    bsCustomFileInput.init();
    });

    $(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
          tags: true,
          tokenSeparators: [',', ' ']
      })

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    // //Datemask dd/mm/yyyy
    // $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    // //Datemask2 mm/dd/yyyy
    // $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    // //Money Euro
    // $('[data-mask]').inputmask()

    // //Date range picker
    // $('#reservation').daterangepicker()
    // //Date range picker with time picker
    // $('#reservationtime').daterangepicker({
    //   timePicker: true,
    //   timePickerIncrement: 30,
    //   locale: {
    //     format: 'MM/DD/YYYY hh:mm A'
    //   }
    // });

    })
</script>

@yield('JS')

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/dist/js/demo.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/dist/js/pages/dashboard3.js') }}"></script> -->

@include('notifications.index')

</body>
</html>
