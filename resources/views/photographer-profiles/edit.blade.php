<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Edit Photographer Profile</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('photographer-profile.update', $profile->id_profile) }}">
                    @csrf
                    @method('PATCH')

                    <div class="fld">
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea
                            id="bio"
                            name="bio"
                            class="ta mt-1"
                            rows="4"
                        >{{ old('bio', $profile->bio) }}</textarea>
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
                            :value="old('city', $profile->city)"
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
                            :value="old('experience', $profile->experience)"
                            min="0"
                            max="100"
                        />
                        <x-input-error :messages="$errors->get('experience')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Optional. Number of years as a professional photographer.</p>
                    </div>

                    <div class="fld">
                        <x-input-label for="validation_status" :value="__('Validation Status')" />
                        <select
                            id="validation_status"
                            name="validation_status"
                            class="sel mt-1"
                            disabled
                        >
                            <option value="pending" {{ $profile->validation_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $profile->validation_status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $profile->validation_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Managed by administrators.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Update Profile
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>