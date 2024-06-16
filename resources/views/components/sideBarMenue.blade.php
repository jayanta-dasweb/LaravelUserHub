<div class="bg-dark p-3" id="sideBarMenue">
    <div class="d-flex align-items-center justify-content-between" id="loginUserProfile">
        <div class="bg-info" id="userProfilePic">
            <i class="fa-solid fa-user-tie fs-2 text-white"></i>
        </div>
        <div id="loginUserInfo">
            <h6 id="name">{{ Auth::user()->name }}</h6>
            <p id="role">{{ optional(Auth::user()->roles->first())->name ?? 'User' }}</p>
        </div>
    </div>
    <nav class="menu">
        <ul class="nav flex-column">
            <!-- New User (with counter) -->
            @if (Auth::user()->can('view new user'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.user.view.new') }}">
                        <i class="fa-solid fa-user-plus"></i>&nbsp;&nbsp;New User <span class="badge badge-primary"
                            id="usersWithoutRolesCount">0</span>
                    </a>
                </li>
            @endif

            <!-- User Management -->
            @if (Auth::user()->can('view user'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-users"></i>&nbsp;&nbsp;User Management <i
                            class="fa-solid {{ Route::currentRouteName() === 'dashboard.user.view' ? 'fa-chevron-down' : 'fa-chevron-right' }} ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled {{ Route::currentRouteName() === 'dashboard.user.view' ? 'show' : '' }}"
                        id="userManagementSubmenu">
                        @can('view user')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.user.view' ? 'fw-bold text-white' : '' }}" href="{{ route('dashboard.user.view') }}">View All Users</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Role Management -->
            @if (Auth::user()->can('create role') || Auth::user()->can('view role'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-user-shield"></i>&nbsp;&nbsp;Role Management <i
                            class="fa-solid fa-chevron-right {{ ((Route::currentRouteName() === 'dashboard.role.create.view') || (Route::currentRouteName() === 'dashboard.role.show.view'))  ? 'fa-chevron-down' : 'fa-chevron-right' }} ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled {{ ((Route::currentRouteName() === 'dashboard.role.create.view') || (Route::currentRouteName() === 'dashboard.role.show.view')) ? 'show' : '' }}" id="roleManagementSubmenu">
                        @can('create role')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.role.create.view' ? 'fw-bold text-white' : '' }}" href="{{route('dashboard.role.create.view')}}">Create Role</a>
                            </li>
                        @endcan
                        @can('view role')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.role.show.view' ? 'fw-bold text-white' : '' }}" href="{{route('dashboard.role.show.view')}}" >View All Roles</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- NSAP Schemes Management -->
            @if (Auth::user()->can('view NSAP scheme'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-brands fa-wpforms"></i>&nbsp;&nbsp;NSAP Scheme Management <i
                            class="fa-solid {{ Route::currentRouteName() === 'dashboard.nsapScheme.view' ? 'fa-chevron-down' : 'fa-chevron-right' }} ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled {{ Route::currentRouteName() === 'dashboard.nsapScheme.view' ? 'show' : '' }}"
                        id="userManagementSubmenu">
                        @can('view NSAP scheme')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.nsapScheme.view' ? 'fw-bold text-white' : '' }}" href="{{ route('dashboard.nsapScheme.view') }}">View All NSAP Schemes</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Import data -->
            @if (Auth::user()->can('create users bulk data'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;Import Bulk Data <i
                            class="fa-solid  {{ (Route::currentRouteName() === 'dashboard.import.users.show' || Route::currentRouteName() === 'dashboard.import.nsapScheme.show') ? 'fa-chevron-down' : 'fa-chevron-right' }} ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled {{ (Route::currentRouteName() === 'dashboard.import.users.show' || Route::currentRouteName() === 'dashboard.import.nsapScheme.show') ? 'show' : '' }}" id="excelFileManagementSubmenu">
                        @can('create users bulk data')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.import.users.show' ? 'fw-bold text-white' : '' }}" href="{{route('dashboard.import.users.show')}}">Import Bulk Users Data</a>
                            </li>
                        @endcan
                         @can('create NSAP scheme bulk data')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.import.nsapScheme.show' ? 'fw-bold text-white' : '' }}" href="{{route('dashboard.import.nsapScheme.show')}}">Import Bulk NSAP Scheme Data</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
        </ul>
    </nav>





</div>
