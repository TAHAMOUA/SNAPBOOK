<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Leave a Review</div>
        </div>

        @if (session('error'))
            <div class="mb-6 border border-[rgba(163,48,48,.35)] bg-[rgba(163,48,48,.1)] px-4 py-3 rounded-md text-sm" style="color:#c97070;">
                {{ session('error') }}
            </div>
        @endif

        <div class="sum-box">
            <div class="sum-row"><span class="sum-lbl">Service</span><span style="color:var(--white);">{{ $booking->service->title }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Photographer</span><span style="color:var(--white);">{{ $booking->service->photographerProfile->user->first_name }} {{ $booking->service->photographerProfile->user->last_name }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Event Date</span><span style="color:var(--white);">{{ $booking->event_date->format('Y-m-d') }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Price</span><span style="color:var(--ember);">${{ number_format($booking->total_price, 2) }}</span></div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('reviews.store') }}">
                    @csrf

                    <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">

                    <div class="fld">
                        <x-input-label for="rating" :value="__('Rating')" />
                        <select
                            id="rating"
                            name="rating"
                            class="sel mt-1"
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

                    <div class="fld">
                        <x-input-label for="comment" :value="__('Comment (optional)')" />
                        <textarea
                            id="comment"
                            name="comment"
                            class="ta mt-1"
                            rows="5"
                            maxlength="2000"
                            placeholder="Share your experience with this photographer"
                        >{{ old('comment') }}</textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('bookings.show', $booking->id_booking) }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Back
                        </a>

                        <x-primary-button>
                            Submit Review
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>