@extends('template.master')

@section('CSS')
<!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.css') }}">
@endsection

@section('content')
         @include('pis.admin.info-boxes')

<center><h1>Division : {{ getDivision($data['division']) }}</h1></center>
<div class="row">
  <div class="col-md-3">
    <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Position Classification</h3>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
            <!-- /.card -->
  </div>
  <div class="col-md-7">
      <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Administrative</h3>
              </div>
              <div class="card-body">
                <canvas id="AdminCanvas"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
    <br>
    <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Technical</h3>
              </div>
              <div class="card-body">
                  <canvas id="TechnicalCanvas"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
  </div>
  <div class="col-md-2">
    <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Division</h3>
              </div>
              <div class="card-body">
                <div class="btn-group-vertical" style="width: 100%">
                        <a href="{{ url('dashboard-employee/ALL') }}" class="btn btn-default"><span class="float-left">Select All</span></a>
                        @foreach(getAllDivision() AS $divisions)
                            <a href="{{ url('dashboard-employee/'.$divisions->division_id) }}" class="btn btn-default"><span class="float-left">{{ $divisions->division_acro }}</span></a>
                        @endforeach
                </div>
              </div>
              
    </div>
            <!-- /.card -->
  </div>
</div>
@endsection

@section('JS')

<!-- Sparkline -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- ChartJS -->
<script src="{{ asset('AdminLTE-3.0.2/plugins/chart.js/Chart.min.js') }}"></script>


<script>

    var donutData = {
      labels: [
          'Administrative', 
          'Technical',
          'Vacant',
      ],
      datasets: [
        {
          data: [{{ totalAdmin($data['division']) }},{{ totalTechnical($data['division']) }},{{ totalVacant($data['division']) }}],
          backgroundColor : ['#f39c12', '#00c0ef','#77dd77'],
        }
      ]
    }

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
      onClick: graphClickEvent,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions    
    });

function graphClickEvent(event, array){
    window.open("{{ url('position-classification').'/'.$data['division'] }}/"+array[0]['_model'].label, '_blank');
    // console.log(array)
}
function graphClickEvent2(event, array){
    window.open("{{ url('position-description').'/'.$data['division'] }}/"+array[0]['_model'].label, '_blank');
    // console.log(array)
}
</script>

<script type="text/javascript">
  var MeSeContext = document.getElementById("AdminCanvas").getContext("2d");
var MeSeData = {
    labels: [
        <?php
              foreach (totalBarAdmin($data['division']) as $value) {
                # code...
                    echo "'".$value->position_desc."',";
              }
        ?>
    ],
    datasets: [{
        label: "Total",
        data: [
                   <?php
              foreach (totalBarAdmin($data['division']) as $value) {
                # code...
                    echo $value->total.",";
              }?>
              ],
        backgroundColor: [
              <?php
              foreach (totalBarAdmin($data['division']) as $value) {
                # code...
                    echo "'#".getColor()."',";
              }?>
                        ],
    }]
};

var MeSeChart = new Chart(MeSeContext, {
    type: 'horizontalBar',
    data: MeSeData,
    options: {
        legend: {
      display: false
    },
        scales: {
            xAxes: [{
                ticks: {
                 beginAtZero: true,
                 userCallback: function(label, index, labels) {
                     // when the floored value is the same as the value we have a whole number
                     if (Math.floor(label) === label) {
                         return label;
                     }

                 },
             }
            }],
            yAxes: [{
              stacked: true
            }]
        },
      onClick: graphClickEvent2,
    }
});
</script>

<script type="text/javascript">
  var MeSeContext = document.getElementById("TechnicalCanvas").getContext("2d");
var MeSeData = {
    labels: [
        <?php
              foreach (totalBarTechnical($data['division']) as $value) {
                # code...
                    echo "'".$value->position_desc."',";
              }
        ?>
    ],
    datasets: [{
        label: "Total",
        data: [
                   <?php
              foreach (totalBarTechnical($data['division']) as $value) {
                # code...
                    echo $value->total.",";
              }?>
              ],
        backgroundColor: [
              <?php
              foreach (totalBarTechnical($data['division']) as $value) {
                # code...
                    echo "'#".getColor()."',";
              }?>
                        ],
    }]
};

var MeSeChart = new Chart(MeSeContext, {
    type: 'horizontalBar',
    data: MeSeData,
    options: {
      legend: {
      display: false
    },
        scales: {
            xAxes: [{
                ticks: {
                 beginAtZero: true,
                 userCallback: function(label, index, labels) {
                     // when the floored value is the same as the value we have a whole number
                     if (Math.floor(label) === label) {
                         return label;
                     }

                 },
             }
            }],
            yAxes: [{
              stacked: true
            }]
        },
      onClick: graphClickEvent2,
    }
});
</script>
@endsection