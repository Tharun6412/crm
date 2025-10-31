{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('industrial_area_id')) ? '-fill' : '' }}"></i>
        @isset(request()->industrial_area_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('industrial_area_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @if(request()->geo_area)    
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" id="industrial_area_id_all">
                <label for="industrial_area_id_all" class="form-check-label">All or clear</label>
            </li>
        @else
            <li class="dropdown-item">
                <label>Please select GA</label>
            </li>
        @endif
        @php
            $industrial_area_checked = (request()->has('industrial_area_id')) ? request()->get('industrial_area_id') : [];
        @endphp
        @foreach ($industrial_areas as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input industrial_area_id_filter" name="industrial_area_id[{{ $item->id }}]" id="industrial_area_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $industrial_area_checked))>
                <label for="industrial_area_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'industrial_area_id'])