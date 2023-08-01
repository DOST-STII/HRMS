@extends('template.master')

@section('CSS')

@endsection

@section('content')
<div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">EMPLOYEE DTR</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              

                <div class="row">
                  
                  <div class="col-lg-6 c-ol-sm-12">
                    <select class="form-control" name="mon" id="dtr_mon">
                              <option selected value='1'>January</option>
                              <option value='2'>February</option>
                              <option value='3'>March</option>
                              <option value='4'>April</option>
                              <option value='5'>May</option>
                              <option value='6'>June</option>
                              <option value='7'>July</option>
                              <option value='8'>August</option>
                              <option value='9'>September</option>
                              <option value='10'>October</option>
                              <option value='11'>November</option>
                              <option value='12'>December</option>
                            </select>
                  </div>
                  <div class="col-lg-6 c-ol-sm-12">
                      <select class="form-control" name="yr" id="dtr_year">
                      <?php
                        for ($i = date('Y'); $i >= (date('Y') - 5) ; $i--) { 
                            echo "<option value='$i'>".$i."</option>";
                        }
                      ?>
                    </select>
                    <br>
                    <p align="right">
                    <button class="btn btn-primary" onclick="showDTR()">Submit</button>
                  </p>
                  </div>
                  
                </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>


@endsection

@section('JS')
<script type="text/javascript">
  $(document).ready(function(){
    
    $("#dtr_mon").val("{{ date('m') }}");
    $("#dtr_year").val({{ date('Y') }});


  });

  function showDTR()
  {
    $("#overlay").show();
    window.location.href = "{{ url('dtr/emp') }}/"+ $("#dtr_mon").val() + "/" + $("#dtr_year").val();
  }
</script>

@endsection