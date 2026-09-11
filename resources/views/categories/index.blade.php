<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">Categories</div>
                <div style="font-size:12px;color:var(--mist);">Service categories used by photographers.</div>
            </div>

            <a href="{{ route('admin.categories.create') }}" class="btn-mini btn-me">
                Add Category
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $categories->count() }} {{ $categories->count() === 1 ? 'category' : 'categories' }}</div>
            </div>

            @if ($categories->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Category Name</th>
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="text-[var(--mist)]">
                                        {{ $category->id_category }}
                                    </td>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $category->category_name }}
                                    </td>
                                    <td class="text-right">
                                        <div class="act-row">
                                            <a
                                                href="{{ route('admin.categories.edit', $category->id_category) }}"
                                                class="btn-mini btn-mg"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('admin.categories.destroy', $category->id_category) }}"
                                                method="POST"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this category?')"
                                                    class="btn-mini btn-mg"
                                                    style="color:#c97070;border-color:rgba(163,48,48,.3);"
                                                >
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No categories found.</p>
                    <p style="font-size:13px;color:var(--mist);">Create a category so photographers can organize their services.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>