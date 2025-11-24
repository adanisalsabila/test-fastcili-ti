<div class="wrapper">

    @include('layouts.navbars.auth')

    <div class="main-panel">
        <link rel="stylesheet" href="{{ asset('style/style.css') }}">
        @include('layouts.navbars.navs.auth')
        @yield('content')
        @include('layouts.footer')
    </div>
</div>
