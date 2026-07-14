{{-- Complaint type Filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('types')) ? '-fill' : '' }}"></i>
        @isset(request()->types)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('types')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="types_all">
                <label for="types_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $category_checked = (request()->has('types')) ? request()->get('types') : [];
            @endphp
            @foreach ($types as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input types_filter" name="types[{{ $item->id }}]" id="types_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $category_checked))>
                    <label for="types_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'types'])