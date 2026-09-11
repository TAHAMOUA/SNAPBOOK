<x-app-layout>
    <div class="dash-wrap" style="max-width:760px;">

        <div class="admin-top">
            <div>
                <div class="admin-title">Photographer Profile</div>
                <div style="font-size:12px;color:var(--mist);">{{ $profile->user->first_name }} {{ $profile->user->last_name }}</div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 border border-[rgba(163,48,48,.35)] bg-[rgba(163,48,48,.1)] px-4 py-3 rounded-md text-sm" style="color:#c97070;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="apanel mb-6">
            <div class="ap-h"><div class="ap-t">Details</div></div>
            <div class="overflow-x-auto">
                <table class="atbl">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:38%;">Profile ID</th>
                            <td class="text-[var(--white)]">{{ $profile->id_profile }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Photographer</th>
                            <td class="text-[var(--white)]">{{ $profile->user->first_name }} {{ $profile->user->last_name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td class="text-[var(--mist)]">{{ $profile->user->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Validation Status</th>
                            <td>
                                <span class="pill
                                    @if ($profile->validation_status === 'approved') green
                                    @elseif ($profile->validation_status === 'rejected') red
                                    @else orange @endif
                                ">
                                    {{ ucfirst($profile->validation_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Bio</th>
                            <td class="text-[var(--white)] whitespace-pre-wrap">{{ $profile->bio ?? 'No bio provided.' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">City</th>
                            <td class="text-[var(--mist)]">{{ $profile->city ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Years of Experience</th>
                            <td class="text-[var(--mist)]">{{ $profile->experience ?? 'Not specified' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="apanel mb-6">
            <div class="ap-h"><div class="ap-t">Reviews</div></div>

            @if ($profile->reviews->count())
                <div>
                    @foreach ($profile->reviews as $review)
                        <div class="px-4 py-3 border-b border-[rgba(118,130,142,.06)] last:border-b-0">
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <span class="font-medium" style="font-size:14px;color:var(--ember);">
                                    {{ $review->rating }} {{ $review->rating === 1 ? 'star' : 'stars' }}
                                </span>
                                <span style="font-size:12px;color:var(--mist);">
                                    {{ $review->user->first_name }} {{ $review->user->last_name }}
                                    &middot; {{ $review->review_date->format('Y-m-d') }}
                                </span>
                            </div>
                            @if ($review->comment)
                                <p class="mt-1" style="font-size:13px;color:var(--mist);">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-10 text-center">
                    <p style="font-size:13px;color:var(--mist);">No reviews yet.</p>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @if ($profile->validation_status === 'pending')
                <form method="POST" action="{{ route('admin.photographers.approve', $profile->id_profile) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-mini btn-me">Approve</button>
                </form>

                <form method="POST" action="{{ route('admin.photographers.reject', $profile->id_profile) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-mini btn-mg" style="color:#c97070;border-color:rgba(163,48,48,.3);">Reject</button>
                </form>
            @endif

            <a href="{{ route('admin.photographers.index') }}" class="btn-mini btn-mg">
                Back to Profiles
            </a>
        </div>

    </div>
</x-app-layout>