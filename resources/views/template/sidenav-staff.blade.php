<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $invitation = $data['nav']['invitation'];
      $calendar = $data['nav']['calendar'];
      $icos = $data['nav']['icos'];
      $icospayroll = $data['nav']['icospayroll'];
      $icosprocess = $data['nav']['icosprocess'];
      $payroll_benefit = $data['nav']['payroll_benefit'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $invitation = "";
      $calendar = "";
      $icos = "";
      $icospayroll = "";
      $icosprocess = "";
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
              <i class="nav-icon fas fa-home"></i>
              <p>
                Home
              </p>
            </a>
          </li>
          <li class="nav-header">MENU</li>

          <li class="nav-item">
            <a href="{{ url('personal-information/info/na') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Update PDS
              </p>
            </a>
          </li>

          @if(getMyInvitation('count') > 0)
          <li class="nav-item">
            <a href="{{ url('invitation/list') }}" class="nav-link {{ $invitation }}">
              <i class="nav-icon fas fa-bullhorn" style="color:red"></i>
              <p>
                Invitation <span class="badge badge-danger">{{ getMyInvitation('count') }}</span>
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item">
          <a href="{{ url('staff/attendance/'.date('m').'/'.date('Y').'/'.Auth::user()->id ) }}" class="nav-link">
              <i class="nav-icon fas fa-calendar"></i>
              <p>
                Attendance
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/leave') }}" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
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

          @if(Auth::user()->id == 233)
          <li class="nav-header">COS MENU</li>

          <li class="nav-item">
          <a href="{{ url('dtr/icos/'.date('m').'/'.date('Y')).'/'.getFirstICOS(Auth::user()->division) }}" class="nav-link {{ $icos }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Attendance
              </p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ url('dtr/icos-process') }}" class="nav-link {{ $icosprocess }}">
              <i class="nav-icon fas fa-clock"></i>
              <p>
                Process DTR
              </p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ url('icos/payroll/'.date('m').'/'.date('Y').'/'.getPeriodCOS()) }}" class="nav-link {{ $icospayroll }}">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                COS Payroll
              </p>
            </a>
          </li>
          @endif


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


         <!--  <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Attendance Monitoring
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub-menu 1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub-menu 2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub-menu 3</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- <li class="nav-item">
            <a href="{{ url('calendar') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Calendar
              </p>
            </a>
          </li> -->


          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->