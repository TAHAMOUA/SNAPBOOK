<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Availability
            </h2>

            <a
                href="{{ route('availabilities.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
                Add Slot
            </a>
        </div>
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

                    @if ($availabilities->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Date
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Start Time
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            End Time
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($availabilities as $availability)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $availability->available_date->format('Y-m-d') }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $availability->start_time }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $availability->end_time }}
                                            </td>

                                            <td class="px-6 py-4 text-right text-sm">
                                                <a
                                                    href="{{ route('availabilities.edit', $availability->id_availability) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('availabilities.destroy', $availability->id_availability) }}"
                                                    method="POST"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this availability slot?')"
                                                        class="text-red-600 hover:text-red-900"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $availabilities->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            No availability slots found.
                            <a href="{{ route('availabilities.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                Add your first slot
                            </a>
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>