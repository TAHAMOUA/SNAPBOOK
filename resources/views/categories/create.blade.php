<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Create Category</div>
        </div>

        <div class="apanel">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf

                    <div class="fld">
                        <x-input-label
                            for="category_name"
                            :value="__('Category Name')"
                        />

                        <x-text-input
                            id="category_name"
                            class="mt-1"
                            type="text"
                            name="category_name"
                            :value="old('category_name')"
                            required
                            autofocus
                        />

                        <x-input-error
                            :messages="$errors->get('category_name')"
                            class="mt-2"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="text-[13px] text-[var(--mist)] hover:text-[var(--white)]"
                        >
                            Cancel
                        </a>

                        <x-primary-button>
                            Create Category
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>