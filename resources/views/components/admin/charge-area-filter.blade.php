{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('charge_area')) ? '-fill' : '' }}"></i>
        @isset(request()->charge_area)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('charge_area')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light" style="max-height: 300px; overflow-y: auto;">
        {{-- Checkbox to select all or clear --}}
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="charge_area_all">
            <label for="charge_area_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $geo_area_checked = (request()->has('charge_area')) ? request()->get('charge_area') : [];
        @endphp
        @foreach ($charge_areas as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input charge_area_filter" name="charge_area[{{ $item->id }}]" id="charge_area_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $geo_area_checked))>
                <label for="charge_area_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'charge_area'])