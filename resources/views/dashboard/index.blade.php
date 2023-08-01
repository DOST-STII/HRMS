<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>HRMS | Dashboard</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="#" class="navbar-brand">
        <img src="{{ asset('AdminLTE-3.0.2/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">HRMS DASHBOARD</span>
      </a>
      
      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
        </ul>

        
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1 class="m-0 text-dark"> Top Navigation <small>Example 3.0</small></h1> -->
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <section class="content">
      <div class="container-fluid">

        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Regular Staff</span>
                <span class="info-box-number">
                  {{ $data['total_staff'] }}
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Technical</span>
                <span class="info-box-number">150</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Admin</span>
                <span class="info-box-number">55</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Vacant</span>
                <span class="info-box-number">{{ $data['total_retiree'] }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-9">
             <!-- solid sales graph -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Trainings
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ICOS/JOs</span>
                <span class="info-box-number">110</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>

        <div class="row">
          <div class="col-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">SEX</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="chart-responsive">
                      <canvas id="pieChart" height="150"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle text-primary"></i> Male
                      <span class="float-right text-success">
                        {{ $data['total_male'] }}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle text-danger"></i> Female
                      <span class="float-right text-success">
                        {{ $data['total_female'] }}
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
          </div>
          <div class="col-8">
            
            <!-- BAR CHART -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Age Group</h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" height="230"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <!-- Anything you want -->
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="https://adminlte.io">DOST-PCAARRD HRMS</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE-3.0.2/dist/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('AdminLTE-3.0.2/dist/js/demo.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>

<!-- PAGE SCRIPTS -->
<!-- <script src="{{ asset('AdminLTE-3.0.2/dist/js/pages/dashboard2.js') }}"></script> -->
<script type="text/javascript">
    //-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = {
      labels: [
          'Female', 
          'Male',
      ],
      datasets: [
        {
          data: [{{ $data['total_female'] }},{{ $data['total_male'] }}],
          backgroundColor : ['#f56954', '#00c0ef'],
        }
      ]
    }
    var pieOptions     = {
      legend: {
        display: false
      }
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: pieData,
      options: pieOptions      
    })

  //-----------------
  //- END PIE CHART -
  //-----------------


      //-------------
    //- BAR CHART -
    //-------------

    var areaChartData = {
      labels  : ['20 and below','21 to 30', '31 to 40', '41 to 50', '51 to 60', 'Above 60'],
      datasets: [
        {
          label               : 'Female',
          backgroundColor     : '#f56954',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [{{ $data['age_20_below_female'] }}, {{ $data['age_21_30_female'] }}, {{ $data['age_31_40_female'] }}, {{ $data['age_41_50_female'] }}, {{ $data['age_51_60_female'] }}, {{ $data['age_above_60_female'] }}]
        },
        {
          label               : 'Male',
          backgroundColor     : '#00c0ef',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#f56954',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ $data['age_20_below_male'] }}, {{ $data['age_21_30_male'] }}, {{ $data['age_31_40_male'] }}, {{ $data['age_41_50_male'] }}, {{ $data['age_51_60_male'] }}, {{ $data['age_above_60_male'] }}]
        },
      ]
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    });


  // Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
  //$('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels  : ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
    datasets: [
      {
        label               : 'Digital Goods',
        fill                : false,
        borderWidth         : 2,
        lineTension         : 0,
        spanGaps : true,
        borderColor         : '#efefef',
        pointRadius         : 3,
        pointHoverRadius    : 7,
        pointColor          : '#efefef',
        pointBackgroundColor: '#efefef',
        data                : [2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
      }
    ]
  }

  var salesGraphChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: false,
    },
    scales: {
      xAxes: [{
        ticks : {
          fontColor: '#efefef',
        },
        gridLines : {
          display : false,
          color: '#efefef',
          drawBorder: false,
        }
      }],
      yAxes: [{
        ticks : {
          stepSize: 5000,
          fontColor: '#efefef',
        },
        gridLines : {
          display : true,
          color: '#efefef',
          drawBorder: false,
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  var salesGraphChart = new Chart(salesGraphChartCanvas, { 
      type: 'line', 
      data: salesGraphChartData, 
      options: salesGraphChartOptions
    }
  )
</script>
</body>
</html>
