<link rel="stylesheet" href="{{ asset('assets/dist/css/custom.css') }}">

@include('backend.layouts.header')
@include('backend.layouts.leftsidebar')

    @yield('main-content')

@include('backend.layouts.footer')
