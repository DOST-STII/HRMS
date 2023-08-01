<!DOCTYPE html>
<html>
<head>
  <title>WORKFORCE PERCENTAGE</title>
</head>
<link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}">
<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-3"></div>
      <div class="col-lg-4 col-md-6 col-sm-12">
        <br>
        <br>
        <center>
            <h3>WORKFORCE PERCENTAGE<br><br>
              <small>
                  <small>
                    <input type="date" name="datedtr" id="datedtr" class="form-control" value="{{ date('Y-m-d',strtotime($data['datedtr'])) }}">
                  </small>
                </small>
            </h3>
        <p class="badge badge-primary" style="font-size: 100px">{{ $data['percent'] }}%</p>
        <p>Regular : <b>{{ $data['total_reg'] }}</b><br>ICOS : <b>{{ $data['total_icos'] }}</b></p>
        </center>
      </div>
      <div class="col-lg-4 col-md-3"></div>
    </div>
  </div>
</body>
<script src="{{ asset('bootstrap4/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap4/js/bootstrap.bundle.js') }}"></script>
<script type="text/javascript">
  $("#datedtr").change(function(){
    window.location.href = "{{ url('monitor-dtr/EzUt1cg19i') }}/" + this.value;
  })
</script>
</html>