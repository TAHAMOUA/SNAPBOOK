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
                <p class="text-gray-600 mt-1">Here is a summary of your bookings and reviews.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-8">
                <a
                    href="{{ route('bookings.index') }}"
                    class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700"
                >
                    My Bookings
                </a>
                <a
                    href="{{ route('profile.edit') }}"
                    class="inline-block px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300"
                >
                    Edit Profile
                </a>
            </div>

            @php
                $statusCards = [
                    'pending'   => ['Pending',   'bg-yellow-100 text-yellow-800'],
                    'accepted'  => ['Confirmed', 'bg-green-100 text-green-800'],
                    'completed' => ['Completed', 'bg-blue-100 text-blue-800'],
                    'cancelled' => ['Cancelled', 'bg-red-100 text-red-800'],
                ];
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                @foreach ($statusCards as $status => [$label, $color])
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">{{ $label }}</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $statusCounts->get($status, 0) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <section class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Bookings</h3>

                        @forelse ($bookings as $booking)
                            @php
                                $photographer = $booking->service?->photographerProfile?->user;
                                $labels = [
                                    'pending'   => ['Pending',   'bg-yellow-100 text-yellow-800'],
                                    'accepted'  => ['Confirmed', 'bg-green-100 text-green-800'],
                                    'completed' => ['Completed', 'bg-blue-100 text-blue-800'],
                                    'cancelled' => ['Cancelled', 'bg-red-100 text-red-800'],
                                    'rejected'  => ['Rejected',  'bg-gray-100 text-gray-800'],
                                ];
                                [$statusLabel, $statusColor] = $labels[$booking->status] ?? ['Unknown', 'bg-gray-100 text-gray-800'];
                                $canReview = $booking->status === 'completed'
                                    && $booking->reviews_count === 0
                                    && $booking->service
                                    && $booking->service->photographerProfile;
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
                                    {{ $photographer
                                        ? $photographer->first_name . ' ' . $photographer->last_name
                                        : 'Photographer unavailable' }}
                                    · {{ $booking->event_date->format('M j, Y') }}
                                    · {{ $booking->event_address }}
                                </p>

                                @if ($canReview)
                                    <a
                                        href="{{ route('reviews.create', ['booking' => $booking->id_booking]) }}"
                                        class="inline-block mt-2 px-3 py-1 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700"
                                    >
                                        Leave a Review
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">No bookings yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Recent Reviews</h3>

                        @forelse ($recentReviews as $review)
                            @php
                                $reviewer = $review->photographerProfile?->user;
                            @endphp

                            <div class="border-b border-gray-200 last:border-0 py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-medium text-sm text-gray-900">
                                        {{ $reviewer
                                            ? $reviewer->first_name . ' ' . $reviewer->last_name
                                            : 'Photographer' }}
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

        </div>
    </div>
</x-app-layout>