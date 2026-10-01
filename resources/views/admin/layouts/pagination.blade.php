{{-- Daftarkan sekali di AppServiceProvider::boot():
     Paginator::defaultView('admin.partials.pagination');
     atau per pemanggilan: {{ $items->links('admin.partials.pagination') }} --}}
@if ($paginator->hasPages())
    <nav class="adm-pagination" role="navigation" aria-label="Paginasi">
        <p class="adm-pagination-info">
            Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
        </p>
        <ul>
            @if ($paginator->onFirstPage())
                <li><span class="adm-page is-disabled" aria-disabled="true"><svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg><span class="adm-sr-only">Sebelumnya</span></span></li>
            @else
                <li><a class="adm-page" href="{{ $paginator->previousPageUrl() }}" rel="prev"><svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-left"/></svg><span class="adm-sr-only">Sebelumnya</span></a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="adm-page is-disabled">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="adm-page is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="adm-page" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="adm-page" href="{{ $paginator->nextPageUrl() }}" rel="next"><svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-right"/></svg><span class="adm-sr-only">Berikutnya</span></a></li>
            @else
                <li><span class="adm-page is-disabled" aria-disabled="true"><svg class="adm-icon" aria-hidden="true"><use href="#i-chevron-right"/></svg><span class="adm-sr-only">Berikutnya</span></span></li>
            @endif
        </ul>
    </nav>
@endif
