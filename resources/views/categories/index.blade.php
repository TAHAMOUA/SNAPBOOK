<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories
            </h2>

            <a
                href="{{ route('categories.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
                Add Category
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

                    @if ($categories->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            ID
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Category Name
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $category->id_category }}
                                            </td>

                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $category->category_name }}
                                            </td>

                                            <td class="px-6 py-4 text-right text-sm">
                                                <a
                                                    href="{{ route('categories.show', $category->id_category) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                                >
                                                    View
                                                </a>

                                                <a
                                                    href="{{ route('categories.edit', $category->id_category) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('categories.destroy', $category->id_category) }}"
                                                    method="POST"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this category?')"
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
                    @else
                        <p class="text-gray-500">
                            No categories found.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>