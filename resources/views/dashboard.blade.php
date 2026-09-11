<x-app-layout>
    <div class="dash-wrap">

        <div class="dash-hd">
            <div class="dash-welcome">Welcome back, {{ auth()->user()->first_name }}</div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn-book">
                    Admin Dashboard
                </a>
            @endif
        </div>

        <p style="font-size:13px;color:var(--mist);">
            You're logged in!
        </p>

    </div>
</x-app-layout>