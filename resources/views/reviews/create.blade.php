<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leave a Review
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <p class="text-lg font-medium text-gray-900">{{ $booking->service->title }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $booking->service->photographerProfile->user->first_name }}
                            {{ $booking->service->photographerProfile->user->last_name }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Event Date: {{ $booking->event_date->format('Y-m-d') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Price: ${{ number_format($booking->total_price, 2) }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf

                        <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">

                        <div>
                            <x-input-label for="rating" :value="__('Rating')" />
                            <select
                                id="rating"
                                name="rating"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                                autofocus
                            >
                                <option value="">Select a rating</option>
                                @foreach (range(1, 5) as $value)
                                    <option value="{{ $value }}" {{ (string) old('rating') === (string) $value ? 'selected' : '' }}>
                                        {{ $value }} {{ $value === 1 ? 'star' : 'stars' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="comment" :value="__('Comment (optional)')" />
                            <textarea
                                id="comment"
                                name="comment"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="5"
                                maxlength="2000"
                                placeholder="Share your experience with this photographer"
                            >{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('bookings.show', $booking->id_booking) }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Back
                            </a>
                            <x-primary-button class="ms-4">
                                Submit Review
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>