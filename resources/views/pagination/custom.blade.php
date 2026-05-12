@if ($paginator->hasPages())
<nav class="flex items-center gap-1">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
    <span class="px-2 py-1.5 text-gray-600 rounded-lg cursor-not-allowed">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}"
       class="px-2 py-1.5 text-gray-400 hover:text-title hover:bg-gray-700 rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
        <span class="px-2 py-1 text-xs text-gray-600">{{ $element }}</span>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <span class="w-8 h-8 flex items-center justify-center text-xs font-semibold text-gray-900
                         bg-gradient-to-br from-[#d4af37] to-[#b8962d] rounded-lg">
                {{ $page }}
            </span>
            @else
            <a href="{{ $url }}"
               class="w-8 h-8 flex items-center justify-center text-xs text-gray-400
                      hover:text-title hover:bg-gray-700 rounded-lg transition-colors">
                {{ $page }}
            </a>
            @endif
        @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}"
       class="px-2 py-1.5 text-gray-400 hover:text-title hover:bg-gray-700 rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
    @else
    <span class="px-2 py-1.5 text-gray-600 rounded-lg cursor-not-allowed">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </span>
    @endif

</nav>
@endif
