{{-- Complaint status filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('cmp_status')) ? '-fill' : '' }}"></i>
        @isset(request()->cmp_status)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('cmp_status')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $cmp_status_checked = (request()->has('cmp_status')) ? request()->get('cmp_status') : [];
            @endphp
            @foreach ($cmp_status as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="cmp_status[{{ $item->id }}]" id="cmp_status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $cmp_status_checked))>
                    <label for="cmp_status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>