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
<!-- <form method="POST" id="frm2" enctype="multipart/form-data" action="{{ url('request/action') }}">  -->
<!-- <form method="POST" id="frm" enctype="multipart/form-data">

{{ csrf_field() }}
<input type="hidden" name="frm_url_action" id="frm_url_action" value="{{ url('request/action') }}">
<input type="hidden" name="frm_url_reset" id="frm_url_reset" value="{{ url('request-for-approval') }}"> -->

<div class="row">
  <div class="col-12">
      <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Leave Application</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Travel Order</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Overtime</a>
                  </li>


                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-home-tab2" data-toggle="pill" href="#custom-tabs-three-messages4" role="tab" aria-controls="custom-tabs-three-messages4" aria-selected="true">Approved</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-messages-tab2" data-toggle="pill" href="#custom-tabs-three-messages2" role="tab" aria-controls="custom-tabs-three-messages2" aria-selected="false">Cancelled</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-messages-tab2" data-toggle="pill" href="#custom-tabs-three-messages3" role="tab" aria-controls="custom-tabs-three-messages3" aria-selected="false">Disapproved</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-messages-tab5" data-toggle="pill" href="#custom-tabs-three-messages5" role="tab" aria-controls="custom-tabs-three-messages5" aria-selected="false">Processed</a>
                  </li>

                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                  <table id="tbl" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th style="width: 30%">Name</th>
                        <th>Type of Request</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <?php $i = 0; ?>
                        @foreach(getRequestForApprovalDirector() AS $lists)
                          
                          <tr>
                            <!-- <td>
                              <input type="checkbox" name="check_request[]" class="check" value="{{ $i }}">
                            </td> -->
                            <td><input type="hidden" name="userid[]" value="{{ $lists->user_id }}">{{ $lists->fname }} {{ $lists->lname }}</td>
                            <td><input type="hidden" name="requestid[]" value="{{ $lists->id }}">
                              <?php
                                  $lwop = "";
                                  if($lists->lwop == 'YES')
                                  {
                                    $lwop = "<small><span class='badge badge-danger'>LWOP</span></small>";
                                  }
                                  echo $lists->leave_desc." ".$lwop;
                              ?>
                            </td>
                            <td>
                              <input type="hidden" name="leavedates[]" value="{{ $lists->leave_date }}">
                              <input type="hidden" name="leaveid[]" value="{{ $lists->leave_id }}">
                              <input type="hidden" name="requestype[]" value="leave">
                              <?php

                                if($lists->leave_date_from == $lists->leave_date_to)
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from));
                                }
                                else
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from))." - ".date("M d, Y",strtotime($lists->leave_date_to));
                                }

                                echo $dt;
                              ?>
                            </td>
                            <td>
                            <?php
                                if($lists->leave_deduction > 1)
                                {
                                  echo $lists->leave_deduction . " days";
                                }
                                else
                                {
                                  echo $lists->leave_deduction_time;
                                }
                              ?>
                            </td>
                            <td></td>
                            <td align="center"><?php echo formatRequestStatus($lists->leave_action_status) ?></td>
                            <td>
                                  <center>
                                 @if($lists->leave_action_status == 'Pending')
                                    <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->leave_id }},'Approved')"></span>
                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                    @if($lists->leave_id == 6)
                                    <span class="fas fa-times-circle text-yellow" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Disapproved')"></span>
                                    @endif
                                  @elseif($lists->leave_action_status == 'Approved')

                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                  @elseif($lists->leave_action_status == 'Processed' || $lists->leave_action_status == 'Disapproved')
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>
                                  @elseif($lists->leave_action_status == 'Cancelled')

                                  @endif
                                </center>
                            </td>
                          </tr>
                          <?php $i++; ?>
                        @endforeach
                     <?php $i = 0; ?>
                        @foreach(getRequestForApproval() AS $lists)
                          <tr>
                            <!-- <td>
                              <input type="checkbox" name="check_request[]" class="check" value="{{ $i }}">
                            </td> -->
                            <td><input type="hidden" name="userid[]" value="{{ $lists->user_id }}">{{ $lists->user->lname }}, {{ $lists->user->fname }} {{ $lists->user->mname }}&nbsp;</td>
                            <td><input type="hidden" name="requestid[]" value="{{ $lists->id }}">
                              <?php
                                  $lwop = "";
                                  if($lists->lwop == 'YES')
                                  {
                                    $lwop = "<small><span class='badge badge-danger'>LWOP</span></small>";
                                  }
                                  echo $lists->leave_type->leave_desc ." ".$lwop;
                                ?>
                            </td>
                            <td>
                              <input type="hidden" name="leavedates[]" value="{{ $lists->leave_date }}">
                              <input type="hidden" name="leaveid[]" value="{{ $lists->leave_id }}">
                              <input type="hidden" name="requestype[]" value="leave">
                              <?php
                                if($lists->leave_date_from == $lists->leave_date_to)
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from));
                                }
                                else
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from))." - ".date("M d, Y",strtotime($lists->leave_date_to));
                                }

                                echo $dt;
                              ?>
                            </td>
                            <td>{{ $lists->leave_deduction_time }}</td>
                            <td></td>
                            <td align="center"><?php echo formatRequestStatus($lists->leave_action_status) ?></td>
                            <td>
                                  <center>
                                 @if($lists->leave_action_status == 'Pending')
                                    <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Approved')"></span>
                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                    @if($lists->leave_id == 6)
                                    <span class="fas fa-times-circle text-yellow" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Disapproved')"></span>
                                    @endif
                                  @elseif($lists->leave_action_status == 'Approved')

                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                  @elseif($lists->leave_action_status == 'Processed' || $lists->leave_action_status == 'Disapproved')
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>
                                  @elseif($lists->leave_action_status == 'Cancelled')

                                  @endif
                                </center>
                            </td>
                          </tr>
                          <?php $i++; ?>
                        @endforeach

                        <!-- IF 15 DAYS OR MORE -->
                        <?php $i = 0; ?>
                        @foreach(getRequestForApproval15() AS $lists)
                          <tr>
                            <!-- <td>
                              <input type="checkbox" name="check_request[]" class="check" value="{{ $i }}">
                            </td> -->
                            <td><input type="hidden" name="userid[]" value="{{ $lists->user_id }}">{{ $lists->fullname }}</td>
                            <td><input type="hidden" name="requestid[]" value="{{ $lists->id }}">
                              <?php
                                  $lwop = "";
                                  if($lists->lwop == 'YES')
                                  {
                                    $lwop = "<small><span class='badge badge-danger'>LWOP</span></small>";
                                  }
                                  echo $lists->leave_desc." ".$lwop;
                                ?>
                            </td>
                            <td>
                              <input type="hidden" name="leavedates[]" value="{{ $lists->leave_date }}">
                              <input type="hidden" name="leaveid[]" value="{{ $lists->leave_id }}">
                              <input type="hidden" name="requestype[]" value="leave">
                              <?php
                                if($lists->leave_date_from == $lists->leave_date_to)
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from));
                                }
                                else
                                {
                                  $dt = date("M d, Y",strtotime($lists->leave_date_from))." - ".date("M d, Y",strtotime($lists->leave_date_to));
                                }

                                echo $dt;
                              ?>
                            </td>
                            <td>{{ $lists->leave_deduction_time }}</td>
                            <td></td>
                            <td align="center"><?php echo formatRequestStatus($lists->leave_action_status) ?></td>
                            <td>
                                  <center>
                                 @if($lists->leave_action_status == 'Pending')
                                    <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->leave_id }},'Approved')"></span>
                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                    @if($lists->leave_id == 6)
                                    <span class="fas fa-times-circle text-yellow" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Disapproved')"></span>
                                    @endif
                                  @elseif($lists->leave_action_status == 'Approved')

                                    <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('leave',{{ $lists->id }},'Cancelled')"></span>
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>

                                  @elseif($lists->leave_action_status == 'Processed' || $lists->leave_action_status == 'Disapproved')
                                    <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('{{ $lists->leave_desc }}',{{ $lists->id }})"></span>
                                  @elseif($lists->leave_action_status == 'Cancelled')

                                  @endif
                                </center>
                            </td>
                          </tr>
                          <?php $i++; ?>
                        @endforeach
                  </table>

                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                     <table id="tbl2" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th style="width: 30%">Name</th>
                        <th>Per Diem</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                     <?php $i = 0; ?>
                        @foreach(getRequestForTOApproval() AS $lists)
                        <?php
                            $dt = date('M d,Y',strtotime($lists->to_date_from));
                            $tm = $lists->to_deduction_time;
                            if($lists->to_date_from != $lists->to_date_to)
                            {
                                $dt = date('M d,Y',strtotime($lists->to_date_from))."-".date('M d,Y',strtotime($lists->to_date_to));
                                $tm = round($lists->to_total_day)." days";
                            }

                            $remarks = $lists->to_place." ".$lists->to_purpose;
                          ?>
                          <tr>
                            <!-- <td>
                              <input type="checkbox" name="check_request[]" class="check" value="{{ $i }}">
                            </td> -->
                            <td><input type="hidden" name="userid[]" value="{{ $lists->userid }}">{{ $lists->employee_name }}</td>
                            <td><input type="hidden" name="requestid[]" value="{{ $lists->id }}">{{ $lists->to_perdiem }}</td>
                            <td>
                              <input type="hidden" name="leavedates[]" value="{{ $lists->to_date }}">
                              <input type="hidden" name="requestype[]" value="TO">
                              <?php
                                  echo $dt;
                              ?>
                            </td>
                            <td>{{ $tm }}</td>
                            <td>{{ $remarks }}</td>
                            @if(Auth::user()->usertype == 'Marshal')
                            <td align="center"><?php echo formatRequestStatus($lists->to_status) ?></td>
                            <td>
                              <center>
                                   @if($lists->to_status == 'Pending')
                                  <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="toAction('TO',{{ $lists->id }},'Approved')"></span>
                                  @if($lists->process_code == null)
                                  <span class="fas fa-edit text-info" style="cursor:pointer;" onclick="toEdit('TO',{{ $lists->id }})"></span>
                                  @endif
                                  <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('TO',{{ $lists->id }},'Cancelled')"></span>
                                  <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('Travel Order',{{ $lists->id }})"></span>
                                @elseif($lists->to_status == 'Approved')
                                  @if($lists->process_code == null)
                                  <span class="fas fa-edit text-info" style="cursor:pointer;" onclick="toEdit('TO',{{ $lists->id }})"></span>
                                  @endif
                                  <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('TO',{{ $lists->id }},'Cancelled')"></span>
                                  <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('Travel Order',{{ $lists->id }})"></span>

                                @elseif($lists->to_status == 'Cancelled')

                                @endif
                               </center>
                            </td>
                            @endif
                          </tr>
                          <?php $i++; ?>
                        @endforeach
                  </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                    <br>
                    <p align="right"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-batch-ot">Batch Print</button></p>
                     <table id="tbl3" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th style="width: 30%">Name</th>
                        <th>Type of Request</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Expected Output</th>
                        <th>CTO</th>
                        <th>Action</th>
                        <th></th>
                      </tr>
                      </thead>
                     <?php $i = 0; ?>
                        @foreach(getRequestForOTApproval() AS $lists)

                        @if($lists->ot_status != 'Cancelled')
                          <tr>
                            <!-- <td>
                              <input type="checkbox" name="check_request[]" class="check" value="{{ $i }}">
                            </td> -->
                            <td><input type="hidden" name="userid[]" value="{{ $lists->userid }}">{{ $lists->employee_name }}</td>
                            <td><input type="hidden" name="requestid[]" value="{{ $lists->id }}">Overtime</td>
                            <td>
                              <input type="hidden" name="leavedates[]" value="{{ $lists->ot_date }}">
                              <input type="hidden" name="requestype[]" value="OT">
                              <?php
                                  echo date("M d, Y",strtotime($lists->ot_date));
                              ?>
                            </td>
                            <td>{{ $lists->ot_purpose }}</td>
                            <td>{{ $lists->ot_output }}</td>
                            <td>{{ $lists->cto }}</td>
                            @if(Auth::user()->usertype == 'Marshal')
                            <td align="center"><?php echo formatRequestStatus($lists->ot_status) ?></td>

                            <td>
                              <center>
                                  @if($lists->ot_status == 'Pending')
                                        <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="modalOT({{ $lists->id }},'Approved','{{ $lists->cto }}','{{ $lists->ot_status }}','{{ $lists->director }}')"></span>
                                        <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('OT',{{ $lists->id }},'Cancelled')"></span>
                                        <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('Overtime',{{ $lists->id }})"></span>
                                  @elseif($lists->ot_status == 'Time Edited')
                                        <span class="fas fa-check-circle text-green" style="cursor:pointer;" onclick="toAction('OT',{{ $lists->id }},'Approved')"></span>
                                        <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('OT',{{ $lists->id }},'Cancelled')"></span>
                                        <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('Overtime',{{ $lists->id }})"></span>
                                      @elseif($lists->ot_status == 'Approved' || $lists->ot_status == 'OED Approved')
                                        <span class="fas fa-times-circle text-red" style="cursor:pointer;" onclick="toAction('OT',{{ $lists->id }},'Cancelled')"></span>
                                        <span class="fas fa-print text-blue" style="cursor:pointer;" onclick="toPrint('Overtime',{{ $lists->id }})"></span>

                                      @elseif($lists->ot_status == 'Cancelled')

                                      @endif
                              </center>
                            </td>


                            @endif
                          </tr>
                    <?php $i++; ?>
                    @endif
                  @endforeach
                  </table>
                  </div>

                  <div class="tab-pane fade" id="custom-tabs-three-messages2" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab2">
                    <br>
                     <table id="tbl3" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th>Name</th>
                        <th>Type of Request</th>
                        <th>Date</th>
                      </tr>
                      </thead>
                      <?php
                        foreach(getCancelled('leave',Auth::user()->division) AS $v2)
                        {
                          $user = App\User::where('username',$v2->empcode)->first();
                          $lv = App\Leave_type::where('id',$v2->leave_id)->first();
                          if($v2->leave_date_from == $v2->leave_date_to)
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from));
                          }
                          else
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from))." - ".date('M d, Y',strtotime($v2->leave_date_to));
                          }
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>".$lv['leave_desc']."</td><td>".$dt."</td></tr>";
                        }


                        foreach(getCancelled('to',Auth::user()->division) AS $v3)
                        {
                          $user = App\User::where('username',$v3->empcode)->first();
                          if($v3->to_date_from == $v2->to_date_to)
                          {
                            $dt2 = date('M d, Y',strtotime($v3->to_date_from));
                          }
                          else
                          {
                            $dt = date('M d, Y',strtotime($v3->to_date_from))." - ".date('M d, Y',strtotime($v3->to_date_to));
                          }
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>Travel Order</td><td>".$dt."</td></tr>";
                        }
                      ?>
                  </table>
                  </div>

                  <div class="tab-pane fade" id="custom-tabs-three-messages3" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab3">
                    <br>
                     <table id="tbl3" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th>Name</th>
                        <th>Type of Request</th>
                        <th>Date</th>
                        <th></th>
                      </tr>
                      </thead>
                      <?php
                        foreach(getDisapproved(Auth::user()->division) AS $v2)
                        {
                          $user = App\User::where('username',$v2->empcode)->first();
                          $lv = App\Leave_type::where('id',$v2->leave_id)->first();
                          if($v2->leave_date_from == $v2->leave_date_to)
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from));
                          }
                          else
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from))." - ".date('M d, Y',strtotime($v2->leave_date_to));
                          }
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>".$lv['leave_desc']."</td><td>".$dt."</td>";
                          echo "<td align='center' style='width: 10%'><span class='fas fa-times-circle text-danger' style='cursor:pointer;' onclick='toAction(\"leave\",".$v2->id.",\"Cancelled\")'></span> 
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"Force Leave\",".$v2->id.")'></span></td></tr>";
                        }
                      ?>
                  </table>
                  </div>


                  <div class="tab-pane fade" id="custom-tabs-three-messages4" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab4">
                    <br>
                     <table id="tbl3" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th style="width: 30%">Name</th>
                        <th>Type of Request</th>
                        <th>Date</th>
                        <th></th>
                      </tr>
                      </thead>
                      <?php
                        foreach(getApproved('leave',Auth::user()->division) AS $v2)
                        {
                          $user = App\User::where('username',$v2->empcode)->first();
                          $lv = App\Leave_type::where('id',$v2->leave_id)->first();

                          if($v2->leave_date_from == $v2->leave_date_to)
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from));
                          }
                          else
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from))." - ".date('M d, Y',strtotime($v2->leave_date_to));
                          }

                          $p = "leave";
                          if($v2->leave_id == '16')
                          {
                            $p = "Work From Home";
                          }
                          

                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>".$lv['leave_desc']."</td><td>".$dt."</td>";
                          echo "<td align='center' style='width: 10%'><span class='fas fa-times-circle text-danger' style='cursor:pointer;' onclick='toAction(\"leave\",".$v2->id.",\"Cancelled\")'></span> 
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"".$p."\",".$v2->id.")'></span></td></tr>";
                        }


                        foreach(getApproved('to',Auth::user()->division) AS $v3)
                        {
                          $user = App\User::where('username',$v3->empcode)->first();
                          if($v3->to_date_from == $v3->to_date_to)
                          {
                            $dt2 = date('M d, Y',strtotime($v3->to_date_from));
                          }
                          else
                          {
                            $dt2 = date('M d, Y',strtotime($v3->to_date_from))." - ".date('M d, Y',strtotime($v3->to_date_to));
                          }
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>Travel Order</td><td>".$dt2."</td>";
                          echo "<td align='center' style='width: 10%'><span class='fas fa-times-circle text-danger' style='cursor:pointer;' onclick='toAction(\"TO\",".$v3->id.",\"Cancelled\")'></span> <span class='fas fa-edit text-info' style='cursor:pointer;' onclick='toEdit(\"TO\",".$v3->id.")'></span> 
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"Travel Order\",".$v3->id.")'></span></td></tr>";
                        }

                        foreach(getApproved('ot',Auth::user()->division) AS $v4)
                        {
                          $user = App\User::where('username',$v4->empcode)->first();
                          $dt3= date('M d, Y',strtotime($v4->ot_date));
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>Overtime</td><td>".$dt3."</td>";

                          echo "<td align='center' style='width: 10%'><span class='fas fa-times-circle text-danger' style='cursor:pointer;' onclick='toAction(\"OT\",".$v4->id.",\"Cancelled\")'></span> 
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"Overtime\",".$v4->id.")'></span></td></tr>";
                        }
                      ?>
                  </table>
                  </div>

                  <div class="tab-pane fade" id="custom-tabs-three-messages5" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab5">
                    <br>
                     <table id="tbl7" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <!-- <th style="width: 2%">
                          <input type="checkbox" id="icheck_all" name="icheck_all" class="check" value="all">
                        </th> -->
                        <th style="width: 30%">Name</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th></th>
                      </tr>
                      </thead>
                      <?php
                        foreach(getProcessed('leave',Auth::user()->division) AS $v2)
                        {
                          $user = App\User::where('username',$v2->empcode)->first();
                          $lv = App\Leave_type::where('id',$v2->leave_id)->first();

                          if($v2->leave_date_from == $v2->leave_date_to)
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from));
                          }
                          else
                          {
                            $dt = date('M d, Y',strtotime($v2->leave_date_from))." - ".date('M d, Y',strtotime($v2->leave_date_to));
                          }
                          

                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>".$lv['leave_desc']."</td><td>".$dt."</td>";
                          echo "<td align='center' style='width: 10%'>
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"Force Leave\",".$v2->id.")'></span></td></tr>";
                        }


                        foreach(getProcessed('to',Auth::user()->division) AS $v3)
                        {
                          $user = App\User::where('username',$v3->empcode)->first();
                          if($v3->to_date_from == $v3->to_date_to)
                          {
                            $dt2 = date('M d, Y',strtotime($v3->to_date_from));
                          }
                          else
                          {
                            $dt2 = date('M d, Y',strtotime($v3->to_date_from))." - ".date('M d, Y',strtotime($v3->to_date_to));
                          }
                          
                          echo "<tr><td>".$user['lname'].", ".$user['fname']." ".$user['mname']."</td><td>Travel Order</td><td>".$dt2."</td>";
                          echo "<td align='center' style='width: 10%'>
                          <span class='fas fa-print text-primary' style='cursor:pointer;' onclick='toPrint(\"Travel Order\",".$v3->id.")'></span></td></tr>";
                        }

                      ?>
                  </table>
                  </div>
                  
                </div>
              </div>
              </div>
              <!-- /.card -->
  </div>
    <!-- /.col -->
