<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Service Details</div>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="apanel mb-6">
            <div class="overflow-x-auto">
                <table class="atbl">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:38%;">Service ID</th>
                            <td class="text-[var(--mist)]">{{ $service->id_service }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Title</th>
                            <td class="font-medium text-[var(--white)]">{{ $service->title }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td class="text-[var(--mist)] whitespace-pre-wrap">{{ $service->description ?? 'No description provided.' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Price</th>
                            <td style="color:var(--ember);font-family:'Barlow Condensed',sans-serif;font-size:16px;font-weight:700;">
                                ${{ number_format($service->price, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Duration</th>
                            <td class="text-[var(--mist)]">{{ $service->duration }} minutes</td>
                        </tr>
                        <tr>
                            <th scope="row">Category</th>
                            <td class="text-[var(--mist)]">{{ $service->category->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Photographer</th>
                            <td class="text-[var(--mist)]">{{ $service->photographerProfile->user->first_name }} {{ $service->photographerProfile->user->last_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if (auth()->id() === $service->photographerProfile->id_user)
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('services.edit', $service->id_service) }}" class="btn-mini btn-me">
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

                <a href="{{ route('services.index') }}" class="btn-mini btn-mg">
                    Back to Services
                </a>
            </div>
        @else
            <div class="flex items-center gap-3 flex-wrap">
                @if (
                    auth()->user()->role === 'client'
                    && $service->photographerProfile
                    && $service->photographerProfile->validation_status === 'approved'
                )
                    <a
                        href="{{ route('bookings.create', ['service' => $service->id_service]) }}"
                        class="btn-mini btn-me"
                    >
                        Book
                    </a>
                @endif

                <a
                    href="{{ route('services.index') }}"
                    class="btn-mini btn-mg"
                >
                    Back to Services
                </a>
            </div>
        @endif

    </div>
</x-app-layout>