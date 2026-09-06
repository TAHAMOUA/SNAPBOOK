<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Category Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            ID
                        </p>

                        <p class="text-lg font-medium text-gray-900">
                            {{ $category->id_category }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            Category Name
                        </p>

                        <p class="text-lg font-medium text-gray-900">
                            {{ $category->category_name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a
                            href="{{ route('categories.edit', $category->id_category) }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                            Edit
                        </a>

                        <a
                            href="{{ route('categories.index') }}"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>