{{-- Paginator --}}
<nav class="paginator">
    <ul class="pagination justify-content-end mb-0">
        <li class="page-item {{ ($paginator->onFirstPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->url(1) }}"><i class="bi bi-chevron-double-left"></i></a>
        </li>
        <li class="page-item {{ ($paginator->onFirstPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
        </li>
        <li class="page-item active" aria-current="page">
            <span class="page-link"><a href="{{ $paginator->url($paginator->currentPage()) }}" id="current-page" class="text-white">{{ $paginator->currentPage() }}</a> of {{ $paginator->lastPage() }}</span>
            </li>
        <li class="page-item {{ ($paginator->onLastPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
        </li>
        <li class="page-item {{ ($paginator->onLastPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}"><i class="bi bi-chevron-double-right"></i></a>
        </li>
    </ul>
</nav>
<script type="module">
    $(function(){
        $("#{{ $modDiv }} .paginator").find('a').click(function(e) {
            e.preventDefault();
            $.get($(this).attr('href'), function(response) {
                $("#{{ $modDiv }}").html(response);
            });
        });
        // Sort
        $("#{{ $modDiv }} .page-sort thead").find('a').click(function(e) {
            e.preventDefault();
            $.get($(this).attr('href'), function(response) {
                $("#{{ $modDiv }}").html(response);
            });
        });
    });
</script>