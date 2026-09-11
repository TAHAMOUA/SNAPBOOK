<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">Manage Users</div>
                <div style="font-size:12px;color:var(--mist);">Accounts and roles across the platform.</div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 border border-[rgba(163,48,48,.35)] bg-[rgba(163,48,48,.1)] px-4 py-3 rounded-md text-sm" style="color:#c97070;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="apanel">
            <div class="ap-h">
                <div class="ap-t">{{ $users->count() }} {{ $users->count() === 1 ? 'user' : 'users' }}</div>
            </div>

            @if ($users->count())
                <div class="overflow-x-auto">
                    <table class="atbl">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Role</th>
                                <th scope="col">Joined</th>
                                <th scope="col" class="text-right">Change Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="font-medium text-[var(--white)]">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $user->email }}
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $user->phone ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="pill
                                            @if ($user->role === 'admin') orange
                                            @elseif ($user->role === 'photographer') blue
                                            @else gray @endif
                                        ">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-[var(--mist)]">
                                        {{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}
                                    </td>
                                    <td class="text-right">
                                        @if ($user->id_user === auth()->id())
                                            <span style="font-size:12px;color:var(--mist);">Current user</span>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.role', $user->id_user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')

                                                <select
                                                    name="role"
                                                    onchange="this.form.submit()"
                                                    class="sel"
                                                    style="width:auto;padding:5px 8px;font-size:12px;"
                                                >
                                                    <option value="client" @selected($user->role === 'client')>client</option>
                                                    <option value="photographer" @selected($user->role === 'photographer')>photographer</option>
                                                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <p class="font-medium text-[var(--white)] mb-1">No users found.</p>
                    <p style="font-size:13px;color:var(--mist);">User accounts will appear here once they register.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>