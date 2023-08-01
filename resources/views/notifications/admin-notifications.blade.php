<!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell fa-lg"></i>
          <?php
            $applicant = 0;
            $req_letter = getNotification('request');
            $call_report = getNotification('report');
            $total = $req_letter + $applicant;
          ?>
        @if($total > 0)
          <span class="badge badge-danger navbar-badge">{{$total}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{$total}} Notifications</span>
          @if($req_letter > 0)
          <div class="dropdown-divider"></div>
          <a href="{{ url('request-for-hiring-list') }}" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> {{$req_letter}} Request for hiring
          </a>
          @endif

          @if($applicant > 0)
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          </div>
          @endif
        @else
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">No Notifications</span>
        </div>
        @endif
      </li>
