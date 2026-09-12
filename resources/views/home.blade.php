<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SnapBook - Find a Photographer</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css2?family=Barlow+Condensed:wght@300;500;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Hero Section -->
            <section class="py-20 lg:py-32">
                <div class="hero max-w-1100 mx-auto px-4 lg:px-8 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <p class="eyebrow mb-4">
                            <span>Capture</span>
                            your special moments
                        </p>
                        <h1 class="ht">
                            <em>Find the perfect</em>
                            photographer for your event
                        </h1>
                        <p class="hero-sub mb-6">
                            Browse verified photographers by specialty, location, and style. All profiles are approved and ready to book.
                        </p>

                        <div class="cta-row">
                            <a href="{{ route('photographers.index') }}"
                               class="btn-hero">
                                <span>Get Started</span>
                            </a>
                            <a href="{{ route('photographer-profile.create') }}"
                               class="btn-ol">
                                <span>List Your Services</span>
                            </a>
                        </div>
                    </div>

                    <div class="hv">
                        <div class="hvc hvc-main">
                            <div class="hvi">
                                <span class="hvtag">Wedding</span>
                                <span class="hvname">Photography</span>
                                <span class="hvstars">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </span>
                            </div>
                        </div>
                        <div class="hvc hvc-sm">
                            <div class="hvi">
                                <span class="hvtag">Portrait</span>
                            </div>
                        </div>
                        <div class="hvc hvc-xs">
                            <div class="hvi">
                                <span class="hvtag">Event</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Search and Categories -->
            <section class="mb-20 lg:mb-24">
                <div class="sec">
                    <form method="GET" action="{{ route('photographers.index') }}" class="mb-6">
                        <div class="search-bar">
                            <label for="q" class="sr-only">Name or City</label>
                            <i><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0M10 7v6m4 0v6m7-7h-2a2 2 0 00-2 2v8a2 2 0 002 2h2v-8zm-5.5 3.5v-3m3 3v-3m-3 3v-3m3-3v-3M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h2m4.5-6a2.5 2.5 0 100 5 2.5 2.5 0 000-5z"></path></svg></i>
                            <input
                                type="text"
                                name="q"
                                placeholder="Search by name or city"
                                class="flex-1"
                                value="{{ request('q') }}"
                            >
                            <button type="submit">
                                Search
                            </button>
                        </div>
                    </form>

                    <div class="ftags mb-6">
                        <a href="#" class="ftag on">All categories</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('photographers.index', ['category' => $category->id_category]) }}"
                               class="ftag {{ request('category') == $category->id_category ? 'on' : '' }}">
                                {{ $category->category_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Photographer Grid -->
            <section class="mb-20 lg:mb-24">
                <div class="sec">
                    <p class="sec-lbl">Featured Photographers</p>
                    <h2 class="sec-t">Photographers</h2>
                    <p class="sec-sub">Choose from our verified community of professionals</p>

                    <div class="pg-grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($profiles as $profile)
                            <a
                                href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                                class="pgc"
                            >
                                <div class="pgc-img {{ optional($profile->services)->count() > 0 ? 'a' : 'b' }}">
                                    <div class="hvi">
                                        <span class="hvname">
                                            {{ Str::substr($profile->user->first_name . ' ' . $profile->user->last_name, 0, 15) }}
                                        </span>
                                        <span class="hvstars">
                                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="pgc-body">
                                    <p class="pgc-name">{{ Str::limit($profile->user->first_name . ' ' . $profile->user->last_name, 20) }}</p>
                                    <p class="pgc-spec">{{ $profile->city }}</p>
                                    <div class="pgc-foot">
                                        <span class="pgc-rat">
                                            <span>4.5</span> / 5
                                        </span>
                                        <span class="pgc-price">From {{ number_format($profile->services_min_price ?? 0, 2) }} MAD</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if ($profiles->count() === 0)
                        <p class="text-center mt-8 text-gray-600">No photographers found.</p>
                    @endif
                </div>
            </section>

            <!-- How It Works -->
            <section class="mb-20 lg:mb-24 bg-[var(--void)]">
                <div class="sec">
                    <p class="sec-lbl">How It Works</p>
                    <h2 class="sec-t">Simple as 1-2-3</h2>
                    <p class="sec-sub">Finding and booking a photographer is easy</p>

                    <div class="how-grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="how-item">
                            <p class="how-n">1</p>
                            <p class="how-t">Search & Discover</p>
                            <p class="how-d">Browse photographers by specialty, location, and style using our search and filter tools.</p>
                        </div>
                        <div class="how-item">
                            <p class="how-n">2</p>
                            <p class="how-t">Book Your Session</p>
                            <p class="how-d">Contact the photographer directly and book your session at a price that fits your budget.</p>
                        </div>
                        <div class="how-item">
                            <p class="how-n">3</p>
                            <p class="how-t">Capture Memories</p>
                            <p class="how-d">Enjoy your photography session and receive beautiful images of your special moments.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="border-t border-[var(--bd2)] flex flex-col sm:flex-row items-center justify-between gap-4 px-4 sm:px-8 py-6">
                <a href="/" class="foot-logo">Snap<span>Book</span></a>
                <nav class="flex items-center gap-1 flex-wrap justify-center">
                    <a href="{{ route('photographers.index') }}" class="nb on">Find Photographers</a>
                    <a href="{{ route('dashboard') }}" class="nb">Dashboard</a>
                </nav>
                <p class="foot-copy">© 2026 SnapBook. All rights reserved.</p>
            </footer>
        </main>
    </body>
</html>