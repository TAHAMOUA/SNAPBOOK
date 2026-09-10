<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Find a Photographer - {{ config('app.name', 'SnapBook') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css2?family=Barlow+Condensed:wght@300;500;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <a href="/" class="flex items-center">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700">
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
                    <h1 class="text-3xl font-bold text-gray-900">
                        Find a Photographer
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Search by specialty, location, or name.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <form method="GET" action="{{ route('photographers.index') }}" class="grid gap-4 sm:grid-cols-12 sm:items-end">
                        <div class="sm:col-span-5">
                            <label for="q" class="block text-sm font-medium text-gray-700">
                                Name or City
                            </label>
                            <input
                                type="text"
                                id="q"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="e.g. Anna, Smith, Paris"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <div class="sm:col-span-5">
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Category
                            </label>
                            <select
                                id="category"
                                name="category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_category }}" @selected(request('category') === $category->id_category)>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <button
                                type="submit"
                                class="w-full px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700"
                            >
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                @if ($profiles->count())
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($profiles as $profile)
                            <a
                                href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                                class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition"
                            >
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                                </h2>

                                <div class="mt-3 space-y-1 text-sm text-gray-600">
                                    @if ($profile->city)
                                        <p>{{ $profile->city }}</p>
                                    @endif

                                    @if ($profile->experience !== null)
                                        <p>{{ $profile->experience }} years of experience</p>
                                    @endif

                                    @if ($profile->services_min_price !== null)
                                        <p>From ${{ number_format($profile->services_min_price, 2) }}</p>
                                    @endif

                                    @if ($profile->reviews_count)
                                        <p>
                                            {{ round($profile->reviews_avg_rating, 1) }} / 5
                                            ({{ $profile->reviews_count }} {{ $profile->reviews_count === 1 ? 'review' : 'reviews' }})
                                        </p>
                                    @else
                                        <p>No reviews yet</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $profiles->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <p class="text-lg font-medium text-gray-900">No photographers found.</p>
                        <p class="mt-2 text-gray-600">Try adjusting your search or category filter.</p>
                    </div>
                @endif
            </main>
        </div>
    </body>
</html>