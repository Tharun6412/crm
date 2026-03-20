{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('sub_stage_id')) ? '-fill' : '' }}"></i>
        @isset(request()->sub_stage_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('sub_stage_id')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-white" style="min-width: 250px; max-height: 320px; overflow-y: auto;">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="sub_stage_id_all">
            <label for="sub_stage_id_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $sub_stage_id_checked = (request()->has('sub_stage_id')) ? request()->get('sub_stage_id') : [];
        @endphp
        @foreach ($stages as $parent)
            <li class="form-check-label ms-3">{{ $parent->name }}</li>
            @foreach ($parent->children as $child)
                <li class="dropdown-item">
                    <input type="checkbox" class="form-check-input sub_stage_id_filter" name="sub_stage_id[{{ $child->id }}]" id="sub_stage_id_{{ $child->id }}" value="{{ $child->id }}" @checked(in_array($child->id, $sub_stage_id_checked))>
                    <label for="sub_stage_id_{{ $child->id }}" class="form-check-label">{{ $child->name }}</label>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'sub_stage_id'])