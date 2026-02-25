{{-- Prospect Status filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('status_id')) ? '-fill' : '' }}"></i>
        @isset(request()->status_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('status_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light" style="min-width: 250px; max-height: 320px; overflow-y: auto;">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="status_id_all">
            <label for="status_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $status_id_checked = (request()->has('status_id')) ? request()->get('status_id') : [];
        @endphp
        @foreach ($status as $list)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input status_id_filter" name="status_id[{{ $list->id }}]" id="status_id_{{ $list->id }}" value="{{ $list->id }}" @checked(in_array($list->id, $status_id_checked))>
                <label for="status_id_{{ $list->id }}" class="form-check-label">{{ $list->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'status_id'])