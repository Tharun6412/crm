{{-- Consumer status filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('geyser_status')) ? '-fill' : '' }}"></i>
        @isset(request()->geyser_status)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('geyser_status')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $geyser_status_checked = (request()->has('geyser_status')) ? request()->get('geyser_status') : [];
            @endphp
            @foreach ($geyser_status as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="geyser_status[{{ $item->id }}]" id="geyser_status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $geyser_status_checked))>
                    <label for="geyser_status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>