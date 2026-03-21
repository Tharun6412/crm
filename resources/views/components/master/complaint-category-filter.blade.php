{{-- Proposal list GA filter --}}
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
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="category_all">
                <label for="category_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $category_checked = (request()->has('category')) ? request()->get('category') : [];
            @endphp
            @foreach ($categories as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input category_filter" name="category[{{ $item->id }}]" id="category_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $category_checked))>
                    <label for="category_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'category'])