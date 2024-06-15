<header class="container-fluid pl-4 bg-light d-flex align-items-center">
    <i class="fa-brands fa-pied-piper fs-1 d-none d-md-block"></i>
    <nav class="d-flex align-items-center justify-content-between ml-4" id="navbar">
        <div class="d-flex align-items-center justify-content-between" id="navBarRightSideContent">
            <h3 class="d-none d-md-block">Dashboard</h3>
            <i class="fa-solid fa-bars fs-2 text-dark" id="hamburgerMenu"></i>
        </div>
        <div class="d-flex align-items-center justify-content-between" id="navBarLeftSideContent">
            <a href="{{route('dashboard.profile.show')}}" id="navLink">Profile</a>
            <button id="logoutButton" type="button" class="btn btn-outline-danger"
                data-logout-url="{{ route('logout') }}" data-csrf-token="{{ csrf_token() }}">
                Logout <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </div>
    </nav>
</header>