</div>

<form method="POST" id="frm_request_print" target="_blank">
  {{ csrf_field() }}
  <input type="hidden" name="req_id" id="req_id">
</form>

<form method="POST" id="frm_request_action">
  {{ csrf_field() }}
  <input type="hidden" name="request_id" id="request_id">
  <input type="hidden" name="request_action" id="request_action">
  <input type="hidden" name="request_type" id="request_type">
  <input type="hidden" name="request_ot_in" id="request_ot_in">
  <input type="hidden" name="request_ot_out" id="request_ot_out">
  <input type="hidden" name="request_ot_supervisor" id="request_ot_supervisor">
  <input type="hidden" name="request_ot_cto" id="request_ot_cto" value="NO">

</form>

      <div class="modal fade" id="modal-approve-ot">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">OT REQUEST</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <strong>CTO</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="cto_no" name="cto_status" value="NO" checked>
                          <label for="cto_no">
                            NO
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="cto_yes" name="cto_status" value="YES">
                          <label for="cto_yes">
                            YES
                          </label>
                        </div>
                      </p>
              <div id="ot_time">
                <strong>TIME-IN</strong>
                        <br>
                        <p class="text-muted">
                          <input type="time" class="form-control" name="cto_in" id="cto_in">
                        </p>
                <strong>TIME-OUT</strong>
                        <br>
                        <p class="text-muted">
                          <input type="time" class="form-control" name="cto_out" id="cto_out">
                        </p>
                <strong>Supervisor</strong>
                        <br>
                        <select class="form-control" name="ot_supervisor" id="ot_supervisor">
                        <option value="" selected=""></option>
                        @foreach(getStaffDivision() AS $divs)
                        <option value="{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}">{{ $divs->lname.', '.$divs->fname.' '.$divs->mname }}</option>
                        @endforeach
                        </select>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="approveOT()">Approve</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



      <div class="modal fade" id="modal-batch-ot">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">OT BATCH PRINTING</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form method="POST" id="frm_request_batch_print" action="{{ url('dtr/print-batch-ot') }}" target="_blank">
            {{ csrf_field() }}
            <strong>Year</strong>
                      <br>
                      <select class="form-control" name="ot_batch_year" id="ot_batch_year">
                          <?php
                            $yr2 = date('Y') - 5;
                            $yr1 = date('Y');
                            for($x = $yr1;$x >= $yr2;$x--)
                            {
                              echo '<option value="'.$x.'">'.$x.'</option>';
                            }
                          ?>
                      </select>
            <br>
            <strong>Month</strong>
                      <br>
                      <select class="form-control" name="ot_batch_mon" id="ot_batch_mon">
                      @foreach(getMonths() as $key => $value)
                            <option value="{{ ++$key }}">{{ $value}}</option>
                      @endforeach
                      </select>
            </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="batchOT()">Print</button>
              
              
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


            <!--REQUEST MODAL-->
            <div class="modal fade" id="modal-edit-to">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i id="icon-title"></i> <span id="modal-request-for-title"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
            <form method="POST" id="frm_to_edit" enctype="multipart/form-data" action="{{ url('dtr/send-to-request') }}">
              {{ csrf_field() }}
              <input type="hidden" name="to_id" id="to_id">  
              <input type="hidden" name="active_tab" id="active_tab" value="TO">            
              <!-- LEAVE TYPE -->
              <div class="div-request" id="div-request-leave">

                  <strong>Duration</strong>
                    <div class="form-group">
                      <div class="input-group" id="option-leave-duration4">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right" id="leave_duration4" name="leave_duration4">
                      </div>
                      <!-- /.input group -->
                    </div>
                    <p class="text-muted">
                      <div class="form-group clearfix">
                      <div id="leave_times" style="display: block">

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_wholeday" name="leave_time" value="wholeday" checked>
                        <label for="leave_time_wholeday">
                          Whole day
                        </label>
                      </div>

                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_am" name="leave_time" value="AM">
                        <label for="leave_time_am">
                          AM
                        </label>
                      </div>
                      <div class="icheck-primary d-inline" style="margin-right: 10px">
                        <input type="radio" id="leave_time_pm" name="leave_time" value="PM">
                        <label for="leave_time_pm">
                          PM
                        </label>
                      </div>
                    </div>
                    </div>
                    </p>

                    <div id="option-to">
                      <strong>Vehicle</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_official1" name="vehicle" value="Official" checked>
                          <label for="vehicle_official1">
                            Official
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_personal2" name="vehicle" value="Personal">
                          <label for="vehicle_personal2">
                            Personal
                          </label>
                        </div>

                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="vehicle_personal3" name="vehicle" value="Public Utility Vehicle">
                          <label for="vehicle_personal3">
                            Public Utility Vehicle
                          </label>
                        </div>
                      </p>

                      <strong>Per Diem</strong>
                      <br>
                      <p class="text-muted">
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="perdiem_yes" name="perdiem" value="YES" checked>
                          <label for="perdiem_yes">
                            Will Claim
                          </label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-right: 10px">
                          <input type="radio" id="perdiem_no" name="perdiem" value="NO">
                          <label for="perdiem_no">
                            Will Not Claim
                          </label>
                        </div>
                      </p>

                      <strong>Place</strong>
                      <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="place" id="place">
                        </p>

                      <strong>Purpose</strong>
                      
                      <br>
                        <p class="text-muted">
                          <input type="text" class="form-control" name="purpose" id="purpose">
                        </p>

                    </div>

                </div>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submitTO()">Submit</button>
            
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
  $('#leave_duration4').daterangepicker();

  $(function () {
    var t = $("#tbl,#tbl2,#tbl3,#tbl4,#tbl5,#tbl6,#tbl7").DataTable();

  //   t.on('order.dt search.dt', function () {
  //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  //         cell.innerHTML = i+1;
  //     });
  // }).draw();

  });

