@if ($paginator->hasPages())
<nav class="mt-4 flex items-center justify-center">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 bg-gray-800 text-gray-300 opacity-50 rounded-l cursor-not-allowed">
            <i class="fas fa-angle-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-3 py-2 bg-gray-800 text-gray-300 hover:text-white rounded-l"
           aria-label="Previous">
            <i class="fas fa-angle-left"></i>
        </a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        {{-- Separator --}}
        @if (is_string($element))
            <span class="px-3 py-2 bg-gray-800 text-gray-300 opacity-50">…</span>
        @endif

        {{-- Page links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 bg-indigo-600 text-white hover:bg-indigo-700">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-2 bg-gray-800 text-gray-300 hover:text-white">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-3 py-2 bg-gray-800 text-gray-300 hover:text-white rounded-r"
           aria-label="Next">
            <i class="fas fa-angle-right"></i>
        </a>
    @else
        <span class="px-3 py-2 bg-gray-800 text-gray-300 opacity-50 rounded-r cursor-not-allowed">
            <i class="fas fa-angle-right"></i>
        </span>
    @endif
</nav>
@endif
