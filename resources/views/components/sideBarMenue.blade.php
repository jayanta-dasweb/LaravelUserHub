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
            @if (Auth::user()->can('view new users'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.user.view.new') }}">
                        <i class="fa-solid fa-user-plus"></i>&nbsp;&nbsp;New User <span class="badge badge-primary"
                            id="usersWithoutRolesCount">0</span>
                    </a>
                </li>
            @endif

            <!-- User Management -->
            @if (Auth::user()->can('create user') || Auth::user()->can('view users'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-users"></i>&nbsp;&nbsp;User Management <i
                            class="fa-solid {{ Route::currentRouteName() === 'dashboard.user.view' ? 'fa-chevron-down' : 'fa-chevron-right' }} ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled {{ Route::currentRouteName() === 'dashboard.user.view' ? 'show fw-bold' : '' }}"
                        id="userManagementSubmenu">
                        @can('view users')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() === 'dashboard.user.view' ? 'fw-bold text-white' : '' }}" href="{{ route('dashboard.user.view') }}">View All Users</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Role Management -->
            @if (Auth::user()->can('create role') || Auth::user()->can('view roles'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-user-shield"></i>&nbsp;&nbsp;Role Management <i
                            class="fa-solid fa-chevron-right ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="roleManagementSubmenu">
                        @can('create role')
                            <li class="nav-item">
                                <a class="nav-link" href="/create-role">Create Role</a>
                            </li>
                        @endcan
                        @can('view roles')
                            <li class="nav-item">
                                <a class="nav-link" href="/view-all-roles">View All Roles</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Excel File Management -->
            @if (Auth::user()->can('upload excel') || Auth::user()->can('view excel files'))
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="collapse"
                        aria-expanded="false">
                        <i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;Excel File Management <i
                            class="fa-solid fa-chevron-right ml-auto"></i>
                    </a>
                    <ul class="collapse list-unstyled" id="excelFileManagementSubmenu">
                        @can('upload excel')
                            <li class="nav-item">
                                <a class="nav-link" href="/upload-excel">Upload Excel File</a>
                            </li>
                        @endcan
                        @can('view excel files')
                            <li class="nav-item">
                                <a class="nav-link" href="/view-all-excel-files">View All Excel Files</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
        </ul>
    </nav>





</div>
