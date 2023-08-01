@extends('templates.master')

@section('CSS')
@endsection

@section('content')

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Training per Division</h4>
                    <div id="chart-age-group" style="height:100%;"></div>
                </div>
              </div>
            </div>
          </div>
@endsection

@section('JS')
<script type="text/javascript">
$("#chart-age-group").insertFusionCharts({
  type: "stackedcolumn2d",
  width: "100%",
  dataFormat: "json",
  dataSource: {
    chart: {
      caption: "",
      subcaption: "",
      numbersuffix: "",
      "palettecolors" : "046582,C490E4", 
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
            label: "Below 20"
          },
          {
            label: "21-30"
          },
          {
            label: "31-40"
          },
          {
            label: "41-50"
          },
          {
            label: "51-60"
          },
          {
            label: "60 and above"
          }
        ]
      }
    ],
    dataset: [
      {
        seriesname: "Male",
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
        seriesname: "Female",
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


$("#chart-sex").insertFusionCharts({
  type: "doughnut2d",
  width: "100%",
  dataFormat: "json",
  dataSource: {
    chart: {
      "palettecolors" : "046582,C490E4", 
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
      "pieRadius": "93"
    },
    data: [
      {
        label: "Male",
        value: "1000"
      },
      {
        label: "Female",
        value: "5300"
      }
    ]
  }
});
</script>
@endsection
