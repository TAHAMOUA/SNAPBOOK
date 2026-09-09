<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Service Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Service ID</p>
                        <p class="text-lg font-medium text-gray-900">{{ $service->id_service }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Title</p>
                        <p class="text-lg font-medium text-gray-900">{{ $service->title }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $service->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-lg font-medium text-gray-900">${{ number_format($service->price, 2) }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="text-lg text-gray-900">{{ $service->duration }} minutes</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="text-lg text-gray-900">{{ $service->category->category_name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Photographer</p>
                        <p class="text-lg text-gray-900">{{ $service->photographerProfile->user->first_name }} {{ $service->photographerProfile->user->last_name }}</p>
                    </div>

                    @if (auth()->id() === $service->photographerProfile->id_user)
                        <div class="flex items-center gap-4">
                            <a
                                href="{{ route('services.edit', $service->id_service) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
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
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                >
                                    Delete
                                </button>
                            </form>

                            <a
                                href="{{ route('services.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Back to Services
                            </a>
                        </div>
                    @else
                        @if (
                            auth()->user()->role === 'client'
                            && $service->photographerProfile
                            && $service->photographerProfile->validation_status === 'approved'
                        )
                            <a
                                href="{{ route('bookings.create', ['service' => $service->id_service]) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Book
                            </a>
                        @endif

                        <a
                            href="{{ route('services.index') }}"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            Back to Services
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>