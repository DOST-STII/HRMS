@extends('template.master')

@section('CSS')

@endsection

@section('content')
<!--  -->
<h1>Learning and Development</h1>
<div class="row">
  <div class="col-3">
    <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Training</span>
                <span class="info-box-number">-</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
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
          label               : 'Personal',
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