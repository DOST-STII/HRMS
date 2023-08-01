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

      $numemp = $data['nav']['numemp'];
      $arvemp = $data['nav']['arvemp'];

      $retiree = $data['nav']['retiree'];
      $jos = $data['nav']['jos'];

      $recruit = $data['nav']['recruit'];
      $learn = $data['nav']['learn'];
      $performance = $data['nav']['performance'];

      $payroll_emp = $data['nav']['payroll_emp'];
      $payroll_process = $data['nav']['payroll_process'];
      $payroll_remittance = $data['nav']['payroll_remittance'];
      //$payroll_ledger = $data['nav']['payroll_ledger'];
      $payroll_lib = $data['nav']['payroll_lib'];
      $payroll_report = $data['nav']['payroll_report'];
      $payroll_benefit = $data['nav']['payroll_benefit'];

      $icospayroll = $data['nav']['icospayroll'];
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

      $numemp = "";
      $arvemp = "";

      $retiree = "";
      $jos = "";

      $recruit = "";
      $learn = "";
      $performance = "";

      $payroll_emp = "";
      $payroll_process = "";
      $payroll_remittance = "";
      //$payroll_ledger = "";
      $payroll_lib = "";
      $payroll_report = "";
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
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-header">PRIME-HRM</li>

          <li class="nav-item">
            <a href="{{ url('recruitment/index') }}" class="nav-link {{ $recruit }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Recruitment <span class="badge badge-danger"></span>
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
              <i class="nav-icon fas fa-chart-line" style="top:0px"></i>
              <p>
                Succession Planning<br/>and Retirement 
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ url('list-of-employees') }}" class="nav-link {{ $numemp }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                List of Active Employees
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('archived-employees') }}" class="nav-link {{ $arvemp }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Inactive Employees
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('vacant-position') }}" class="nav-link {{ $vacant }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Vacant Plantilla Position<span class="badge badge-danger"></span>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('contract-of-service') }}" class="nav-link {{ $jos }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                List of ICOS/JOS/Project Staff <span class="badge badge-danger"></span>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('pis-library/division') }}" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Library
              </p>
            </a>
          </li>

          

          <li class="nav-header">ATTENDANCE MONITORING</li>
          <li class="nav-item">
            <a href="{{ url('staff/leave') }}" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Apply for leave
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
            <a href="{{ url('request-for-approval') }}" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                View Leave
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('staff/attendance/'.date('m').'/'.date('Y').'/'.Auth::user()->id) }}" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                View DTR
              </p>
            </a>
          </li>

          <li class="nav-item">
                <a href="{{ url('dtr/employee') }}" class="nav-link">
                  <i class="fas fa-user-clock nav-icon"></i>
                  <p>Process DTR</p>
                </a>
              </li>

          <li class="nav-item">
                <a href="{{ url('dtr/reverse') }}" class="nav-link">
                  <i class="fas fa-user-clock nav-icon"></i>
                  <p>Reverse DTR</p>
                </a>
              </li>



          <li class="nav-item">
            <a href="{{ url('dtr/report') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-area"></i>
              <p>
                Reports
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('maintenance') }}" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                DTR Maintenance
              </p>
            </a>
          </li>


          <li class="nav-header">PAYROLL</li>

          <li class="nav-item">
            <a href="{{ url('payroll/emp') }}" class="nav-link {{ $payroll_emp }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Payroll Details
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('payroll/benefits') }}" class="nav-link {{ $payroll_benefit }}">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Benefits
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('payroll/mc/'.date('m').'/'.date('Y')) }}" class="nav-link">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                MC Report
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('payroll/process') }}" class="nav-link {{ $payroll_process }}">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Payroll Processing
              </p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ url('icos/payroll/'.date('m').'/'.date('Y').'/'.getPeriodCOS()) }}" class="nav-link {{ $icospayroll }}">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                COS Payroll Processing
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('payroll/remittance') }}" class="nav-link {{ $payroll_remittance}}">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Remittance
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('payroll/ledger') }}" class="nav-link">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Salary Ledger
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('payroll/library') }}" class="nav-link {{ $payroll_lib }}">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Payroll Library
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('payroll/report') }}" class="nav-link {{ $payroll_report }}">
              <i class="nav-icon fas fa-chart-area"></i>
              <p>
                Reports
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