//SET DEFAULT MONTH
$("#ot_batch_mon").val({{ date('m') }});

function frmSubmit(status)
{
  $("#leave_action_status").val(status);
  $("#frm").submit();
}

$('input:radio[name="cto_status"]').change(
    function(){
      $("#request_ot_cto").val(this.value);  
    });

function toPrint(type,id) {

    if(type == 'Travel Order')
    {
      action = "{{ url('dtr/print-to') }}";
    }
    else if(type == 'Work From Home')
    {
      action = "{{ url('dtr/print-wfh') }}";
    }
    else if(type == 'Overtime')
    {
      action = "{{ url('dtr/print-ot') }}";
    }
    else
    {
      action = "{{ url('dtr/print-leave') }}";
    }

    $("#req_id").val(id);
    $("#frm_request_print").prop('action',action).submit();
  }

function toAction(type,id,act) {

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes!'
    }).then((result) => {
      if (result.value) {

        $("#overlay").show();
        
        if(type == 'to')
        {
          action = "{{ url('dtr/request-action-to') }}";
        }
        else
        {
          action = "{{ url('dtr/request-action-leave') }}";
        }

        $("#request_id").val(id);
        $("#request_type").val(type);
        $("#request_action").val(act);
        $("#frm_request_action").prop('action',action).submit();
          }
    })
  }

