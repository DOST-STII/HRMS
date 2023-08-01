<!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php
            $applicant = 0;
            $req = getNotification('request-update');
            $total = $req;
          ?>
        @if($total > 0)
          <span class="badge badge-danger navbar-badge">{{$total}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{$total}} Notifications</span>
          @if($req > 0)
          <div class="dropdown-divider"></div>
          <a href="{{ url('request-for-hiring-list') }}" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> {{$req}} Request for hiring
          </a>
          @endif
        @else
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">No Notifications</span>
        </div>
        @endif
      </li>
