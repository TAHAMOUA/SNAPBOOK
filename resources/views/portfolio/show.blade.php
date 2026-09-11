<x-app-layout>
    <div class="book-wrap">

        <div class="admin-top">
            <div class="admin-title">Portfolio Image Details</div>
        </div>

        @if (session('success'))
            <div class="mb-6 border border-[rgba(45,138,78,.35)] bg-[rgba(45,138,78,.1)] px-4 py-3 rounded-md text-sm" style="color:#5dbf7e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="apanel mb-6 overflow-hidden">
            <img
                src="{{ Storage::url($portfolio->image) }}"
                alt="Portfolio image"
                class="w-full"
            >

            <div class="overflow-x-auto border-t border-[var(--bd)]">
                <table class="atbl">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:38%;">Image ID</th>
                            <td class="text-[var(--mist)]">{{ $portfolio->id_photo }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td class="text-[var(--mist)] whitespace-pre-wrap">{{ $portfolio->description ?? 'No description provided.' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Photographer</th>
                            <td class="text-[var(--mist)]">{{ $portfolio->photographerProfile->user->first_name }} {{ $portfolio->photographerProfile->user->last_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if (auth()->id() === $portfolio->photographerProfile->id_user)
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('portfolio.edit', $portfolio->id_photo) }}" class="btn-mini btn-me">
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

                <a href="{{ route('portfolio.index') }}" class="btn-mini btn-mg">
                    Back to Portfolio
                </a>
            </div>
        @else
            <a href="{{ route('portfolio.index') }}" class="btn-mini btn-mg">
                Back to Portfolio
            </a>
        @endif

    </div>
</x-app-layout>