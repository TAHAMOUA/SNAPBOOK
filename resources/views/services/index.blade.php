<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">My Services</div>
                <div style="font-size:12px;color:var(--mist);">Sessions and packages you offer.</div>
            </div>

            <a href="{{ route('services.create') }}" class="btn-mini btn-me">
                Add Service
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $services->count() }} {{ $services->count() === 1 ? 'service' : 'services' }}</div>
            </div>

            @if ($services->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Price</th>
                                <th scope="col">Duration</th>
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $service->title }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $service->category->category_name ?? 'N/A' }}
                                    </td>
                                    <td style="color:var(--ember);font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:700;">
                                        ${{ number_format($service->price, 2) }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $service->duration }} min
                                    </td>
                                    <td class="text-right">
                                        <div class="act-row">
                                            <a href="{{ route('services.show', $service->id_service) }}" class="btn-mini btn-mg">
                                                View
                                            </a>
                                            <a href="{{ route('services.edit', $service->id_service) }}" class="btn-mini btn-mg">
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

                <div class="p-4">
                    {{ $services->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No services found.</p>
                    <p style="font-size:13px;color:var(--mist);" class="mb-4">Add your first session so clients can book your work.</p>
                    <a href="{{ route('services.create') }}" class="btn-mini btn-me">
                        Create your first service
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>