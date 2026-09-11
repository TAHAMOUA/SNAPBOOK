<x-app-layout>
    <div class="book-wrap">

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm text-[#5dbf7e]">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="book-title">My Bookings</h1>
        <p class="book-sub">
            @if (auth()->user()->role === 'photographer')
                Booking requests for your services.
            @else
                Track and manage your photo sessions.
            @endif
        </p>

        @php
            $isClient = auth()->user()->role === 'client';

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

        @forelse ($bookings as $booking)
            <div class="bk-row">
                <div class="bk-acc {{ $statusAccent[$booking->status] ?? 'done' }}"></div>

                <div class="bk-body">
                    <div>
                        <div class="bk-name">
                            @if ($isClient)
                                {{ $booking->service->photographerProfile->user->first_name }} {{ $booking->service->photographerProfile->user->last_name }}
                            @else
                                {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                            @endif
                            - {{ $booking->service->title }}
                        </div>

                        <div class="bk-det">
                            {{ $booking->event_date->format('M j, Y') }}
                            @if ($booking->availability)
                                · {{ $booking->availability->start_time }} - {{ $booking->availability->end_time }}
                            @endif
                            · {{ $booking->event_address }}
                        </div>
                    </div>

                    <div class="bk-acts">
                        <span class="pill {{ $statusPill[$booking->status] ?? 'gray' }}">
                            {{ $statusLabel[$booking->status] ?? ucfirst($booking->status) }}
                        </span>

                        <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn-mini btn-mg">
                            View
                        </a>

                        @if ($isClient && in_array($booking->status, ['pending', 'accepted']))
                            <form action="{{ route('bookings.cancel', $booking->id_booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                    class="btn-mini btn-mg"
                                >
                                    Cancel
                                </button>
                            </form>
                        @endif

                        @if ($isClient && $booking->status === 'completed')
                            @if ($booking->reviews->isEmpty())
                                <a
                                    href="{{ route('reviews.create', ['booking' => $booking->id_booking]) }}"
                                    class="btn-mini btn-me"
                                >
                                    Leave review
                                </a>
                            @else
                                <span class="btn-mini btn-mg">Reviewed</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="border border-[var(--bd)] bg-[rgba(63,58,66,.2)] rounded-md p-10 text-center">
                <p class="font-medium text-[var(--white)] mb-1">No bookings found.</p>
                <p class="text-[13px] text-[var(--mist)] mb-4">
                    @if ($isClient)
                        Book a photographer and your sessions will appear here.
                    @else
                        Booking requests from clients will appear here.
                    @endif
                </p>
                @if ($isClient)
                    <a href="{{ route('photographers.index') }}" class="btn-mini btn-me">
                        Find a Photographer
                    </a>
                @endif
            </div>
        @endforelse

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>

    </div>
</x-app-layout>