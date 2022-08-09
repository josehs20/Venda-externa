@if ($paginator->hasPages())

<nav aria-label="Page navigation example col-md-11" style="position: relative">
    <ul class="pagination">
        <li class="page-item">
            @if ($paginator->onFirstPage())
                {{-- <a class="page-link" href="#"><i class="bi bi-arrow-right"></i></a> --}}
            @else
                {{-- <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
                    <i class="bi bi-arrow-left"></i>
                </a> --}}
            @endif
        </li>
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @php
                $i = 0;
            @endphp
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item"><a class="page-link" href="#">{{ $element }}</a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($i <= 5)
                        <li style="height: 0px;" class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}"><a
                                class="page-link" href="{{ $url }}"> {{ $page }}</a></li>
                    @endif
                    @php
                        $i++;
                    @endphp
                @endforeach
            @endif
        @endforeach

        <li class="page-item">

            @if ($paginator->hasMorePages())
                {{-- <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="ri-arrow-right-line"></i></a> --}}
            @else
                {{-- <a class="page-link bg-secondary"><i class="ri-arrow-right-line"></i></a> --}}
            @endif
        </li>
    </ul>
</nav>
@endif
