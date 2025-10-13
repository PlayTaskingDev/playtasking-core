<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? get_app_setting('app_name') }}</title>
    <meta name="description" content="{{ $description ?? get_app_setting('app_description') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,400i,700,700i,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/scss/app.scss'])

    <style>
        #main-nav {
            background-color: {{get_app_setting('header_background_color')}};
        }
        .background-none {
            background-image: url('{{get_app_setting('app_background')}}');
            background-position: 50%;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    @yield('header_scripts')
</head>

<body class="font-sans antialiased background-none">
    <div class="min-h-screen dark:bg-gray-900">
        @include('layouts.navigation-panel')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @vite(['resources/js/app.js'])

    @yield('scripts')
</body>

</html>
