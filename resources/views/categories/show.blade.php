<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Category Details</div>
        </div>

        <div class="apanel mb-6">
            <div class="overflow-x-auto">
                <table class="atbl">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:38%;">ID</th>
                            <td class="text-[var(--mist)]">{{ $category->id_category }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Category Name</th>
                            <td class="font-medium text-[var(--white)]">{{ $category->category_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.edit', $category->id_category) }}" class="btn-mini btn-me">
                Edit
            </a>

            <a href="{{ route('admin.categories.index') }}" class="btn-mini btn-mg">
                Back
            </a>
        </div>

    </div>
</x-app-layout>