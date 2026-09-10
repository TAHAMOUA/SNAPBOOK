<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Bookings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($bookings->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photographer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->id_booking }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $booking->service?->title ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($booking->service?->photographerProfile)
                                                    {{ $booking->service->photographerProfile->user->first_name }} {{ $booking->service->photographerProfile->user->last_name }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $booking->event_date?->format('Y-m-d') ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if ($booking->status === 'accepted' || $booking->status === 'completed') bg-green-100 text-green-800
                                                    @elseif ($booking->status === 'rejected' || $booking->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif
                                                ">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                                ${{ $booking->total_price }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No bookings found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>