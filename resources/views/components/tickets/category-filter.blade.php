{{-- Category filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('category')) ? '-fill' : '' }}"></i>
        @isset(request()->category)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('category')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $ticket_category_checked = (request()->has('category')) ? request()->get('category') : [];
            @endphp
            @foreach ($category_filter as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="category[{{ $item->id }}]" id="category_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $ticket_category_checked))>
                    <label for="category_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>