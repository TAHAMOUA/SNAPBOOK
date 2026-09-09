<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Availability Slot
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('availabilities.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="available_date" :value="__('Available Date')" />
                            <x-text-input
                                id="available_date"
                                class="block mt-1 w-full"
                                type="date"
                                name="available_date"
                                :value="old('available_date')"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('available_date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input
                                id="start_time"
                                class="block mt-1 w-full"
                                type="time"
                                name="start_time"
                                :value="old('start_time')"
                                required
                            />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input
                                id="end_time"
                                class="block mt-1 w-full"
                                type="time"
                                name="end_time"
                                :value="old('end_time')"
                                required
                            />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('availabilities.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </a>
                            <x-primary-button class="ms-4">
                                Create Slot
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>