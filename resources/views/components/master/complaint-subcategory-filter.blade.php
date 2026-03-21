{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('subcategory')) ? '-fill' : '' }}"></i>
        @isset(request()->subcategory)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('subcategory')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="subcategory_all">
                <label for="subcategory_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $subcategory_checked = (request()->has('subcategory')) ? request()->get('subcategory') : [];
            @endphp
            
            @foreach ($categories as $category)
                <li class="list-group-item fw-semibold">{{ $category->name }}</li>
                @if ($category->children->count() > 0)
                    @foreach ($category->children as $item)
                        <li class="list-group-item">
                            <input type="checkbox" class="form-check-input subcategory_filter" name="subcategory[{{ $item->id }}]" id="subcategory_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $subcategory_checked))>
                            <label for="subcategory_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'subcategory'])