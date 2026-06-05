{{-- Area filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('subarea')) ? '-fill' : '' }}"></i>
        @isset(request()->subarea)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('subarea')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light" style="max-height: 300px; overflow-y: auto;">
        {{-- Checkbox to select all or clear --}}
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="subarea_all">
            <label for="subarea_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $area_checked = (request()->has('subarea')) ? request()->get('subarea') : [];
        @endphp
        @foreach ($subareas as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input area_filter" name="subarea[{{ $item->id }}]" id="subarea_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $area_checked))>
                <label for="subarea_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'subarea'])