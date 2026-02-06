@if ($paginator->hasPages())
    <ul class="wd-navigation">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <li><a href="#" class="nav-item disabled"><i class="icon icon-arr-l"></i></a></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" class="nav-item"><i class="icon icon-arr-l"></i></a></li>
        @endif

        <!-- Pagination Elements -->
        @foreach ($elements as $element)
            <!-- "Three Dots" Separator -->
            @if (is_string($element))
                <li><a href="#" class="nav-item disabled">{{ $element }}</a></li>
            @endif

            <!-- Array Of Links -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><a href="#" class="nav-item active">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}" class="nav-item">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="nav-item"><i class="icon icon-arr-r"></i></a></li>
        @else
            <li><a href="#" class="nav-item disabled"><i class="icon icon-arr-r"></i></a></li>
        @endif
    </ul>
@endif
