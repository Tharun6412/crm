{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('fuel_id')) ? '-fill' : '' }}"></i>
        @isset(request()->fuel_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('fuel_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light" style="min-width: 250px; max-height: 320px; overflow-y: auto;">
        {{-- Search Box --}}
        <li class="dropdown-item p-0 mb-2 position-sticky top-0 bg-light">
            <input type="text" class="form-control form-control-sm" id="fuel_search" placeholder="Search...">
        </li>
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="fuel_id_all">
            <label for="fuel_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $fuel_checked = (request()->has('fuel_id')) ? request()->get('fuel_id') : [];
        @endphp
        @foreach ($fuel_list as $item)
            <li class="dropdown-item fuel-item">
                <input type="checkbox" class="form-check-input fuel_id_filter" name="fuel_id[{{ $item->id }}]" id="fuel_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $fuel_checked))>
                <label for="fuel_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'fuel_id'])
<script type="module">
    $(function() {
        // Search functionality
        $('#fuel_search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.fuel-item').filter(function() {
                $(this).toggle($(this).text().toLowerCase().includes(value));
            });
        });

        // Optional: Reset search when dropdown closes
        $('[data-bs-toggle="dropdown"]').on('hide.bs.dropdown', function () {
            $('#fuel_search').val('');
            $('.fuel-item').show();
        });
    });
</script>