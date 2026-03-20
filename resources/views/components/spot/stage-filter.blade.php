{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('stage_id')) ? '-fill' : '' }}"></i>
        @isset(request()->stage_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('stage_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-white">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="stage_id_all">
            <label for="stage_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $stage_checked = (request()->has('stage_id')) ? request()->get('stage_id') : [];
        @endphp
        @foreach ($stages as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input stage_id_filter" name="stage_id[{{ $item->id }}]" id="stage_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $stage_checked))>
                <label for="stage_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'stage_id'])