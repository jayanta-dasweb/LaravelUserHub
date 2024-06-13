<!DOCTYPE html>
<html>

<head>
    @include('partials.head')
    @include('partials.styles')
</head>

<body class="position-relative">
    @include('partials.loader')
    @unless (Request::is('auth/login') || Request::is('auth/register'))
        @include('components.header')
        @include('components.sideBarMenue')
        <div id="mainContent" class="p-3">
            @if (Auth::user()->roles->isEmpty())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div>
                        You currently do not have any roles assigned. Please contact your administrator for
                        access
                        rights.
                    </div>
                </div>
            @elseif (Auth::user()->getAllPermissions()->isEmpty())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div>
                        You currently do not have any permissions assigned. Please contact your administrator for
                        access
                        rights.
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
    @endunless

    @if (Request::is('auth/login') || Request::is('auth/register'))
        @yield('content')
    @endif

    @include('partials.scripts')
</body>

</html>
