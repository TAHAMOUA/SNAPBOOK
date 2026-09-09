<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Portfolio Image Details
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
                        <p class="text-sm text-gray-500">Image ID</p>
                        <p class="text-lg font-medium text-gray-900">{{ $portfolio->id_photo }}</p>
                    </div>

                    <div class="mb-6">
                        <img
                            src="{{ Storage::url($portfolio->image) }}"
                            alt="Portfolio image"
                            class="w-full rounded-lg"
                        >
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-lg text-gray-900 whitespace-pre-wrap">{{ $portfolio->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Photographer</p>
                        <p class="text-lg text-gray-900">{{ $portfolio->photographerProfile->user->first_name }} {{ $portfolio->photographerProfile->user->last_name }}</p>
                    </div>

                    @if (auth()->id() === $portfolio->photographerProfile->id_user)
                        <div class="flex items-center gap-4">
                            <a
                                href="{{ route('portfolio.edit', $portfolio->id_photo) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('portfolio.destroy', $portfolio->id_photo) }}"
                                method="POST"
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to delete this portfolio image?')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                >
                                    Delete
                                </button>
                            </form>

                            <a
                                href="{{ route('portfolio.index') }}"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                Back to Portfolio
                            </a>
                        </div>
                    @else
                        <a
                            href="{{ route('portfolio.index') }}"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            Back to Portfolio
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
