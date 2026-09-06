<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Category
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form
                        method="POST"
                        action="{{ route('categories.update', $category->id_category) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label
                                for="category_name"
                                :value="__('Category Name')"
                            />

                            <x-text-input
                                id="category_name"
                                class="block mt-1 w-full"
                                type="text"
                                name="category_name"
                                :value="old('category_name', $category->category_name)"
                                required
                                autofocus
                            />

                            <x-input-error
                                :messages="$errors->get('category_name')"
                                class="mt-2"
                            />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('categories.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </a>

                            <x-primary-button class="ms-4">
                                Update Category
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>