<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

    <!-- datatables css-->
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.2.2/r-3.0.4/sp-2.3.3/sr-1.4.1/datatables.min.css"
        rel="stylesheet" integrity="sha384-uMRVFAehEmeRx+eu65ZAwUtvyFbGSAXOA+y0/bktyqsYwlw8575VE7T7o5PqC9HY"
        crossorigin="anonymous">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@isset($style)
    {{ $style }}
@endisset

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.admin.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset


        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

{{-- datatables script --}}
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.2.2/r-3.0.4/sp-2.3.3/sr-1.4.1/datatables.min.js"
    integrity="sha384-EyOrkIw2BJ0wGDDncNwhfC5UwkD+tjKPyPNUqOd9J92FC+Y3JT5Q/32Ad5/x0ylC" crossorigin="anonymous">
</script>

<!-- script -->
@isset($script)
    {{ $script }}
@endisset

</html>
