@if ($paginator->hasPages())
<nav role="navigation" class="inline-flex shadow-sm rounded-xl overflow-hidden border border-blue-100">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="px-4 py-2 bg-gray-100 text-gray-400 cursor-not-allowed">«</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-white text-blue-600 hover:bg-blue-50">«</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-4 py-2 bg-white text-gray-500">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-4 py-2 bg-white text-blue-600 hover:bg-blue-50">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-white text-blue-600 hover:bg-blue-50">»</a>
    @else
        <span class="px-4 py-2 bg-gray-100 text-gray-400 cursor-not-allowed">»</span>
    @endif
</nav>
@endif
