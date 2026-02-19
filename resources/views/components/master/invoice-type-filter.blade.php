{{-- Invoice Types Filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('invoice_type')) ? '-fill' : '' }}"></i>
        @isset(request()->invoice_type)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('invoice_type')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="invoice_type_all">
                <label for="invoice_type_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $invoice_types_checked = (request()->has('invoice_type')) ? request()->get('invoice_type') : [];
            @endphp
            @foreach ($invoice_types as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input invoice_type_filter" name="invoice_type[{{ $item->id }}]" id="invoice_type_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $invoice_types_checked))>
                    <label for="invoice_type_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'invoice_type'])
