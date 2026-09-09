<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Book Service
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6 border-b border-gray-200 pb-4">
                        <p class="text-lg font-medium text-gray-900">{{ $service->title }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $service->photographerProfile->user->first_name }} {{ $service->photographerProfile->user->last_name }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Price: ${{ number_format($service->price, 2) }}
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($availabilities->count())
                        <form method="POST" action="{{ route('bookings.store') }}">
                            @csrf

                            <input type="hidden" name="id_service" value="{{ $service->id_service }}">

                            <div>
                                <x-input-label for="id_availability" :value="__('Available Slot')" />
                                <select
                                    id="id_availability"
                                    name="id_availability"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                    autofocus
                                >
                                    <option value="">Select a time slot</option>
                                    @foreach ($availabilities->groupBy(fn ($slot) => $slot->available_date->format('Y-m-d')) as $date => $slots)
                                        <optgroup label="{{ $date }}">
                                            @foreach ($slots as $slot)
                                                <option value="{{ $slot->id_availability }}" {{ old('id_availability') === $slot->id_availability ? 'selected' : '' }}>
                                                    {{ $slot->start_time }} - {{ $slot->end_time }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_availability')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="event_address" :value="__('Event Address')" />
                                <x-text-input
                                    id="event_address"
                                    class="block mt-1 w-full"
                                    type="text"
                                    name="event_address"
                                    :value="old('event_address')"
                                    maxlength="255"
                                    required
                                />
                                <x-input-error :messages="$errors->get('event_address')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a
                                    href="{{ route('services.show', $service->id_service) }}"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    Back
                                </a>
                                <x-primary-button class="ms-4">
                                    Confirm Booking
                                </x-primary-button>
                            </div>
                        </form>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            This photographer currently has no available time slots.
                            <a href="{{ route('services.show', $service->id_service) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                Back to Service
                            </a>
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>