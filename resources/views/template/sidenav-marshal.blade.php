<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $hiring = $data['nav']['hiring'];
      $submission = $data['nav']['submission'];
      $attendance_menu = $data['nav']['attendance_menu'];
      $attendance = $data['nav']['attendance'];
      $attendance_approval = $data['nav']['attendance_approval'];
      $monitor = $data['nav']['monitor'];
      $calendar = $data['nav']['calendar'];
      $icos = $data['nav']['icos'];
      //$icospayroll = null;
      $icospayroll = $data['nav']['icospayroll'];
      $icosprocess = $data['nav']['icosprocess'];

      $empdtr = $data['nav']['empdtr'];
      $dtr = $data['nav']['dtr'];

      $invitation = $data['nav']['invitation'];
      $payroll_benefit = $data['nav']['payroll_benefit'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $hiring = "";
      $submission = "";
      $attendance_menu = "";
      $attendance = "";
      $attendance_approval = "";
      $monitor = "";
      $calendar = "";
      $icos = "";
      $icospayroll = "";
      $icosprocess = "";
      $empdtr = "";
      $dtr = "";
      $invitation = "";
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
          <li class="nav-header">MARSHAL MENU</li>
          
          
          @if(Auth::user()->division == 'O')
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

          
          @if(Auth::user()->division == 'q' || Auth::user()->division == 'A' || Auth::user()->division == 'O')
          <!-- BUDGET/ARMMS/OED -->
          <li class="nav-item">
            <a href="{{ url('recruitment/letter-approval') }}" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
                Letter of Approval
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a href="{{ url('letter-request') }}" class="nav-link {{ $hiring }}">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                HR Requirements
              </p>
            </a>
          </li>


          @if(countCallforSubmitDivision('total') > 0)
          <li class="nav-item">
            <a href="{{ url('submission-list/division') }}" class="nav-link {{ $submission }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Call for Submission <span class="badge badge-danger">{{ countCallforSubmitDivision('active') }}</span>
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item has-treeview {{ $attendance_menu }}">
            <a href="#" class="nav-link {{ $attendance }}">
              <i class="nav-icon fas fa-calendar-check"></i>
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
                <a href="{{ url('dtr/employee') }}" class="nav-link {{ $empdtr }}">
                  <i class="fas fa-user-clock nav-icon"></i>
                  <p>Process DTR</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('dtr/icos') }}" class="nav-link {{ $icos }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ICOS DTR</p>
                </a>
              </li> -->
            

          <li class="nav-item">
            <a href="{{ url('core-competency') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Succession Planning and Retirement 
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('dtr/report') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Reports 
              </p>
            </a>
          </li>

          <?php
          //CHECK IF HAS ICOS
          $user = App\User::where('division',Auth::user()->division)->where('employment_id',8)->count();

          $flag = false;

          if($user > 0)
            $flag = true;
          ?>


          @if($flag)
          <li class="nav-header">MENU</li>

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

          


          <li class="nav-header">STAFF MENU</li>

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
            <a href="{{ url('personal-information/info/na') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Personal Information
              </p>
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