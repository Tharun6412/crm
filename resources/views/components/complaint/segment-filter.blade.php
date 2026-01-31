{{-- Complaint Segment filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('segment_id')) ? '-fill' : '' }}"></i>
        @isset(request()->segment_id)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('segment_id')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $cmp_segment_checked = (request()->has('segment_id')) ? request()->get('segment_id') : [];
            @endphp
            @foreach ($segments as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="segment_id[{{ $item->id }}]" id="segment_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $cmp_segment_checked))>
                    <label for="segment_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>