{{-- Invoice item types filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('item_types')) ? '-fill' : '' }}"></i>
        @isset(request()->item_types)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('item_types')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $item_types_checked = (request()->has('item_types')) ? request()->get('item_types') : [];
            @endphp
            @foreach ($item_types as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="item_types[{{ $item->id }}]" id="segment_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $item_types_checked))>
                    <label for="segment_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>