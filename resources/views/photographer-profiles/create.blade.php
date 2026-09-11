<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Create Photographer Profile</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('photographer-profile.store') }}">
                    @csrf

                    <div class="fld">
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea
                            id="bio"
                            name="bio"
                            class="ta mt-1"
                            rows="4"
                        >{{ old('bio') }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Tell clients about yourself and your photography style.</p>
                    </div>

                    <div class="fld">
                        <x-input-label for="city" :value="__('City')" />
                        <x-text-input
                            id="city"
                            class="mt-1"
                            type="text"
                            name="city"
                            :value="old('city')"
                            autocomplete="city"
                        />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <div class="fld">
                        <x-input-label for="experience" :value="__('Years of Experience')" />
                        <x-text-input
                            id="experience"
                            class="mt-1"
                            type="number"
                            name="experience"
                            :value="old('experience')"
                            min="0"
                            max="100"
                        />
                        <x-input-error :messages="$errors->get('experience')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Optional. Number of years as a professional photographer.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('dashboard') }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Create Profile
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>