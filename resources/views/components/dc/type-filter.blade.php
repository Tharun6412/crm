{{-- Document types filter dropdown --}}
<div class="dropdown float-end">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('doc_types')) ? '-fill' : '' }}"></i>
        @isset(request()->doc_types)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('doc_types')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="doc_type_all">
            <label for="doc_type_all" class="form-check-label">All</label>
        </li>
        @php
            $doc_types_checked = (request()->has('doc_types')) ? request()->get('doc_types') : [];
        @endphp
        @foreach ($doc_types as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input doc_type_filter" name="doc_types[{{ $item->id }}]" id="doc_type_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $doc_types_checked))>
                <label for="doc_type_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'doc_type'])