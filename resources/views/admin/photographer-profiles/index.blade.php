<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Photographer Profiles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($profiles->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photographer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Experience</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($profiles as $profile)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $profile->city ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $profile->experience ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if ($profile->validation_status === 'approved') bg-green-100 text-green-800
                                                    @elseif ($profile->validation_status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif
                                                ">
                                                    {{ ucfirst($profile->validation_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm">
                                                <a
                                                    href="{{ route('admin.photographers.show', $profile->id_profile) }}"
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $profiles->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No photographer profiles found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>