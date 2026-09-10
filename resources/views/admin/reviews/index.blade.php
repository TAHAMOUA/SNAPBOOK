<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Reviews
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($reviews->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviewer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photographer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $review->user->first_name }} {{ $review->user->last_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($review->photographerProfile)
                                                    {{ $review->photographerProfile->user->first_name }} {{ $review->photographerProfile->user->last_name }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $review->rating }} {{ $review->rating === 1 ? 'star' : 'stars' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $review->comment }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $review->review_date?->format('Y-m-d') ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No reviews found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>