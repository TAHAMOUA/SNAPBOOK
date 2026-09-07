<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Service
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input
                                id="title"
                                class="block mt-1 w-full"
                                type="text"
                                name="title"
                                :value="old('title')"
                                required
                                autofocus
                                maxlength="150"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea
                                id="description"
                                name="description"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="4"
                            >{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input
                                id="price"
                                class="block mt-1 w-full"
                                type="number"
                                name="price"
                                :value="old('price')"
                                step="0.01"
                                min="0"
                                required
                            />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Price in your local currency.</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="duration" :value="__('Duration (minutes)')" />
                            <x-text-input
                                id="duration"
                                class="block mt-1 w-full"
                                type="number"
                                name="duration"
                                :value="old('duration')"
                                min="1"
                                required
                            />
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Duration in minutes.</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="id_category" :value="__('Category')" />
                            <select
                                id="id_category"
                                name="id_category"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id_category }}" {{ old('id_category') === $category->id_category ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_category')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a
                                href="{{ route('services.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </a>
                            <x-primary-button class="ms-4">
                                Create Service
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>