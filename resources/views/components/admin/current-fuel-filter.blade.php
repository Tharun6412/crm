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
    <ul class="dropdown-menu bg-light">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="fuel_id_all">
            <label for="fuel_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $fuel_checked = (request()->has('fuel_id')) ? request()->get('fuel_id') : [];
        @endphp
        @foreach ($fuel_list as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input fuel_id_filter" name="fuel_id[{{ $item->id }}]" id="fuel_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $fuel_checked))>
                <label for="fuel_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'fuel_id'])