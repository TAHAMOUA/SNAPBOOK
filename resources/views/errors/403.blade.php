<x-app-layout>
    <div class="book-wrap text-center">

        <p class="eyebrow" style="justify-content:center;">
            <span>403</span>
        </p>

        <h1 class="book-title" style="font-size:52px;">
            Access Denied
        </h1>

        <p class="book-sub" style="max-width:340px;margin:0 auto 2rem;">
            You don't have permission to view this page. If you arrived here by mistake, use one of the options below to get back on track.
        </p>

        <div class="flex items-center justify-center gap-3 flex-wrap">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-mini btn-me">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('home') }}" class="btn-mini btn-me">
                    Go to Home
                </a>
            @endauth

            <a href="{{ route('photographers.index') }}" class="btn-mini btn-mg">
                Find Photographers
            </a>
        </div>

    </div>
</x-app-layout>