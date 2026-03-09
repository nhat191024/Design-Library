<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="image/x-icon" rel="icon" href="{{ asset('images/logos/favicon.ico') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <title>Thiết kế decor</title>

    <title>{{ $title }}</title>

    <!-- Fix UI flickering script -->
    <script>
        (function() {
            let theme = localStorage.getItem("theme") || "light";
            document.documentElement.setAttribute("data-theme", theme);
        })();
    </script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('client.partials.navigation')
    @yield('content')
    @include('client.partials.footer')
</body>

</html>
