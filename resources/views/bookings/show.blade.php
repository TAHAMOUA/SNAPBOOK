<x-app-layout>
    <div class="book-wrap">

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm text-[#5dbf7e]">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="book-title">Booking Details</h1>
        <p class="book-sub">{{ $booking->id_booking }}</p>

        @php
            $isClient = (string) auth()->id() === $booking->id_user;
            $isPhotographer = (string) auth()->id() === $booking->service->photographerProfile->id_user;
            $bookingReview = $booking->reviews->first();

            $statusAccent = [
                'pending'   => 'up',
                'accepted'  => 'up',
                'completed' => 'done',
                'rejected'  => 'can',
                'cancelled' => 'can',
            ];

            $statusPill = [
                'pending'   => 'gray',
                'accepted'  => 'orange',
                'completed' => 'green',
                'rejected'  => 'red',
                'cancelled' => 'red',
            ];

            $statusLabel = [
                'pending'   => 'Pending',
                'accepted'  => 'Confirmed',
                'completed' => 'Completed',
                'rejected'  => 'Declined',
                'cancelled' => 'Cancelled',
            ];
        @endphp

        <div class="mb-6">
            <span class="pill {{ $statusPill[$booking->status] ?? 'gray' }}">
                {{ $statusLabel[$booking->status] ?? ucfirst($booking->status) }}
            </span>
        </div>

        <div class="sum-box">
            <div class="sum-row"><span class="sum-lbl">Service</span><span>{{ $booking->service->title }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Photographer</span><span>{{ $booking->service->photographerProfile->user->first_name }} {{ $booking->service->photographerProfile->user->last_name }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Client</span><span>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</span></div>
            <div class="sum-row"><span class="sum-lbl">Event date</span><span>{{ $booking->event_date->format('M j, Y') }}</span></div>
            <div class="sum-row">
                <span class="sum-lbl">Time slot</span>
                <span>
                    @if ($booking->availability)
                        {{ $booking->availability->start_time }} - {{ $booking->availability->end_time }}
                    @else
                        Unavailable
                    @endif
                </span>
            </div>
            <div class="sum-row"><span class="sum-lbl">Event address</span><span>{{ $booking->event_address }}</span></div>
            @if ($booking->booking_date)
                <div class="sum-row"><span class="sum-lbl">Requested on</span><span>{{ $booking->booking_date->format('M j, Y') }}</span></div>
            @endif
            <div class="sum-row"><span class="sum-lbl">Total</span><span class="sum-total">{{ number_format($booking->total_price, 2) }} MAD</span></div>
        </div>

        @if ($isClient && $booking->status === 'completed')
            <div class="ptab">Your review</div>

            @if ($bookingReview)
                <div class="rev-card">
                    <div class="rev-top">
                        <span class="rev-name">{{ $bookingReview->rating }} / 5</span>
                        <span class="rev-stars">{{ str_repeat('★', $bookingReview->rating) }}</span>
                    </div>
                    @if ($bookingReview->comment)
                        <p class="rev-txt">{{ $bookingReview->comment }}</p>
                    @endif
                </div>
            @else
                <a href="{{ route('reviews.create', ['booking' => $booking->id_booking]) }}" class="btn-book">
                    Leave a Review
                </a>
            @endif
        @endif

        <div class="flex flex-wrap items-center gap-2 mt-8 pt-6 border-t border-[var(--bd2)]">
            @if ($isClient && in_array($booking->status, ['pending', 'accepted']))
                <form action="{{ route('bookings.cancel', $booking->id_booking) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                        class="btn-msg"
                    >
                        Cancel Booking
                    </button>
                </form>
            @endif

            @if ($isPhotographer && $booking->status === 'pending')
                <form action="{{ route('bookings.accept', $booking->id_booking) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-book">
                        Accept
                    </button>
                </form>

                <form action="{{ route('bookings.reject', $booking->id_booking) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to reject this booking?')"
                        class="btn-msg"
                    >
                        Reject
                    </button>
                </form>
            @endif

            @if ($isPhotographer && $booking->status === 'accepted')
                <form action="{{ route('bookings.complete', $booking->id_booking) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-book">
                        Complete
                    </button>
                </form>
            @endif

            <a href="{{ route('bookings.index') }}" class="btn-msg">
                Back to Bookings
            </a>
        </div>

    </div>
</x-app-layout>