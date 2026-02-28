{{-- Payment Gateways Filter --}}

<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('payment_gateways')) ? '-fill' : '' }}"></i>
        @isset(request()->payment_gateways)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('payment_gateways')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $payment_gateways_checked = (request()->has('payment_gateways')) ? request()->get('payment_gateways') : [];
        @endphp
        @foreach ($payment_gateways as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="payment_gateways[{{ $item->id }}]" id="payment_gateway_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $payment_gateways_checked))>
                <label for="payment_gateway_{{ $item->id }}" class="form-check-label">{{ $item->gateway }}</label>
            </li>
        @endforeach
    </ul>
</div>