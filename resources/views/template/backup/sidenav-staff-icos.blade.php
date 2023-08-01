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
                DTR
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('personal-information/info') }}" class="nav-link {{ $myprofile }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                IPCR
              </p>
            </a>
          </li>



          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->