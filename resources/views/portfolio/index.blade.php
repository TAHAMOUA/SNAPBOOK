<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Portfolio
            </h2>

            <a
                href="{{ route('portfolio.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
                Add Image
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($portfolios->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($portfolios as $portfolio)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <a href="{{ route('portfolio.show', $portfolio->id_photo) }}">
                                        <img
                                            src="{{ Storage::url($portfolio->image) }}"
                                            alt="Portfolio image"
                                            class="w-full h-48 object-cover"
                                        >
                                    </a>

                                    <div class="p-4">
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $portfolio->description ?? 'No description' }}
                                        </p>

                                        <div class="mt-3 flex justify-between items-center text-sm">
                                            <a
                                                href="{{ route('portfolio.edit', $portfolio->id_photo) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
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
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $portfolios->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            No portfolio images found.
                            <a href="{{ route('portfolio.create') }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                Add your first image
                            </a>
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
