<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- VITE -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('head')

</head>

<body class="page-transition">

    {{-- AUTH PAGE --}}
    @if(
            Request::is('login') ||
            Request::is('register') ||
            Request::is('forgot-password') ||
            Request::is('reset-password/*')
        )

        @yield('content')

    @else

        @yield('content')

    @endif

</body>

@stack('scripts')

</html>