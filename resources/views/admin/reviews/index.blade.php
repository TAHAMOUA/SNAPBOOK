<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">All Reviews</div>
                <div style="font-size:12px;color:var(--mist);">Feedback left by clients.</div>
            </div>
        </div>

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }}</div>
            </div>

            @if ($reviews->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Reviewer</th>
                                <th scope="col">Photographer</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $review->user->first_name }} {{ $review->user->last_name }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        @if ($review->photographerProfile)
                                            {{ $review->photographerProfile->user->first_name }} {{ $review->photographerProfile->user->last_name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td style="color:var(--ember);">
                                        {{ $review->rating }} {{ $review->rating === 1 ? 'star' : 'stars' }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $review->comment }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $review->review_date?->format('Y-m-d') ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No reviews found.</p>
                    <p style="font-size:13px;color:var(--mist);">Reviews will appear here once clients rate completed bookings.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>