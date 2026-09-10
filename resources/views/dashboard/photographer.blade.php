<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">
                    Welcome back, {{ $user->first_name }}
                </h3>
                <p class="text-gray-600 mt-1">Here is an overview of your bookings and business.</p>
            </div>

            @if (! $profile)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-900 font-medium">
                            You don't have a photographer profile yet.
                        </p>
                        <p class="text-gray-600 mt-1">
                            Create your professional profile so clients can find you and book your services.
                        </p>
                        <a
                            href="{{ route('photographer-profile.create') }}"
                            class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700"
                        >
                            Create Photographer Profile
                        </a>
                    </div>
                </div>
            @else

                <div class="flex flex-wrap items-center gap-3 mb-8">
                    <a
                        href="{{ route('services.index') }}"
                        class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700"
                    >
                        Manage Services
                    </a>
                    <a
                        href="{{ route('portfolio.index') }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300"
                    >
                        Manage Portfolio
                    </a>
                    <a
                        href="{{ route('availabilities.index') }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300"
                    >
                        Manage Availability
                    </a>
                    <a
                        href="{{ route('bookings.index') }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300"
                    >
                        View Bookings
                    </a>
                    <a
                        href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300"
                    >
                        View Profile
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Pending Requests</p>
                            <p class="mt-1 text-2xl font-bold text-yellow-600">
                                {{ $bookingStats->get('pending', 0) }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Accepted</p>
                            <p class="mt-1 text-2xl font-bold text-green-600">
                                {{ $bookingStats->get('accepted', 0) }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Completed</p>
                            <p class="mt-1 text-2xl font-bold text-blue-600">
                                {{ $bookingStats->get('completed', 0) }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Total Services</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $servicesCount }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Portfolio Photos</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $portfolioCount }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Reviews</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $reviewsCount }}</p>
                            <p class="text-sm text-gray-500">
                                @if ($reviewsCount > 0)
                                    Average {{ number_format($reviewsAverage, 1) }} / 5
                                @else
                                    No ratings yet
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <section class="bg-white shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Booking Requests</h3>

                        @forelse ($recentBookings as $booking)
                            @php
                                $labels = [
                                    'pending'   => ['Pending',   'bg-yellow-100 text-yellow-800'],
                                    'accepted'  => ['Confirmed', 'bg-green-100 text-green-800'],
                                    'completed' => ['Completed', 'bg-blue-100 text-blue-800'],
                                    'cancelled' => ['Cancelled', 'bg-red-100 text-red-800'],
                                    'rejected'  => ['Rejected',  'bg-gray-100 text-gray-800'],
                                ];
                                [$statusLabel, $statusColor] = $labels[$booking->status] ?? ['Unknown', 'bg-gray-100 text-gray-800'];
                            @endphp

                            <div class="border-b border-gray-200 last:border-0 py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center justify-between gap-3">
                                    <a
                                        href="{{ route('bookings.show', $booking->id_booking) }}"
                                        class="font-medium text-gray-900 hover:text-indigo-700"
                                    >
                                        {{ $booking->service?->title ?? 'Service unavailable' }}
                                    </a>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                                    · {{ $booking->event_date->format('M j, Y') }}
                                    · {{ $booking->event_address }}
                                </p>

                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    @if ($booking->status === 'pending')
                                        <form action="{{ route('bookings.accept', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                                Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('bookings.reject', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                onclick="return confirm('Are you sure you want to reject this booking?')"
                                                class="px-3 py-1 bg-red-600 text-white text-xs rounded-md hover:bg-red-700"
                                            >
                                                Reject
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'accepted')
                                        <form action="{{ route('bookings.complete', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700">
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No booking requests yet.</p>
                        @endforelse
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <section class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Services</h3>

                            @forelse ($recentServices as $service)
                                <div class="border-b border-gray-200 last:border-0 py-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-medium text-sm text-gray-900">{{ $service->title }}</p>
                                        <p class="text-sm text-gray-600">${{ number_format($service->price, 2) }}</p>
                                    </div>
                                    @if ($service->category)
                                        <p class="text-sm text-gray-500 mt-1">{{ $service->category->category_name }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">
                                    No services yet.
                                    <a href="{{ route('services.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                        Add your first service
                                    </a>
                                </p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Portfolio</h3>

                            @forelse ($recentPortfolio as $photo)
                                <div class="flex items-center gap-4 border-b border-gray-200 last:border-0 py-3 first:pt-0 last:pb-0">
                                    <img
                                        src="{{ Storage::url($photo->image) }}"
                                        alt="Portfolio image"
                                        class="w-16 h-16 object-cover rounded-md"
                                    >
                                    <p class="text-sm text-gray-600">{{ $photo->description ?? 'No description' }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">
                                    No portfolio photos yet.
                                    <a href="{{ route('portfolio.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                        Add your first photo
                                    </a>
                                </p>
                            @endforelse
                        </div>
                    </section>

                    <section class="bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Reviews</h3>

                            @forelse ($recentReviews as $review)
                                @php
                                    $reviewer = $review->user;
                                @endphp

                                <div class="border-b border-gray-200 last:border-0 py-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-medium text-sm text-gray-900">
                                            {{ $reviewer
                                                ? $reviewer->first_name . ' ' . $reviewer->last_name
                                                : 'Client' }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $review->rating }} / 5</p>
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No reviews yet.</p>
                            @endforelse
                        </div>
                    </section>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>