{{-- Consumer status filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('cns_status')) ? '-fill' : '' }}"></i>
        @isset(request()->cns_status)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('cns_status')) }}
            </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $cns_status_checked = (request()->has('cns_status')) ? request()->get('cns_status') : [];
        @endphp
        @foreach ($cns_status as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="cns_status[{{ $item->id }}]" id="cns_status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $cns_status_checked))>
                <label for="cns_status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'cns_status'])