{{-- list Scheme filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('scheme')) ? '-fill' : '' }}"></i>
        @isset(request()->scheme)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('scheme')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="scheme_all">
                <label for="scheme_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $scheme_checked = (request()->has('scheme')) ? request()->get('scheme') : [];
            @endphp
            @foreach ($schemes as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input scheme_filter" name="scheme[{{ $item->id }}]" id="scheme_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $scheme_checked))>
                    <label for="scheme_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'scheme'])