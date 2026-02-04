{{-- list Scheme filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('district')) ? '-fill' : '' }}"></i>
        @isset(request()->district)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('district')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input" id="district_all">
                <label for="district_all" class="form-check-label">All or clear</label>
            </li>
            @php
                $district_checked = (request()->has('district')) ? request()->get('district') : [];
            @endphp
            @foreach ($districts as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input district_filter" name="district[{{ $item->id }}]" id="district_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $district_checked))>
                    <label for="district_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'district'])