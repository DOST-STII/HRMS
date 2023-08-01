<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $invitation = $data['nav']['invitation'];
      $calendar = $data['nav']['calendar'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $invitation = "";
      $calendar = "";
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
            <a href="{{ url('personal-information/info') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Personal Information
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

          <li class="nav-item has-treeview">
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

          <li class="nav-item">
            <a href="{{ url('calendar') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Calendar
              </p>
            </a>
          </li>


          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->