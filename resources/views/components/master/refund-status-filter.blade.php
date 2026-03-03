{{-- Segments filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('refund_status')) ? '-fill' : '' }}"></i>
        @isset(request()->refund_status)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('refund_status')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="refund_status_all">
                <label for="refund_status_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $refund_status_checked = (request()->has('refund_status')) ? request()->get('refund_status') : [];
            @endphp
            @foreach ($status_list as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="refund_status[{{ $item->id }}]" id="refund_status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $refund_status_checked))>
                    <label for="refund_status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'refund_status'])
