<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? get_app_setting('app_name') }}</title>
    <meta name="description" content="{{ $description ?? get_app_setting('app_description') }}">

    <link rel="icon" type="image/png" href="{{get_app_setting('favicon')}}">

    @if(isset($ogShare))
        {{ $ogShare }}
    @endif
    
    @if (get_app_setting('ga4_id'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{get_app_setting('ga4_id')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{get_app_setting('ga4_id')}}');
        </script>
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,400i,700,700i,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/scss/app.scss','resources/js/app.js'])
    @yield('header_scripts')
    <style>
        #main-nav {
            background-color:{{ get_app_setting('header_background_color') }};
        }

        .background-app {
            background-color:{{get_app_setting('app_background_color')}};
            background-image: url('{{get_app_setting('app_animated_background')}}'), url('{{get_app_setting('app_background')}}');
            background-position: 0 0, 50% 50%;
            background-repeat: repeat, no-repeat;
            background-size: contain, cover;
        }

        .game-heading:after{
            content: "";
            display: block;
            padding-bottom: .8rem;
            border-bottom: 2px solid {{get_app_setting('cards_font_color')}};
        }

        .game-card {
            background-color:{{ get_app_setting('cards_background_color') ? get_app_setting('cards_background_color') : 'transparent' }};
            color: {{get_app_setting('cards_font_color')}};
        }

        .game-card p {
            color: {{get_app_setting('cards_font_color')}}
        }
    </style>
    <style>{{ get_app_setting('custom_css') }}</style>
</head>

<body class="font-sans antialiased background-app app">
    <div class="min-h-screen dark:bg-gray-900">
        @include('layouts.navigation')

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

    {{-- @vite(['resources/js/app.js']) --}}

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            anime({
                targets: 'body',
                backgroundPosition: ['0 0, 50% 50%', '0 20px, 50% 50%'],
                easing: 'easeInOutSine',
                loop: true,
                direction: 'alternate',
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
