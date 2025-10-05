<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/index.css') }}">
    <link rel="stylesheet"  href="{{ asset('css/theme/export/index.css') }}">

    @yield('style')
</head>

<body>
    @yield('content')
</body>

</html>
