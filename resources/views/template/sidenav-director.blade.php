<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $attendance_menu = $data['nav']['attendance_menu'];
      $attendance = $data['nav']['attendance'];
      $attendance_approval = $data['nav']['attendance_approval'];
      $calendar = $data['nav']['calendar'];
      $monitor = $data['nav']['monitor'];
      $payroll_benefit = $data['nav']['payroll_benefit'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $calendar = "";
      $attendance_menu = "";
      $attendance = "";
      $attendance_approval = "";
      $monitor = "";
      $payroll_benefit = "";
    }
?>
<!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="font-size: 15px">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $dashboard }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-header">MENU</li>
          <li class="nav-item">
            <a href="{{ url('personal-information/info/na') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Personal Information
              </p>
            </a>
          </li>

          @if(checkifHRDC(Auth::user()->id))
          <!-- BUDGET/ARMMS/OED -->
          <li class="nav-item">
            <a href="{{ url('learning-development/list-hrd-approval') }}" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
                HRD Plan for Approval
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item has-treeview {{ $attendance_menu }}">
          <a href="{{ url('staff/attendance/'.date('m').'/'.date('Y').'/'.Auth::user()->id ) }}" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('staff/attendance/'.date('m').'/'.date('Y').'/'.Auth::user()->id ) }}" class="nav-link {{ $monitor }}">
                  <i class="nav-icon fas fa-desktop"></i>
                  <p>
                    Monitoring 
                  </p>
                </a>
              </li>
            </ul>
          </li>
          

          <li class="nav-item">
                <a href="{{ url('request-for-approval') }}" class="nav-link {{ $attendance_approval }}">
                  <i class="fas fa-envelope-open-text nav-icon"></i>
                  <p>Request For Approval</p>
                </a>
              </li>

          <li class="nav-item">
            <a href="{{ url('staff/leave') }}" class="nav-link">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                Apply for leave
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff-all-request') }}" class="nav-link">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                View all request
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/to') }}" class="nav-link">
              <i class="nav-icon fas fa-shuttle-van"></i>
              <p>
                File TO
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/cto') }}" class="nav-link">
              <i class="nav-icon fas fa-clock"></i>
              <p>
                Request for OT/CTO
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/payroll') }}" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Check payroll
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/loan') }}" class="nav-link">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Loan monitoring
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('change-password') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>


          <li class="nav-header">RESOURCES</li>
          <li class="nav-item">
            <a href="{{ asset('files/RA_6713-Code-of-Ethics.pdf') }}" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-file-pdf"></i>
              <p>
                RA_6713 Code of Ethics
              </p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ asset('files/RACCS-2017.pdf') }}" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-file-pdf"></i>
              <p>
                RACCS 2017
              </p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ asset('files/Employee-Manual.pdf') }}" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-file-pdf"></i>
              <p>
                Employee Manual
              </p>
            </a>
          </li>

          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->