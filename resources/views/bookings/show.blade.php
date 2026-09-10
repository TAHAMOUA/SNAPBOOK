<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Booking ID</p>
                        <p class="text-lg font-medium text-gray-900">{{ $booking->id_booking }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Service</p>
                        <p class="text-lg text-gray-900">{{ $booking->service->title }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Photographer</p>
                        <p class="text-lg text-gray-900">
                            {{ $booking->service->photographerProfile->user->first_name }}
                            {{ $booking->service->photographerProfile->user->last_name }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Client</p>
                        <p class="text-lg text-gray-900">
                            {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Event Date</p>
                        <p class="text-lg text-gray-900">{{ $booking->event_date->format('Y-m-d') }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Time Slot</p>
                        @if ($booking->availability)
                            <p class="text-lg text-gray-900">
                                {{ $booking->availability->start_time }} - {{ $booking->availability->end_time }}
                            </p>
                        @else
                            <p class="text-lg text-gray-900">Unavailable</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Event Address</p>
                        <p class="text-lg text-gray-900">{{ $booking->event_address }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Total Price</p>
                        <p class="text-lg font-medium text-gray-900">${{ number_format($booking->total_price, 2) }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-lg font-medium text-gray-900">{{ ucfirst($booking->status) }}</p>
                    </div>

                    @php
                        $isClient = auth()->user()->id_user === $booking->id_user;
                        $isPhotographer = auth()->user()->id_user === $booking->service->photographerProfile->id_user;
                        $bookingReview = $booking->reviews->first();
                    @endphp

                    @if ($isClient && $booking->status === 'completed')
                        <div class="mb-6 border-b border-gray-200 pb-4">
                            <p class="text-sm text-gray-500">Your Review</p>

                            @if ($bookingReview)
                                <div class="mt-2">
                                    <p class="text-lg text-gray-900">
                                        {{ $bookingReview->rating }} {{ $bookingReview->rating === 1 ? 'star' : 'stars' }}
                                    </p>
                                    @if ($bookingReview->comment)
                                        <p class="text-gray-900 mt-1">{{ $bookingReview->comment }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $bookingReview->review_date->format('Y-m-d') }}
                                    </p>
                                </div>
                            @else
                                <a
                                    href="{{ route('reviews.create', ['booking' => $booking->id_booking]) }}"
                                    class="inline-block mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                >
                                    Leave a Review
                                </a>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        @if ($isClient && in_array($booking->status, ['pending', 'accepted']))
                            <form
                                action="{{ route('bookings.cancel', $booking->id_booking) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                >
                                    Cancel
                                </button>
                            </form>
                        @endif

                        @if ($isPhotographer && $booking->status === 'pending')
                            <form
                                action="{{ route('bookings.accept', $booking->id_booking) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                >
                                    Accept
                                </button>
                            </form>

                            <form
                                action="{{ route('bookings.reject', $booking->id_booking) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to reject this booking?')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                >
                                    Reject
                                </button>
                            </form>
                        @endif

                        @if ($isPhotographer && $booking->status === 'accepted')
                            <form
                                action="{{ route('bookings.complete', $booking->id_booking) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                                >
                                    Complete
                                </button>
                            </form>
                        @endif

                        <a
                            href="{{ route('bookings.index') }}"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            Back to Bookings
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>