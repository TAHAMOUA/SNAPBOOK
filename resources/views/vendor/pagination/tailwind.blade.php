@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-[12px] font-medium text-[var(--mist)] bg-transparent border border-[var(--bd)] leading-5 rounded-[3px] cursor-not-allowed opacity-50">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-[12px] font-medium text-[var(--mist)] bg-transparent border border-[var(--bd)] leading-5 rounded-[3px] hover:text-[var(--white)] hover:border-[var(--mist)] focus:outline-none transition ease-in-out duration-150">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-[12px] font-medium text-[var(--mist)] bg-transparent border border-[var(--bd)] leading-5 rounded-[3px] hover:text-[var(--white)] hover:border-[var(--mist)] focus:outline-none transition ease-in-out duration-150">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-[12px] font-medium text-[var(--mist)] bg-transparent border border-[var(--bd)] leading-5 rounded-[3px] cursor-not-allowed opacity-50">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-[13px] text-[var(--mist)] leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-[var(--white)]">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-[var(--white)]">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium text-[var(--white)]">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse rounded-[3px] border border-[var(--bd2)] overflow-hidden">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-[13px] text-[var(--mist)] bg-transparent cursor-not-allowed opacity-50" aria-hidden="true">
                                <i class="ti ti-chevron-left" style="font-size:14px;"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 text-[13px] text-[var(--mist)] bg-transparent hover:text-[var(--white)] hover:bg-[var(--w10)] focus:outline-none transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <i class="ti ti-chevron-left" style="font-size:14px;"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-[12px] text-[var(--mist)] bg-transparent cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-[12px] font-medium text-white bg-[var(--ember)] cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 text-[12px] text-[var(--mist)] bg-transparent hover:text-[var(--white)] hover:bg-[var(--w10)] focus:outline-none transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 text-[13px] text-[var(--mist)] bg-transparent hover:text-[var(--white)] hover:bg-[var(--w10)] focus:outline-none transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                            <i class="ti ti-chevron-right" style="font-size:14px;"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-[13px] text-[var(--mist)] bg-transparent cursor-not-allowed opacity-50" aria-hidden="true">
                                <i class="ti ti-chevron-right" style="font-size:14px;"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif