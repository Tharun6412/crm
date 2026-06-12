{{-- Proposal list GA filter --}}
@php
    $gaEleId = rand(3, 999);
@endphp
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('geo_area')) ? '-fill' : '' }}"></i>
        @isset(request()->geo_area)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('geo_area')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" id="{{ $gaEleId }}_ga_all">
                <label for="{{ $gaEleId }}_ga_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $geo_area_checked = (request()->has('geo_area')) ? request()->get('geo_area') : [];
            @endphp
            @foreach ($geo_areas as $item)
                <li class="dropdown-item">
                    <input type="checkbox" class="form-check-input {{ $gaEleId }}_ga_filter" name="geo_area[{{ $item->id }}]" id="{{ $gaEleId }}_geo_area_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $geo_area_checked))>
                    <label for="{{ $gaEleId }}_geo_area_{{ $item->id }}" class="form-check-label">{{ $item->name . ' (' . $item->code . ')' }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => $gaEleId . '_ga'])