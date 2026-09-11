<x-app-layout>
    <div class="dash-wrap">

        <div class="dash-hd">
            <div class="dash-welcome">Welcome back, {{ $user->first_name }}</div>
            <a href="{{ route('bookings.index') }}" class="btn-book">
                My Bookings
            </a>
        </div>

        @php
            $statusCards = [
                'pending'   => ['Pending',   'text-[var(--ember)]'],
                'accepted'  => ['Confirmed', 'text-[#5dbf7e]'],
                'completed' => ['Completed', 'text-[var(--white)]'],
                'cancelled' => ['Cancelled', 'text-[#c97070]'],
            ];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
            @foreach ($statusCards as $status => [$label, $color])
                <div class="kpi">
                    <div class="kpi-n {{ $color }}">{{ $statusCounts->get($status, 0) }}</div>
                    <div class="kpi-l">{{ $label }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <section class="ph-panel">
                <div class="ph-ph">
                    <div class="ph-pt">Recent bookings</div>
                    <a href="{{ route('bookings.index') }}" class="btn-mini btn-mg">View all</a>
                </div>

                <div class="ph-pb">
                    @php
                        $labels = [
                            'pending'   => ['Pending',   'gray'],
                            'accepted'  => ['Confirmed', 'orange'],
                            'completed' => ['Completed', 'green'],
                            'cancelled' => ['Cancelled', 'red'],
                            'rejected'  => ['Rejected',  'red'],
                        ];
                        $accents = [
                            'pending'   => 'up',
                            'accepted'  => 'up',
                            'completed' => 'done',
                            'cancelled' => 'can',
                            'rejected'  => 'can',
                        ];
                    @endphp

                    @forelse ($bookings as $booking)
                        @php
                            $photographer = $booking->service?->photographerProfile?->user;
                            [$statusLabel, $statusPill] = $labels[$booking->status] ?? ['Unknown', 'gray'];
                            $canReview = $booking->status === 'completed'
                                && $booking->reviews_count === 0
                                && $booking->service
                                && $booking->service->photographerProfile;
                        @endphp

                        <div class="bk-row">
                            <div class="bk-acc {{ $accents[$booking->status] ?? 'done' }}"></div>
                            <div class="bk-body">
                                <div>
                                    <div class="bk-name">
                                        {{ $photographer ? $photographer->first_name . ' ' . $photographer->last_name : 'Photographer unavailable' }}
                                        - {{ $booking->service?->title ?? 'Service unavailable' }}
                                    </div>
                                    <div class="bk-det">
                                        {{ $booking->event_date->format('M j, Y') }} · {{ $booking->event_address }}
                                    </div>

                                    @if ($canReview)
                                        <a
                                            href="{{ route('reviews.create', ['booking' => $booking->id_booking]) }}"
                                            class="btn-mini btn-me mt-2"
                                        >
                                            Leave review
                                        </a>
                                    @endif
                                </div>

                                <div class="bk-acts">
                                    <span class="pill {{ $statusPill }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-8" style="font-size:13px;color:var(--mist);">No bookings yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="ph-panel">
                <div class="ph-ph">
                    <div class="ph-pt">Recent reviews</div>
                </div>

                <div class="ph-pb">
                    @forelse ($recentReviews as $review)
                        @php
                            $reviewer = $review->photographerProfile?->user;
                        @endphp

                        <div class="border-b border-[var(--bd2)] last:border-0 py-2">
                            <div class="flex justify-between items-center gap-3 mb-1">
                                <span class="rev-name">
                                    {{ $reviewer ? $reviewer->first_name . ' ' . $reviewer->last_name : 'Photographer' }}
                                </span>
                                <span class="rev-name">{{ $review->rating }} / 5</span>
                            </div>
                            @if ($review->comment)
                                <p class="rev-txt">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-center py-8" style="font-size:13px;color:var(--mist);">No reviews yet.</p>
                    @endforelse
                </div>
            </section>

        </div>

    </div>
</x-app-layout>