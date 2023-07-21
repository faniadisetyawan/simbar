@if ($paginator->hasPages())
  <div class="pagination-wrap hstack gap-2" style="display: flex;">

    @if ($paginator->onFirstPage())
      <span class="page-item pagination-prev disabled">
        Previous
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="page-item pagination-prev" rel="prev" aria-label="@lang('pagination.previous')">
        Previous
      </a>
    @endif

    <ul class="pagination listjs-pagination mb-0">
      @foreach ($elements as $element)
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="active" aria-current="page">
                <span class="page">{{ $page }}</span>
              </li>
            @else
              <li>
                <a href="{{ $url }}" class="page">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach
    </ul>

    @if ($paginator->hasMorePages())
      <a class="page-item pagination-next" href="{{ $paginator->nextPageUrl() }}">
        Next
      </a>
    @else
      <span class="page-item pagination-next disabled" aria-hidden="true">Next</span>
    @endif

  </div>
@endif