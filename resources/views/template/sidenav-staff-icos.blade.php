<?php
    if(isset($data['nav']))
    {
      $dashboard = $data['nav']['dashboard'];
      $myprofile = $data['nav']['myprofile'];
      $invitation = $data['nav']['invitation'];
      $calendar = $data['nav']['calendar'];
      $icospayroll = $data['nav']['icospayroll'];
      $payroll_benefit = $data['nav']['payroll_benefit'];
    }
    else
    {
      $dashboard = "";
      $myprofile = "";
      $invitation = "";
      $calendar = "";
      $icospayroll = "";
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
              <i class="nav-icon fas fa-clock"></i>
              <p>
                DTR
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('cos/payroll-page') }}" class="nav-link {{ $icospayroll }}">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Payroll
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