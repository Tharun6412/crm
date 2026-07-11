{{-- Consumer Verification Steps filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-outline-info" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        Issue Areas&nbsp;<i class="bi bi-sliders2{{ (request()->has('verify_steps')) ? '-fill' : '' }}"></i>
        @isset(request()->verify_steps)        
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('verify_steps')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $verify_steps_checked = (request()->has('verify_steps')) ? request()->get('verify_steps') : [];
            @endphp
            @foreach ($verify_steps as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="verify_steps[{{ $item->id }}]" id="verify_steps_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $verify_steps_checked))>
                    <label for="verify_steps_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>