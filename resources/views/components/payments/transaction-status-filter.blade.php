{{-- Transaction status filter --}}

<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('transaction_status')) ? '-fill' : '' }}"></i>
        @isset(request()->transaction_status)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('transaction_status')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $transaction_status_checked = (request()->has('transaction_status')) ? request()->get('transaction_status') : [];
        @endphp
        @foreach ($transaction_status as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="transaction_status[{{ $item->id }}]" id="transaction_status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $transaction_status_checked))>
                <label for="transaction_status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>