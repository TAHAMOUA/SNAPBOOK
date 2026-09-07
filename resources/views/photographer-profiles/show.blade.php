<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Photographer Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 p-4 bg-blue-100 text-blue-800 rounded-md">
                            {{ session('info') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Profile ID</p>
                        <p class="text-lg font-medium text-gray-900">{{ $profile->id_profile }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Photographer</p>
                        <p class="text-lg font-medium text-gray-900">{{ $profile->user->first_name }} {{ $profile->user->last_name }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-lg font-medium text-gray-900">{{ $profile->user->email }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Validation Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($profile->validation_status === 'approved')
                                bg-green-100 text-green-800
                            @elseif ($profile->validation_status === 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            {{ ucfirst($profile->validation_status) }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Bio</p>
                        <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $profile->bio ?? 'No bio provided.' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">City</p>
                        <p class="text-lg text-gray-900">{{ $profile->city ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Years of Experience</p>
                        <p class="text-lg text-gray-900">{{ $profile->experience ?? 'Not specified' }}</p>
                    </div>

                    @if (auth()->id() === $profile->id_user)
                        <div class="flex items-center gap-4">
                            <a
                                href="{{ route('photographer-profile.edit', $profile->id_profile) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Edit Profile
                            </a>
                            <a
                                href="{{ route('dashboard') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Back to Dashboard
                            </a>
                        </div>
                    @else
                        <a
                            href="{{ route('dashboard') }}"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            Back to Dashboard
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>