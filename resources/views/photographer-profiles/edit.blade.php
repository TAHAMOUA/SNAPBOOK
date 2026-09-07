<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Photographer Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('photographer-profile.update', $profile->id_profile) }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="bio" :value="__('Bio')" />
                            <textarea
                                id="bio"
                                name="bio"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="4"
                            >{{ old('bio', $profile->bio) }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Tell clients about yourself and your photography style.</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input
                                id="city"
                                class="block mt-1 w-full"
                                type="text"
                                name="city"
                                :value="old('city', $profile->city)"
                                autocomplete="city"
                            />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="experience" :value="__('Years of Experience')" />
                            <x-text-input
                                id="experience"
                                class="block mt-1 w-full"
                                type="number"
                                name="experience"
                                :value="old('experience', $profile->experience)"
                                min="0"
                                max="100"
                            />
                            <x-input-error :messages="$errors->get('experience')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Optional. Number of years as a professional photographer.</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="validation_status" :value="__('Validation Status')" />
                            <select
                                id="validation_status"
                                name="validation_status"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                disabled
                            >
                                <option value="pending" {{ $profile->validation_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $profile->validation_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $profile->validation_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Managed by administrators.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('photographer-profile.show', $profile->id_profile) }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </a>
                            <x-primary-button class="ms-4">
                                Update Profile
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>