function modalOT(id,act,cto = null,status = null,director = null)
{
  //GET TIME
  $.getJSON( "{{ url('staff/json/cto-time') }}/"+id, function( datajson ) {
                
              }).done(function(datajson){
                  $("#cto_in").val(datajson['otIn']);
                  $("#cto_out").val(datajson['otOut']);
              });
              
  $("#request_id").val(id);
  $("#request_type").val('OT');
  $("#request_action").val(act);
  $("#modal-approve-ot").modal('toggle');

  if(cto)
    $("#request_ot_cto").val('YES');
  else
    $("#request_ot_cto").val('NO');

  $("#ot_time").show();
  
  if(status == 'Pending');
    {
      if(director == 'YES')
      {
        $("#ot_time").hide();
        $("#request_action").val("OED Approved");
      }
    }

  
}

function approveOT() {

    $("#request_ot_in").val($('#cto_in').val());
    $("#request_ot_out").val($('#cto_out').val());
    $("#request_ot_supervisor").val($('#ot_supervisor').val());
    // $("#request_ot_cto").val($('input[name=cto_status]').val());

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes!'
    }).then((result) => {
      if (result.value) {

        $("#overlay").show();
        
        action = "{{ url('dtr/request-action-leave') }}";
        $("#frm_request_action").prop('action',action).submit();
          }
    })
  }

  function batchOT()
  {
    $("#frm_request_batch_print").submit();
  }

  function toEdit(type,id)
  {
    switch(type)
    {
      case "TO":
        $("#to_id").val(id);
        $("#duration4").val();
        $("#modal-edit-to").modal("toggle");

        //GET JSON DETAILS
        $.getJSON( "{{ url('request/json/to/') }}/"+id, function( datajson ) {
                
              }).done(function(datajson){
                //CONVERT DATE
                var tempDate1 = new Date(datajson['to_date_from']);
                var tempDate2 = new Date(datajson['to_date_to']);

                var formattedDate1 = [( '0' + (tempDate1.getMonth()+1) ).slice( -2 ), ( '0' + (tempDate1.getDate()) ).slice( -2 ), tempDate1.getFullYear()].join('/');
                var formattedDate2 = [( '0' + (tempDate2.getMonth()+1) ).slice( -2 ), ( '0' + (tempDate2.getDate()) ).slice( -2 ), tempDate2.getFullYear()].join('/');

                $("#leave_duration4").daterangepicker({
                  startDate : formattedDate1,
                  endDate : formattedDate2
                });

                //GET TIME
                $("input[name=leave_time][value=" + datajson['to_deduction_time'] + "]").prop('checked', 'checked');

                //GET VEHICLE
                $("input[name=vehicle][value=" + datajson['to_vehicle'] + "]").prop('checked', 'checked');

                //PER DIEM
                $("input[name=perdiem][value=" + datajson['to_perdiem'] + "]").prop('checked', 'checked');
                
                $("#place").val(datajson['to_place']);
                $("#purpose").val(datajson['to_purpose']);
              });

      break;
    }

  }

  function submitTO()
  {
    $("#overlay").show();
    $("#frm_to_edit").submit();
  }

  // $("#cto_in").change(function(){
  //   if(this.value < "17:31:00")
  //     $("#cto_in").val("17:31:00");
  // })

  // $("#cto_out").change(function(){
  //   if(this.value < $("#cto_in").val())
  //     $("#cto_out").val("");
  // })
</script>
@endsection