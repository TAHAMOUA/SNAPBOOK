<x-app-layout>
    <div class="prof-wrap">

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm text-[#5dbf7e]">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 border border-[var(--bd)] bg-[rgba(63,58,66,.2)] px-4 py-3 rounded-md text-sm text-[var(--mist)]">
                {{ session('info') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-[auto_1fr_auto] gap-6 items-start mb-8 pb-8 border-b border-[var(--bd2)]">
            <div class="avatar">
                <i class="ti ti-camera" aria-hidden="true"></i>
            </div>

            <div>
                <h1 class="prof-name">
                    {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                </h1>
                <p class="prof-spec">
                    {{ optional($profile->services->first()?->category)?->category_name ?? 'Photographer' }}
                </p>
                @if ($profile->city)
                    <p class="prof-loc">
                        <i class="ti ti-map-pin" aria-hidden="true" style="color:var(--ember);font-size:13px;"></i>
                        {{ $profile->city }}
                    </p>
                @endif
                <div class="prof-stats">
                    <div>
                        <div class="ps-n">{{ $profile->reviews_count }}</div>
                        <div class="ps-l">Reviews</div>
                    </div>
                    <div>
                        <div class="ps-n">{{ $profile->reviews_count ? number_format($profile->reviews_avg_rating, 1) : '—' }}</div>
                        <div class="ps-l">Rating</div>
                    </div>
                    <div>
                        <div class="ps-n">{{ $profile->experience !== null ? $profile->experience.'y' : '—' }}</div>
                        <div class="ps-l">Experience</div>
                    </div>
                </div>
            </div>

            <div class="prof-cta items-start">
                @if ((string) auth()->id() === $profile->id_user)
                    <a href="{{ route('photographer-profile.edit', $profile->id_profile) }}" class="btn-book">
                        Edit Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-msg">
                        Back to Dashboard
                    </a>
                @elseif ($profile->validation_status === 'approved' && $profile->services->isNotEmpty())
                    @if (auth()->user()?->role === 'client')
                        <a href="{{ route('bookings.create', ['service' => $profile->services->first()->id_service]) }}" class="btn-book">
                            Book a session
                        </a>
                        <a href="{{ route('photographers.index') }}" class="btn-msg">
                            Back to photographers
                        </a>
                    @else
                        <a href="{{ route('photographers.index') }}" class="btn-msg">
                            Back to photographers
                        </a>
                    @endif
                @else
                    <a href="{{ route('photographers.index') }}" class="btn-msg">
                        Back to photographers
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">

            <div class="space-y-8">
                <section>
                    <h2 class="ptab">Portfolio</h2>
                    @if ($profile->portfolios->count())
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-6">
                            @foreach ($profile->portfolios as $photo)
                                <div class="pt" style="background-image:url('{{ Storage::url($photo->image) }}');background-size:cover;background-position:center;"></div>
                            @endforeach
                        </div>
                    @else
                        <p class="bio">No portfolio photos published yet.</p>
                    @endif
                </section>

                <section>
                    <h2 class="ptab">About</h2>
                    @if ($profile->bio)
                        <p class="bio whitespace-pre-wrap">{{ $profile->bio }}</p>
                    @else
                        <p class="bio">{{ $profile->user->first_name }} hasn't written their bio yet.</p>
                    @endif
                </section>

                <section>
                    <h2 class="ptab">Client reviews</h2>
                    @forelse ($profile->reviews as $review)
                        <div class="rev-card">
                            <div class="rev-top">
                                <div class="rev-name">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                                <div class="rev-stars">
                                    {{ str_repeat('★', $review->rating) }}<span class="opacity-40">{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="rev-txt">{{ $review->comment }}</p>
                            @endif
                            <p class="text-[11px] text-[var(--mist)] opacity-70 mt-1">
                                {{ $review->review_date->format('M j, Y') }}
                            </p>
                        </div>
                    @empty
                        <p class="bio">No reviews yet.</p>
                    @endforelse
                </section>
            </div>

            <div class="space-y-6">
                <section>
                    <h2 class="ptab">Services</h2>
                    @forelse ($profile->services as $service)
                        <div class="serv-item">
                            <div>
                                <div class="sname">{{ $service->title }}</div>
                                <div class="sdesc">{{ $service->description }}</div>
                            </div>
                            <div class="sprice">${{ number_format($service->price, 2) }}</div>
                        </div>
                    @empty
                        <p class="bio">No services listed yet.</p>
                    @endforelse
                </section>

                <section>
                    <h2 class="ptab">Availability</h2>
                    @if ($profile->availabilities->count())
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach ($profile->availabilities->sortBy('available_date')->take(14) as $slot)
                                <span class="inline-flex items-center gap-1 rounded-[2px] border border-[rgba(45,138,78,.28)] bg-[rgba(45,138,78,.1)] px-2 py-1 text-[11px] text-[#5dbf7e]">
                                    <i class="ti ti-calendar"></i>
                                    {{ $slot->available_date->format('M j') }} &middot; {{ $slot->start_time }}–{{ $slot->end_time }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="bio">No upcoming availability posted.</p>
                    @endif

                    @if ($profile->validation_status === 'approved' && $profile->services->isNotEmpty() && auth()->user()?->role === 'client')
                        <a href="{{ route('bookings.create', ['service' => $profile->services->first()->id_service]) }}" class="btn-book" style="width:100%;margin-top:1.2rem;">
                            Book {{ $profile->user->first_name }}
                        </a>
                    @endif
                </section>
            </div>

        </div>
    </div>
</x-app-layout>