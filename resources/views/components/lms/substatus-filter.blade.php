{{-- Child status filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('child_status_id')) ? '-fill' : '' }}"></i>
        @isset(request()->child_status_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('child_status_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-white" style="min-width: 250px; max-height: 320px; overflow-y: auto;">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="child_status_id_all">
            <label for="child_status_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $child_status_id_checked = (request()->has('child_status_id')) ? request()->get('child_status_id') : [];
        @endphp
        @foreach ($status as $parent)
            <li class="form-check-label ms-3">{{ $parent->name }}</li>
            @foreach ($parent->children as $child)
                <li class="dropdown-item">
                    <input type="checkbox" class="form-check-input child_status_id_filter" name="child_status_id[{{ $child->id }}]" id="child_status_id_{{ $child->id }}" value="{{ $child->id }}" @checked(in_array($child->id, $child_status_id_checked))>
                    <label for="child_status_id_{{ $child->id }}" class="form-check-label">{{ $child->name }}</label>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'child_status_id'])