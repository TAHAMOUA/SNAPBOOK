<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Create Availability Slot</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('availabilities.store') }}">
                    @csrf

                    <div class="fld">
                        <x-input-label for="available_date" :value="__('Available Date')" />
                        <x-text-input
                            id="available_date"
                            class="mt-1"
                            type="date"
                            name="available_date"
                            :value="old('available_date')"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('available_date')" class="mt-2" />
                    </div>

                    <div class="fld">
                        <x-input-label for="start_time" :value="__('Start Time')" />
                        <x-text-input
                            id="start_time"
                            class="mt-1"
                            type="time"
                            name="start_time"
                            :value="old('start_time')"
                            required
                        />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>

                    <div class="fld">
                        <x-input-label for="end_time" :value="__('End Time')" />
                        <x-text-input
                            id="end_time"
                            class="mt-1"
                            type="time"
                            name="end_time"
                            :value="old('end_time')"
                            required
                        />
                        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('availabilities.index') }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Create Slot
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>