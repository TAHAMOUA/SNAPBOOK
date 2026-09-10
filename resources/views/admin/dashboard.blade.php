<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Total Photographers</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_photographers'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Pending Profiles</p>
                        <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending_profiles'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Approved Profiles</p>
                        <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['approved_profiles'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Total Bookings</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Total Reviews</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_reviews'] }}</p>
                    </div>
                </div>

            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.users.index') }}" class="bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <p class="font-medium text-gray-900">Users</p>
                    <p class="text-sm text-gray-500 mt-1">View and manage user roles</p>
                </a>

                <a href="{{ route('admin.photographers.index') }}" class="bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <p class="font-medium text-gray-900">Photographers</p>
                    <p class="text-sm text-gray-500 mt-1">Validate photographer profiles</p>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <p class="font-medium text-gray-900">Categories</p>
                    <p class="text-sm text-gray-500 mt-1">Manage service categories</p>
                </a>

                <a href="{{ route('admin.bookings.index') }}" class="bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <p class="font-medium text-gray-900">Bookings</p>
                    <p class="text-sm text-gray-500 mt-1">View all bookings</p>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <p class="font-medium text-gray-900">Reviews</p>
                    <p class="text-sm text-gray-500 mt-1">View all reviews</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>