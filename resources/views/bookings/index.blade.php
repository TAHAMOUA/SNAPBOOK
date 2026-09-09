<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Bookings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($bookings->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        @if (auth()->user()->role === 'photographer')
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Client
                                            </th>
                                        @endif

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Service
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Event Date
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Time Slot
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Address
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Price
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            @if (auth()->user()->role === 'photographer')
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                                                </td>
                                            @endif

                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $booking->service->title }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $booking->event_date->format('Y-m-d') }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if ($booking->availability)
                                                    {{ $booking->availability->start_time }} - {{ $booking->availability->end_time }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $booking->event_address }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                ${{ number_format($booking->total_price, 2) }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ ucfirst($booking->status) }}
                                            </td>

                                            <td class="px-6 py-4 text-right text-sm">
                                                <a
                                                    href="{{ route('bookings.show', $booking->id_booking) }}"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            No bookings found.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>