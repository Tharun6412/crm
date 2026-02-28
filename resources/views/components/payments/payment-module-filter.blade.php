{{-- Payment modules --}}

<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('payment_modules')) ? '-fill' : '' }}"></i>
        @isset(request()->payment_modules)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('payment_modules')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $payment_modules_checked = (request()->has('payment_modules')) ? request()->get('payment_modules') : [];
        @endphp
        @foreach ($payment_modules as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="payment_modules[{{ $item->id }}]" id="payment_module_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $payment_modules_checked))>
                <label for="payment_module_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>