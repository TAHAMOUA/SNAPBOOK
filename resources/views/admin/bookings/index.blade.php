<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">All Bookings</div>
                <div style="font-size:12px;color:var(--mist);">Every booking across the platform.</div>
            </div>
        </div>

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $bookings->count() }} {{ $bookings->count() === 1 ? 'booking' : 'bookings' }}</div>
            </div>

            @if ($bookings->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Booking ID</th>
                                <th scope="col">Client</th>
                                <th scope="col">Service</th>
                                <th scope="col">Photographer</th>
                                <th scope="col">Event Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td style="color:var(--mist);">{{ $booking->id_booking }}</td>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $booking->service?->title ?? '—' }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        @if ($booking->service?->photographerProfile)
                                            {{ $booking->service->photographerProfile->user->first_name }} {{ $booking->service->photographerProfile->user->last_name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $booking->event_date?->format('Y-m-d') ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="pill
                                            @if ($booking->status === 'accepted' || $booking->status === 'completed') green
                                            @elseif ($booking->status === 'rejected' || $booking->status === 'cancelled') red
                                            @else orange @endif
                                        ">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right font-medium" style="color:var(--ember);font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:700;">
                                        ${{ $booking->total_price }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No bookings found.</p>
                    <p style="font-size:13px;color:var(--mist);">Bookings will appear here once clients request sessions.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>