<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="{{asset('./assets/img/admin-avatar.png')}}" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong">Bond</div><small>{{Auth::user()->name}}</small></div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="active" href="{{route('Dashboard')}}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading">Management</li>
            <li>
                <a class="{{ (request()->routeIs('terminals*')) ? 'active' : '' }}" href="{{ route('terminals.index')}}"><i class="sidebar-item-icon fa fa-microchip"></i>
                    <span class="nav-label">Terminals</span>
                </a>
            </li>
            <li>
                <a class="{{ (request()->routeIs('users*')) ? 'active' : '' }}" href="{{ route('users.index')}}"><i class="sidebar-item-icon fa fa-user-circle-o"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
