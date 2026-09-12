<x-app-layout>
    <div class="ph-wrap">

        <div class="dash-hd">
            <div class="dash-welcome">Welcome back, {{ $user->first_name }}</div>

            @if ($profile)
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('services.index') }}" class="btn-mini btn-mg">Services</a>
                    <a href="{{ route('portfolio.index') }}" class="btn-mini btn-mg">Portfolio</a>
                    <a href="{{ route('availabilities.index') }}" class="btn-mini btn-mg">Availability</a>
                    <a href="{{ route('bookings.index') }}" class="btn-mini btn-mg">Bookings</a>
                    <a href="{{ route('photographer-profile.show', $profile->id_profile) }}" class="btn-mini btn-me">View Profile</a>
                </div>
            @endif
        </div>

        @if (! $profile)

            <div class="ph-panel" style="text-align:center;">
                <div class="ph-pb">
                    <div style="font-size:15px;font-weight:600;color:var(--white);">
                        You don't have a photographer profile yet.
                    </div>
                    <p style="font-size:12px;color:var(--mist);margin-top:6px;">
                        Create your professional profile so clients can find you and book your services.
                    </p>
                    <a
                        href="{{ route('photographer-profile.create') }}"
                        class="btn-book"
                        style="display:inline-block;margin-top:18px;"
                    >
                        Create Photographer Profile
                    </a>
                </div>
            </div>

        @else

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-8">
                <div class="ph-sc">
                    <div class="ph-sn" style="color:var(--ember);">{{ $bookingStats->get('pending', 0) }}</div>
                    <div class="ph-sl">Pending Requests</div>
                </div>
                <div class="ph-sc">
                    <div class="ph-sn" style="color:#5dbf7e;">{{ $bookingStats->get('accepted', 0) }}</div>
                    <div class="ph-sl">Accepted</div>
                </div>
                <div class="ph-sc">
                    <div class="ph-sn">{{ $bookingStats->get('completed', 0) }}</div>
                    <div class="ph-sl">Completed</div>
                </div>
                <div class="ph-sc">
                    <div class="ph-sn">{{ $servicesCount }}</div>
                    <div class="ph-sl">Total Services</div>
                </div>
                <div class="ph-sc">
                    <div class="ph-sn">{{ $portfolioCount }}</div>
                    <div class="ph-sl">Portfolio Photos</div>
                </div>
                <div class="ph-sc">
                    <div class="ph-sn">{{ $reviewsCount }}</div>
                    <div class="ph-sl">Reviews</div>
                    <div class="ph-sd">
                        @if ($reviewsCount > 0)
                            Average {{ number_format($reviewsAverage, 1) }} / 5
                        @else
                            No ratings yet
                        @endif
                    </div>
                </div>
            </div>

            @php
                $labels = [
                    'pending'   => ['Pending',   'gray'],
                    'accepted'  => ['Confirmed', 'orange'],
                    'completed' => ['Completed', 'green'],
                    'cancelled' => ['Cancelled', 'red'],
                    'rejected'  => ['Rejected',  'red'],
                ];
            @endphp

            <div class="ph-grid">

                <section class="ph-panel">
                    <div class="ph-ph">
                        <div class="ph-pt">Recent Booking Requests</div>
                        <a href="{{ route('bookings.index') }}" class="btn-mini btn-mg">View all</a>
                    </div>

                    <div class="ph-pb">
                        @forelse ($recentBookings as $booking)
                            @php
                                [$statusLabel, $statusPill] = $labels[$booking->status] ?? ['Unknown', 'gray'];
                                $initials = strtoupper(
                                    mb_substr($booking->user->first_name, 0, 1) .
                                    mb_substr($booking->user->last_name, 0, 1)
                                );
                            @endphp

                            <div class="req-row">
                                <div class="req-av" style="background:var(--ocean);">{{ $initials }}</div>
                                <div class="req-info">
                                    <div class="req-name">
                                        {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                                    </div>
                                    <a
                                        href="{{ route('bookings.show', $booking->id_booking) }}"
                                        class="req-date block hover:underline hover:text-[var(--ember)]"
                                    >
                                        {{ $booking->service?->title ?? 'Service unavailable' }}
                                        · {{ $booking->event_date->format('M j, Y') }}
                                        · {{ $booking->event_address }}
                                    </a>
                                </div>
                                <div class="req-acts">
                                    <span class="pill {{ $statusPill }}">{{ $statusLabel }}</span>

                                    @if ($booking->status === 'pending')
                                        <form action="{{ route('bookings.accept', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-mini btn-me" style="width:100%;">Accept</button>
                                        </form>
                                        <form action="{{ route('bookings.reject', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                onclick="return confirm('Are you sure you want to reject this booking?')"
                                                class="btn-mini btn-mg"
                                                style="width:100%;"
                                            >
                                                Reject
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'accepted')
                                        <form action="{{ route('bookings.complete', $booking->id_booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-mini btn-me" style="width:100%;">Complete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-8" style="font-size:13px;color:var(--mist);">No booking requests yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="ph-panel">
                    <div class="ph-ph">
                        <div class="ph-pt">Recent services</div>
                        <a href="{{ route('services.create') }}" class="btn-mini btn-me">Add</a>
                    </div>

                    <div class="ph-pb">
                        @forelse ($recentServices as $service)
                            <div class="border-b border-[var(--bd2)] last:border-0 py-2">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="req-name">{{ $service->title }}</p>
                                    <p style="font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:700;color:var(--white);">
                                        {{ number_format($service->price, 2) }} MAD
                                    </p>
                                </div>
                                @if ($service->category)
                                    <p class="req-date">{{ $service->category->category_name }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-center py-8" style="font-size:13px;color:var(--mist);">
                                No services yet.
                                <a href="{{ route('services.create') }}" class="text-[var(--ember)] hover:underline ml-2">
                                    Add your first service
                                </a>
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="ph-panel">
                    <div class="ph-ph">
                        <div class="ph-pt">Recent portfolio</div>
                        <a href="{{ route('portfolio.create') }}" class="btn-mini btn-mg">Upload</a>
                    </div>

                    <div class="ph-pb">
                        @forelse ($recentPortfolio as $photo)
                            <div class="flex items-center gap-3 border-b border-[var(--bd2)] last:border-0 py-2">
                                <div
                                    class="mt"
                                    style="flex-shrink:0;width:64px;height:64px;background-image:url('{{ Storage::url($photo->image) }}');background-size:cover;background-position:center;"
                                ></div>
                                <p style="font-size:12px;color:var(--mist);">{{ $photo->description ?? 'No description' }}</p>
                            </div>
                        @empty
                            <p class="text-center py-8" style="font-size:13px;color:var(--mist);">
                                No portfolio photos yet.
                                <a href="{{ route('portfolio.create') }}" class="text-[var(--ember)] hover:underline ml-2">
                                    Add your first photo
                                </a>
                            </p>
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
                                $reviewer = $review->user;
                            @endphp

                            <div class="border-b border-[var(--bd2)] last:border-0 py-2">
                                <div class="flex items-center justify-between gap-3 mb-1">
                                    <span class="req-name">
                                        {{ $reviewer ? $reviewer->first_name . ' ' . $reviewer->last_name : 'Client' }}
                                    </span>
                                    <span class="req-name">{{ $review->rating }} / 5</span>
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

        @endif

    </div>
</x-app-layout>