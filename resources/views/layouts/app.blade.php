<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
    @include('partials.styles')
</head>
<body>
    @unless (Request::is('auth/login') || Request::is('auth/register'))
        @include('components.header')
        @include('components.navbar')
    @endunless

    <div class="container-fluid">
        @yield('content')
    </div>

    @unless (Request::is('auth/login') || Request::is('auth/register'))
        @include('components.footer')
    @endunless
    
    @include('partials.scripts')
</body>
</html>
