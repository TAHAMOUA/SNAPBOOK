<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">My Availability</div>
                <div style="font-size:12px;color:var(--mist);">Time slots clients can book.</div>
            </div>

            <a href="{{ route('availabilities.create') }}" class="btn-mini btn-me">
                Add Slot
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $availabilities->count() }} {{ $availabilities->count() === 1 ? 'slot' : 'slots' }}</div>
            </div>

            @if ($availabilities->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availabilities as $availability)
                                <tr>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $availability->available_date->format('Y-m-d') }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $availability->start_time }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $availability->end_time }}
                                    </td>
                                    <td class="text-right">
                                        <div class="act-row">
                                            <a
                                                href="{{ route('availabilities.edit', $availability->id_availability) }}"
                                                class="btn-mini btn-mg"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('availabilities.destroy', $availability->id_availability) }}"
                                                method="POST"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this availability slot?')"
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
                    {{ $availabilities->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No availability slots found.</p>
                    <p style="font-size:13px;color:var(--mist);" class="mb-4">Add time slots so clients can request your sessions.</p>
                    <a href="{{ route('availabilities.create') }}" class="btn-mini btn-me">
                        Add your first slot
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>