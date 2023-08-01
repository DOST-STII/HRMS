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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <div class="float-right">
              Filter Division : <select class="form-control" id="selectdivision">
                <option value="ALL">All Division</option>
                @foreach(getAllDivision() AS $divisions)
                    <option value="{{ $divisions->division_id }}">{{ $divisions->division_acro }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<!-- <h5>Division selected : {{ getDivision($data['division']) }}</h5> -->

<div class="row">
  <div class="col-md-4">
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
  <div class="col-md-4">
      <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Administrative</h3>
              </div>
              <div class="card-body">
                <canvas id="AdminCanvas" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
  </div>
  <div class="col-md-4">
    <!-- <div class="card card-primary">
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
              
    </div> -->
            <!-- /.card -->
                <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Technical</h3>
              </div>
              <div class="card-body">
                  <canvas id="TechnicalCanvas" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
  </div>
</div>

<div class="row" style="display: none">
  <div class="col-md-4">
    <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Education</h3>
              </div>
              <div class="card-body">
                <canvas id="pieChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
    </div>
            <!-- /.card -->
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title"><b>Trainings</b></h3>
                <h3 class="card-title float-right">Total Investment : <b>P 1,000,000</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
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

  //ON CHANGE DIVISION
  $("#selectdivision").change(function(){
      window.location.href = "{{ url('admin/dashboard') }}/" + this.value;
  });
  $("#selectdivision").val("{{ $data['division'] }}");

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

<script type="text/javascript">
  
    var donutData = {
      labels: [
          'MS',
          'PhD',
          'BS',
          'Others'
      ],
      datasets: [
        {
          data: [{{ totalMS($data['division']) }},{{ totalPHD($data['division']) }},{{ totalBS($data['division']) }},{{ totalOTHERS($data['division']) }}],
          backgroundColor : ['#f39c12', '#00c0ef', '#02a61a' ,'#555'],
        }
      ]
    }

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart2').get(0).getContext('2d')
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
</script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
  // var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['ACD', 'ARMRD', 'CRD', 'FAD-Accounting', 'FAD-Budget', 'FAD-Cash', 'FAD-DO','FAD-GSS','FAD-Personnel','FAD-Property','FAD-Records','FERD','IARRD','IDD','LRD','MISD','MRRD','ODED-ARMSS','ODED-RD','OED','PCMD','SERD','TTPD'],
      datasets: [
        {
          label               : 'Free',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
                                  @foreach($data['total_trainings'] as $trainings)
                                      {{ $trainings->total_training_free.',' }}
                                  @endforeach
                                ]
        },
        {
          label               : 'Funded',
          backgroundColor     : 'rgba(171, 0, 0, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [
                                  @foreach($data['total_trainings'] as $trainings)
                                      {{ $trainings->total_training_funded.',' }}
                                  @endforeach
                                ]
        },

        {
          label               : 'Self Finance',
          backgroundColor     : 'rgba(0, 163, 54, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0,0,0]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }


    //-------------
    //- BAR CHART -
    //-------------
    // var barChartCanvas = $('#barChart').get(0).getContext('2d')
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

    // var barChart = new Chart(barChartCanvas, {
    //   type: 'bar', 
    //   data: barChartData,
    //   options: barChartOptions
    // })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      },
      onClick: graphClickEvent,
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  });

function graphClickEvent(event, array)
{
  // console.log(array[0]['_model'].label)
  window.open("{{ url('trainings-list') }}/"+array[0]['_model'].label, '_blank');
}
</script>
@endsection