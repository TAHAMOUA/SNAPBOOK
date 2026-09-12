<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css2?family=Barlow+Condensed:wght@300;500;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[var(--void)]">

        <nav>
            <a href="/" class="logo">Snap<span>Book</span></a>
            <div class="nav-right">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-li">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-li">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-gs">
                            Get Started
                        </a>
                    @endif
                @endauth
            </div>
        </nav>

        <div class="auth-pg">
            {{ $slot }}
        </div>
    </body>
</html>