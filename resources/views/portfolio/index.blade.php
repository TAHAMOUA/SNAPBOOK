<x-app-layout>
    <div class="dash-wrap">

        <div class="admin-top">
            <div>
                <div class="admin-title">My Portfolio</div>
                <div style="font-size:12px;color:var(--mist);">Photo samples that display your work.</div>
            </div>

            <a href="{{ route('portfolio.create') }}" class="btn-mini btn-me">
                Add Image
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        @if ($portfolios->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($portfolios as $portfolio)
                    <div class="border border-[var(--bd)] rounded-md overflow-hidden bg-[rgba(63,58,66,.2)]">
                        <a href="{{ route('portfolio.show', $portfolio->id_photo) }}">
                            <img
                                src="{{ Storage::url($portfolio->image) }}"
                                alt="Portfolio image"
                                class="w-full h-48 object-cover"
                            >
                        </a>

                        <div class="p-4">
                            <p class="text-[13px] text-[var(--mist)] line-clamp-2">
                                {{ $portfolio->description ?? 'No description' }}
                            </p>

                            <div class="mt-3 flex items-center justify-between gap-2">
                                <a
                                    href="{{ route('portfolio.edit', $portfolio->id_photo) }}"
                                    class="btn-mini btn-mg"
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
                                        class="btn-mini btn-mg"
                                        style="color:#c97070;border-color:rgba(163,48,48,.3);"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $portfolios->links() }}
            </div>
        @else
            <div class="border border-[var(--bd)] bg-[rgba(63,58,66,.2)] rounded-md p-10 text-center">
                <p class="font-medium text-[var(--white)] mb-1">No portfolio images found.</p>
                <p style="font-size:13px;color:var(--mist);" class="mb-4">Showcase your work to attract more clients.</p>
                <a href="{{ route('portfolio.create') }}" class="btn-mini btn-me">
                    Add your first image
                </a>
            </div>
        @endif

    </div>
</x-app-layout>