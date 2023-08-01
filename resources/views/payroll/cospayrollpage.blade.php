@extends('template.master')

@section('CSS')
<link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

<div class="row">
  <div class="col-lg-6">
    <!-- STACKED BAR CHART -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title"><b>PAYROLL</b></h3>
                <div class="card-tools">
                  
                </div>
              </div>
              <div class="card-body">
              <table class="table" id="tbl">
                    <thead>
                      <th>Period</th>
                      <th class="text-right">Net</th>
                      <th style="width:10%"></th>
                    </thead>
                    <tbody>
                      @foreach(getPayrollCOS() AS $key => $list)
                        <tr>
                          <td>{{ $list["period_text"] }}</td>
                          <td align="right">{{ formatCash($list["net"]) }}</td>
                          <td align="center"><button class="btn btn-primary btn-sm" onclick="printPayroll({{$list['mon']}},{{$list['yr']}},{{$list['period']}})"><i class="fas fa-print"></button></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
              <!-- /.card-body -->
              </form>
              
              <div class="card-footer">
              </div>
              
            </div>
            <!-- /.card -->
  </div>
</div>

<form method="POST" id="frm_payroll" enctype="multipart/form-data" role="form" action="{{ url('payroll/cos-print') }}" target="_blank">
  {{ csrf_field() }}
  <input type="hidden" name="mon" id="mon">
  <input type="hidden" name="yr" id="yr">
  <input type="hidden" name="period" id="period">
</form>
@endsection

@section('JS')
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('AdminLTE-3.0.2/plugins-new/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>
  $("#tbl").DataTable();

  function printPayroll(mon,yr,period)
  {
    $("#mon").val(mon);
    $("#yr").val(yr);
    $("#period").val(period);

    $("#frm_payroll").submit();
  }
</script>
@endsection