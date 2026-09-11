<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Edit Portfolio Image</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('portfolio.update', $portfolio->id_photo) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="fld">
                        <x-input-label for="image" :value="__('Image')" />
                        <input
                            id="image"
                            type="file"
                            name="image"
                            accept="image/jpeg,image/png,image/webp"
                            class="block mt-1 w-full text-[13px] text-[var(--mist)]
                                file:mr-3 file:px-3 file:py-2 file:rounded-[3px] file:border file:border-[var(--bd)]
                                file:bg-[rgba(63,58,66,.3)] file:text-[var(--mist)] file:font-medium
                                hover:file:border-[var(--mist)]"
                        />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        <p class="mt-1 text-[12px] text-[var(--mist)]">Leave empty to keep the current image. JPEG, PNG, JPG or WebP. Max 5 MB.</p>
                    </div>

                    <div class="fld">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea
                            id="description"
                            name="description"
                            class="ta mt-1"
                            rows="4"
                            maxlength="2000"
                        >{{ old('description', $portfolio->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('portfolio.show', $portfolio->id_photo) }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Update Image
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>