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
      $calendar = $data['nav']['calendar'];
      $icos = $data['nav']['icos'];
      $dtr = $data['nav']['dtr'];
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
      $calendar = "";
      $icos = "";
      $dtr = "";
    }
?>
<!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="font-size: 15px">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!--<li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $dashboard }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-header">MENU</li>
          <li class="nav-item">
            <a href="{{ url('personal-information/info') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Personal Information
              </p>
            </a>
          </li>-->

          

          <li class="nav-item has-treeview {{ $attendance_menu }}">
            <a href="#" class="nav-link {{ $attendance }}">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Attendance Monitoring
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!--<li class="nav-item">
                <a href="{{ url('request-for-approval') }}" class="nav-link {{ $attendance_approval }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Request For Approval</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $dtr }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee DTR</p>
                </a>
              </li>-->
              <li class="nav-item">
                <a href="{{ url('dtr/icos') }}" class="nav-link {{ $icos }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ICOS DTR</p>
                </a>
              </li>
            </ul>
          </li>

          <!--<li class="nav-item">
            <a href="{{ url('calendar') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Calendar
              </p>
            </a>
          </li>-->



          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->