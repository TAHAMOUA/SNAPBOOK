<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Create Service</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('services.store') }}">
                    @csrf

                    <div class="fld">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input
                            id="title"
                            class="mt-1"
                            type="text"
                            name="title"
                            :value="old('title')"
                            required
                            autofocus
                            maxlength="150"
                        />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="fld">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea
                            id="description"
                            name="description"
                            class="ta mt-1"
                            rows="4"
                        >{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="fld">
                        <x-input-label for="price" :value="__('Price')" />
                        <x-text-input
                            id="price"
                            class="mt-1"
                            type="number"
                            name="price"
                            :value="old('price')"
                            step="0.01"
                            min="0"
                            required
                        />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Price in your local currency.</p>
                    </div>

                    <div class="fld">
                        <x-input-label for="duration" :value="__('Duration (minutes)')" />
                        <x-text-input
                            id="duration"
                            class="mt-1"
                            type="number"
                            name="duration"
                            :value="old('duration')"
                            min="1"
                            required
                        />
                        <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Duration in minutes.</p>
                    </div>

                    <div class="fld">
                        <x-input-label for="id_category" :value="__('Category')" />
                        <select
                            id="id_category"
                            name="id_category"
                            class="sel mt-1"
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

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('services.index') }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Create Service
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>