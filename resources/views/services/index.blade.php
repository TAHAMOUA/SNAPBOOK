<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Services
            </h2>

            <a
                href="{{ route('services.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
                Add Service
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

                    @if ($services->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Title
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Category
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Price
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Duration
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($services as $service)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $service->title }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $service->category->category_name ?? 'N/A' }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                ${{ number_format($service->price, 2) }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $service->duration }} min
                                            </td>

                                            <td class="px-6 py-4 text-right text-sm">
                                                <a
                                                    href="{{ route('services.show', $service->id_service) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                                >
                                                    View
                                                </a>

                                                <a
                                                    href="{{ route('services.edit', $service->id_service) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('services.destroy', $service->id_service) }}"
                                                    method="POST"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this service?')"
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
                            {{ $services->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            No services found.
                            <a href="{{ route('services.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                Create your first service
                            </a>
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>