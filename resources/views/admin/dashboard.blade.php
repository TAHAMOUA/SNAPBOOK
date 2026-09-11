<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div class="admin-title">Dashboard</div>
            <div style="font-size:12px;color:var(--mist);">{{ now()->format('F Y') }}</div>
        </div>

        <div class="grid gap-3 mb-8" style="grid-template-columns:repeat(auto-fit,minmax(190px,1fr));">
            <div class="kpi">
                <div class="kpi-n" style="color:var(--white);">{{ $stats['total_users'] }}</div>
                <div class="kpi-l">Total Users</div>
            </div>
            <div class="kpi">
                <div class="kpi-n" style="color:var(--white);">{{ $stats['total_photographers'] }}</div>
                <div class="kpi-l">Total Photographers</div>
            </div>
            <div class="kpi">
                <div class="kpi-n" style="color:var(--ember);">{{ $stats['pending_profiles'] }}</div>
                <div class="kpi-l">Pending Profiles</div>
            </div>
            <div class="kpi">
                <div class="kpi-n" style="color:#5dbf7e;">{{ $stats['approved_profiles'] }}</div>
                <div class="kpi-l">Approved Profiles</div>
            </div>
            <div class="kpi">
                <div class="kpi-n" style="color:var(--white);">{{ $stats['total_bookings'] }}</div>
                <div class="kpi-l">Total Bookings</div>
            </div>
            <div class="kpi">
                <div class="kpi-n" style="color:var(--white);">{{ $stats['total_reviews'] }}</div>
                <div class="kpi-l">Total Reviews</div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a
                href="{{ route('admin.users.index') }}"
                class="apanel block no-underline transition hover:border-[rgba(219,82,39,.55)]"
            >
                <div class="ap-h"><div class="ap-t">Users</div></div>
                <div class="p-[14px]"><p style="font-size:12px;color:var(--mist);">View and manage user roles</p></div>
            </a>

            <a
                href="{{ route('admin.photographers.index') }}"
                class="apanel block no-underline transition hover:border-[rgba(219,82,39,.55)]"
            >
                <div class="ap-h"><div class="ap-t">Photographers</div></div>
                <div class="p-[14px]"><p style="font-size:12px;color:var(--mist);">Validate photographer profiles</p></div>
            </a>

            <a
                href="{{ route('admin.categories.index') }}"
                class="apanel block no-underline transition hover:border-[rgba(219,82,39,.55)]"
            >
                <div class="ap-h"><div class="ap-t">Categories</div></div>
                <div class="p-[14px]"><p style="font-size:12px;color:var(--mist);">Manage service categories</p></div>
            </a>

            <a
                href="{{ route('admin.bookings.index') }}"
                class="apanel block no-underline transition hover:border-[rgba(219,82,39,.55)]"
            >
                <div class="ap-h"><div class="ap-t">Bookings</div></div>
                <div class="p-[14px]"><p style="font-size:12px;color:var(--mist);">View all bookings</p></div>
            </a>

            <a
                href="{{ route('admin.reviews.index') }}"
                class="apanel block no-underline transition hover:border-[rgba(219,82,39,.55)]"
            >
                <div class="ap-h"><div class="ap-t">Reviews</div></div>
                <div class="p-[14px]"><p style="font-size:12px;color:var(--mist);">View all reviews</p></div>
            </a>
        </div>

    </div>
</x-app-layout>