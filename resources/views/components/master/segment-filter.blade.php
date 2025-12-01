{{-- Segments filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('segments')) ? '-fill' : '' }}"></i>
        @isset(request()->segments)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('segments')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $segments_checked = (request()->has('segments')) ? request()->get('segments') : [];
            @endphp
            @foreach ($segments as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="segments[{{ $item->id }}]" id="segment_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $segments_checked))>
                    <label for="segment_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>