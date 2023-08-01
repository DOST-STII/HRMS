@extends('templates.master')

@section('CSS')
@endsection

@section('content')
<div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Classification</h4>
                    <div id="chart-class" style="height:100%"></div>
                </div>
              </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Division</h4>
                  <div id="chart-class-division"></div>                                            
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Administrative</h4>
                    <div id="chart-position-admin" style="height:100%;"></div>
                </div>
              </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Techinical</h4>
                    <div id="chart-position-tech" style="height:100%;"></div>
                </div>
              </div>
            </div>
          </div>
@endsection

@section('JS')
<script type="text/javascript">
$("#chart-position-admin").insertFusionCharts({
  type: "bar2d",
  width: "100%",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "",
      subcaption: "",
      xaxisname: "",
      yaxisname: "",
      numbersuffix: "",
      "palettecolors" : "9DAB86", 
      theme: "fusion"
    },
    data: [
      {
        label: "Venezuela",
        value: "290"
      },
      {
        label: "Saudi",
        value: "260"
      },
      {
        label: "Canada",
        value: "180"
      },
      {
        label: "Iran",
        value: "140"
      },
      {
        label: "Russia",
        value: "115"
      },
      {
        label: "UAE",
        value: "100"
      },
      {
        label: "US",
        value: "30"
      },
      {
        label: "China",
        value: "30"
      }
    ]
  }
});

$("#chart-position-tech").insertFusionCharts({
  type: "bar2d",
  width: "100%",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "",
      subcaption: "",
      xaxisname: "",
      yaxisname: "",
      numbersuffix: "",
      "palettecolors" : "CC7351", 
      theme: "fusion"
    },
    data: [
      {
        label: "Venezuela",
        value: "290"
      },
      {
        label: "Saudi",
        value: "260"
      },
      {
        label: "Canada",
        value: "180"
      },
      {
        label: "Iran",
        value: "140"
      },
      {
        label: "Russia",
        value: "115"
      },
      {
        label: "UAE",
        value: "100"
      },
      {
        label: "US",
        value: "30"
      },
      {
        label: "China",
        value: "30"
      }
    ]
  }
});

$("#chart-class").insertFusionCharts({
  type: "doughnut2d",
  dataFormat: "json",
  dataSource: {
    chart: {
      "palettecolors" : "CC7351,9DAB86", 
      caption: "",
      subcaption: "",
      showpercentvalues: "1",
      defaultcenterlabel: "",
      aligncaptionwithcanvas: "0",
      captionpadding: "0",
      decimals: "1",
      plottooltext:
        "<b>$percentValue</b>",
      centerlabel: "",
      theme: "fusion",
      "pieRadius": "93",
      "labelFontSize": "11",
    },
    data: [
      {
        label: "Administrative",
        value: "1000"
      },
      {
        label: "Technical",
        value: "5300"
      }
    ]
  }
});

$("#chart-class-division").insertFusionCharts({
  type: "stackedcolumn2d",
  width: "100%",
  height: "70%",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "",
      subcaption: "",
      numbersuffix: "",
      showsum: "1",
      plottooltext:
        "<b>$dataValue</b>",
      theme: "fusion",
      drawcrossline: "1"
    },
    categories: [
      {
        category: [
          {
            label: "Canada"
          },
          {
            label: "China"
          },
          {
            label: "Russia"
          },
          {
            label: "Australia"
          },
          {
            label: "United States"
          },
          {
            label: "France"
          }
        ]
      }
    ],
    dataset: [
      {
        seriesname: "Administrative",
        data: [
          {
            value: "400"
          },
          {
            value: "830"
          },
          {
            value: "500"
          },
          {
            value: "420"
          },
          {
            value: "790"
          },
          {
            value: "380"
          }
        ]
      },
      {
        seriesname: "Technical",
        data: [
          {
            value: "350"
          },
          {
            value: "620"
          },
          {
            value: "410"
          },
          {
            value: "370"
          },
          {
            value: "720"
          },
          {
            value: "310"
          }
        ]
      }
    ]
  }
});

</script>
@endsection

