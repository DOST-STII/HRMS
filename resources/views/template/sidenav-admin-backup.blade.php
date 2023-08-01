<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $hiring = $data['nav']['hiring'];
      $vacant = $data['nav']['vacant'];
      $submission = $data['nav']['submission'];
      $pislibrary = $data['nav']['pislibrary'];
      $calendar = $data['nav']['calendar'];
      $servicerecord = $data['nav']['servicerecord'];

      $retiree = $data['nav']['retiree'];
      $jos = $data['nav']['jos'];

      $recruit = $data['nav']['recruit'];
      $learn = $data['nav']['learn'];
      $performance = $data['nav']['performance'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $hiring = "";
      $vacant = "";
      $submission = "";
      $pislibrary = "";
      $calendar = "";
      $servicerecord = "";

      $retiree = "";
      $jos = "";

      $recruit = "";
      $learn = "";
      $performance = "";
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

          <li class="nav-header">PERSONNEL INFO</li>
          <li class="nav-item">
            <a href="{{ url('list-of-employees') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Number of Employees
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('vacant-position') }}" class="nav-link {{ $vacant }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Plantilla Positions <span class="badge badge-danger"></span>
              </p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="{{ url('retiree/ALL') }}" class="nav-link {{ $retiree }}">
              <i class="nav-icon fas fa-user-slash"></i>
              <p>
                Retirees (59 to 65 YO) <span class="badge badge-danger"></span>
              </p>
            </a>
          </li> -->

          <li class="nav-item">
            <a href="{{ url('contract-of-service') }}" class="nav-link {{ $jos }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Contract of Service/JO <span class="badge badge-danger"></span>
              </p>
            </a>
          </li>

          <li class="nav-header"></li>
          <li class="nav-item">
            <a href="{{ url('recruitment/index') }}" class="nav-link {{ $recruit }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Recruitement <span class="badge badge-danger"></span>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('learning-development/index') }}" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Learning And Development 
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('performance/index') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Performance Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('rewards/index') }}" class="nav-link">
              <i class="nav-icon fas fa-medal"></i>
              <p>
                Rewards and Recognitions
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('retiree/ALL') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Succession Planning and Retirement 
              </p>
            </a>
          </li>

         <!--  <li class="nav-item">
            <a href="{{ url('letter-of-request-list') }}" class="nav-link {{ $hiring }}">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
                Letter of Request <span class="badge badge-danger"></span>
              </p>
            </a>
          </li> -->


          <!-- <li class="nav-header"></li>

           <li class="nav-item">
            <a href="{{ url('submission/list') }}" class="nav-link {{ $submission }}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Call for Submmision
              </p>
            </a>
          </li> -->
          
          <!-- <li class="nav-item">
            <a href="{{ url('service-record') }}" class="nav-link {{ $servicerecord }}">
              <i class="nav-icon fas fa-print"></i>
              <p>
                Service Record
              </p>
            </a>
          </li> -->

          <!-- <li class="nav-header">ATTENDANCE MONITORING</li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                DTR
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
          </li> -->

<!--           <li class="nav-item">
            <a href="{{ url('calendar') }}" class="nav-link {{ $calendar }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Calendar
              </p>
            </a>
          </li> -->


          <!-- <li class="nav-header">PAYROLL</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Menu 1
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                Menu 2
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Menu 3
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

            <li class="nav-header">SETTINGS</li>
            <li class="nav-item">
            <a href="{{ url('pis-library/division') }}" class="nav-link {{ $pislibrary }}">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Library
              </p>
            </a>
          </li> -->

          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->