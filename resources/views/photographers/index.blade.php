<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Find a Photographer - SnapBook</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css2?family=Barlow+Condensed:wght@300;500;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <header class="bg-[var(--void)] border-b border-[var(--bd)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="/" class="logo">Snap<span>Book</span></a>
                    <div class="flex items-center gap-3">
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
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="mb-8">
                <p class="eyebrow mb-0">
                    <span>Explore</span>
                </p>
                <h1 class="sec-t">Find a Photographer</h1>
                <p class="sec-sub">Search by name, city, or specialty.</p>
            </div>

            <div class="mb-10">
                <form method="GET" action="{{ route('photographers.index') }}" class="mb-5">
                    <div class="search-bar">
                        <label for="q" class="sr-only">Name or City</label>
                        <i><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></i>
                        <input
                            type="text"
                            id="q"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search by name or city"
                        >
                        <button type="submit">Search</button>
                    </div>
                </form>

                <div class="ftags">
                    <a
                        href="{{ route('photographers.index') }}"
                        class="ftag {{ request('category') === '' || request('category') === null ? 'on' : '' }}"
                    >
                        All categories
                    </a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('photographers.index', ['category' => $category->id_category]) }}"
                            class="ftag {{ request('category') === $category->id_category ? 'on' : '' }}"
                        >
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($profiles->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($profiles as $profile)
                        <a
                            href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                            class="pgc"
                        >
                            <div class="pgc-img {{ optional($profile->services)->count() ? 'a' : 'b' }}">
                                <div class="hvi">
                                    <span class="hvname">
                                        {{ Str::limit($profile->user->first_name . ' ' . $profile->user->last_name, 20) }}
                                    </span>
                                    <span class="hvstars">
                                        @if ($profile->reviews_count)
                                            {{ str_repeat('★', min(5, (int) round((float) $profile->reviews_avg_rating))) }}
                                        @else
                                            New
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="pgc-body">
                                <p class="pgc-name">
                                    {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                                </p>
                                <p class="pgc-spec">{{ $profile->city }}</p>
                                <div class="pgc-foot">
                                    <span class="pgc-rat">
                                        @if ($profile->reviews_count)
                                            {{ number_format($profile->reviews_avg_rating, 1) }} / 5
                                        @else
                                            No reviews yet
                                        @endif
                                    </span>
                                    <span class="pgc-price">
                                        {{ $profile->services_min_price !== null ? '$' . number_format($profile->services_min_price, 2) : '—' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $profiles->links() }}
                </div>
            @else
                <div class="border border-[var(--bd)] rounded-md bg-[rgba(63,58,66,.2)] p-12 text-center">
                    <p class="text-lg font-medium text-[var(--white)]">No photographers found.</p>
                    <p class="mt-2 text-[var(--mist)]">Try adjusting your search or category filter.</p>
                </div>
            @endif
        </main>

        <footer class="border-t border-[var(--bd2)] flex flex-col sm:flex-row items-center justify-between gap-4 px-4 sm:px-8 py-6">
            <a href="/" class="foot-logo">Snap<span>Book</span></a>
            <nav class="flex items-center gap-1 flex-wrap justify-center">
                <a href="{{ route('photographers.index') }}" class="nb on">Find Photographers</a>
                <a href="{{ route('dashboard') }}" class="nb">Dashboard</a>
            </nav>
            <p class="foot-copy">© 2026 SnapBook. All rights reserved.</p>
        </footer>
    </body>
</html>