<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">Photographer Profiles</div>
                <div style="font-size:12px;color:var(--mist);">Validate photographer applications.</div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 border border-[rgba(2,54,97,.5)] bg-[rgba(2,54,97,.25)] px-4 py-3 rounded-md text-sm" style="color:#6aaed6;">
                {{ session('info') }}
            </div>
        @endif

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $profiles->count() }} {{ $profiles->count() === 1 ? 'profile' : 'profiles' }}</div>
            </div>

            @if ($profiles->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Photographer</th>
                                <th scope="col">City</th>
                                <th scope="col">Experience</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $profile->city ?? '—' }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $profile->experience ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="pill
                                            @if ($profile->validation_status === 'approved') green
                                            @elseif ($profile->validation_status === 'rejected') red
                                            @else orange @endif
                                        ">
                                            {{ ucfirst($profile->validation_status) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="act-row">
                                            <a href="{{ route('admin.photographers.show', $profile->id_profile) }}" class="btn-mini btn-mg">
                                                View Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $profiles->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No photographer profiles found.</p>
                    <p style="font-size:13px;color:var(--mist);">Submitted photographer profiles will appear here.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>