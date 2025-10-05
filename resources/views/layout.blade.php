<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true" data-color="light-blue">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'Pos-system Control Pannel')</title>
    <meta name="description" content=""/>
    @include('_partials.head')
</head>
<body>
<div id="root">
    <div id="nav" class="nav-container d-flex">
        @include('_partials.nav')
    </div>
    <main>
        <div class="container">
            <div class="row">
            @include('_partials.menu_left')
            <!-- Page Content Start -->
                <div class="col">
                    @yield('content')
                </div>
                <!-- Page Content End -->
            </div>
        </div>
    </main>
    @include('_partials.footer')
</div>
@include('_partials.scripts')
</body>
</html>
